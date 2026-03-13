<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Resources\ProductCollection as ProductResourceCollection;
use App\Http\Resources\ValueList as ValuelistResource;
use App\Product;
use App\Category;
use App\Review;
use App\Valuelist;
use App\Caop;
use DB;
use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

class SearchAPIController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productListOld($searchText, $sortBy, $distrito, $concelho, $brand, $store)
    {

        $productList = Product::selectRaw("*, MATCH(name) AGAINST ('". $searchText . "*' IN BOOLEAN MODE) AS relevance_score");

        if($sortBy == "RATE") {

            $productList = $productList->withCount(['reviews as average_rating' => function($query) {
                $query->select(DB::raw('coalesce(avg(score),0)'));
            }])->orderByDesc('average_rating');

        } else if($sortBy == "PRICE") {

            $productList = $productList->withCount(['stores as average_price' => function($query) {
                $query->select(DB::raw('coalesce(avg(price),0)'));
            }])->orderByDesc('average_price');

        } else if($sortBy == "PROX") {


            $concelhoSearch = "CARTAXO";
            $concelhoData = Caop::select(DB::raw('astext( Centroid(SHAPE) ) as centroid'))
            ->where('concelho', $concelhoSearch)
            ->firstOrFail();
            $centroid = $concelhoData->centroid;


            $productList = $productList->withCount(['stores as min_distance' => function($query) use ($centroid) {
                $query->select(DB::raw("coalesce(MIN( GLength( LineStringFromWKB( LineString( location, GeomFromText('" . $centroid . "') ) ) ) ) )"));
            }])->orderBy('min_distance')->having('min_distance', '>', 0);


        } else if($sortBy == "NAME") {

            $productList = $productList->orderBy('name');

        } else if($sortBy == "DATE") {

                $productList = $productList->orderBy('updated_at');

        } else {
            // Sort by relevance (Default)
            $productList = $productList->orderBy("relevance_score", "DESC");
        }

        if($concelho!="null") {
            $productList->whereHas('stores.caop', function($q) use ($concelho) {
                $q->where('concelho', $concelho);
            });
        } else if($distrito!="null") {
            $productList->whereHas('stores.caop', function($q) use ($distrito) {
                $q->where('distrito', $distrito);
            });
        }

        if($brand!="null") {
            $productList->whereHas('brand', function($q) use ($brand) {
                $q->where('name', 'LIKE', '%' . $brand . '%');
            });
        }

        if($store!="null") {
            $productList->whereHas('stores', function($q) use ($store) {
                $q->where('name', 'LIKE', '%' . $store . '%');
            });
        }

        $productList = $productList->take(100)->get();

        return new ProductResourceCollection($productList);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function productList(Request $request)
    {
        $searchText = $request->searchText;
        $searchCategory = $request->searchCategory;
        $sortBy = $request->order;
        $distrito = $request->filter["distrito"];
        $concelho = $request->filter["concelho"];
        $brand = $request->filter["brand"];
        $store = $request->filter["store"];
        $distance = isset($request->distance) ? intval($request->distance) * 1000 : 5000;
        $centerPoint = "";

        // $productList = Product::selectRaw("*, MATCH(name) AGAINST ('". $searchText . "*' IN BOOLEAN MODE) AS relevance_score")
        //                         ->where('status_id', 2)
        //                         ->whereRaw("MATCH(name) AGAINST ('". $searchText . "*' IN BOOLEAN MODE) > 0");

        // Get User Location
        if($request->filter["distrito"] != "null") {

            $centroid = '';
            $distritoSearch = $request->filter["distrito"];
            $concelhoSearch = $request->filter["concelho"];
            if($concelhoSearch != "null") {
                $concelhoData = Caop::selectRaw(DB::raw('astext( Centroid(SHAPE) ) as centroid'))
                ->where('concelho', $concelhoSearch)
                ->firstOrFail();
                $centroid = $concelhoData->centroid;
            } else  {
                $distritoData = Caop::selectRaw(DB::raw('astext( Centroid(SHAPE) ) as centroid'))
                ->where('distrito', $distritoSearch)
                ->firstOrFail();
                $centroid = $distritoData->centroid;
            }

            // et location point
            $centerPoint = $centroid;

        } else {

            $coords =  [];

            if($request->lat && $request->lon) {
                $coords["latitude"] = $request->lat;
                $coords["longitude"] = $request->lon;
            } else {
                $ip = request()->ip();
                if($ip == '127.0.0.1')
                    $ip = '93.108.241.222';
                $locationData = \Location::get($ip);
                $coords["latitude"] = $locationData->latitude;
                $coords["longitude"] = $locationData->longitude;
            }

            // Initialise Proj4
            $proj4 = new Proj4php();

            // Create two different projections.
            $projL93    = new Proj('EPSG:3763', $proj4);
            $projWGS84  = new Proj('EPSG:4326', $proj4);

            // Create a point.
            $pointSrc = new Point($coords["longitude"], $coords["latitude"], $projWGS84);

            // Transform the point between datums.
            $pointDest = $proj4->transform($projL93, $pointSrc);

            // Set Location Point
            $centerPoint = "POINT(" . $pointDest->x . " " . $pointDest->y . ")";

        }

        // Order by distance to location
        // $productList = $productList->withCount(['stores as distance' => function($query) use ($centerPoint) {
        //     $query->select(DB::raw("coalesce(MIN( GLength( LineStringFromWKB( LineString( location, GeomFromText('" . $centerPoint . "') ) ) ) ) )"));
        // }])->having('distance', '>', 0)->having('distance', '<', $distance);


        $pQueryPluck = "SELECT p.*, MATCH(p.name) AGAINST ('". $searchText . "*' IN BOOLEAN MODE) AS relevance_score, GLength( LineStringFromWKB( LineString( s.location, GeomFromText('" . $centerPoint . "') ) ) ) as distance";

        if($sortBy == "RATE") {
            $pQueryPluck .= ", AVG(r.score) as average_rating";
        } else if($sortBy == "PRICE") {
            $pQueryPluck .= ", AVG(ps.price) as average_price";
        }

        $pQueryPluck .= " FROM products p";

        $pQuerySort = " ORDER BY distance";

        $pQueryJoin1 = " JOIN product_stores ps ON p.id = ps.product_id
        JOIN stores s ON s.id = ps.store_id";

        $pQueryJoin2 = " JOIN product_chains pc ON p.id = pc.product_id
        JOIN stores s ON s.chain_id = pc.chain_id";
        if($sortBy == "PRICE") {
            $pQueryJoin2 .= " JOIN product_stores ps ON p.id = ps.product_id";
        }

        // Sorting Options
        if($sortBy == "RATE") {
            $pQueryJoin1 .= " LEFT JOIN reviews r ON r.product_id = p.id";
            $pQueryJoin2 .= " LEFT JOIN reviews r ON r.product_id = p.id";
            $pQuerySort = " ORDER BY average_rating";
        } else if($sortBy == "PRICE") {
            $pQuerySort = " ORDER BY average_price";
        } else if($sortBy == "NAME") {
            $pQuerySort = " ORDER BY name";
        } else if($sortBy == "DATE") {
            $pQuerySort = " ORDER BY updated_at desc";
        }else {
            $pQuerySort = " ORDER BY relevance_score";
        }

        $pQueryConditions = " WHERE p.status_id = 2 AND MATCH(p.name) AGAINST ('". $searchText . "*' IN BOOLEAN MODE) > 0 AND GLength( LineStringFromWKB( LineString( s.location, GeomFromText('" . $centerPoint . "') ) ) ) < " . $distance;

        if($searchCategory) {
            $categoryName = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $searchCategory);
            $searchCategoryId = Category::where('name', trim($categoryName))->first()->id;
            $searchCategoryList = DB::table('categorytrees')->where('category_parent', $searchCategoryId)->pluck('category_child');

            $pQueryConditions .= " AND p.category_id IN (" . implode(', ', $searchCategoryList->toArray()) . ")";

        }

        if($concelho!="null") {
            // $productList->whereHas('stores.caop', function($q) use ($concelho) {
            //     $q->where('concelho', $concelho);
            // });
            $pQueryJoin1 .= " JOIN caops local ON local.id = s.caop_id";
            $pQueryJoin2 .= " JOIN caops local ON local.id = s.caop_id";
            $pQueryConditions .= ' AND local.concelho = "' . $concelho . '"';
        } else if($distrito!="null") {
            // $productList->whereHas('stores.caop', function($q) use ($distrito) {
            //     $q->where('distrito', $distrito);
            // });
            $pQueryJoin1 .= " JOIN caops local ON local.id = s.caop_id";
            $pQueryJoin2 .= " JOIN caops local ON local.id = s.caop_id";
            $pQueryConditions .= ' AND local.distrito = "' . $distrito . '"';
        }

        if($brand!="null") {
            // $productList->whereHas('brand', function($q) use ($brand) {
            //     $q->where('name', 'LIKE', '%' . $brand . '%');
            // });
            $pQueryJoin1 .= " JOIN brands b ON b.id = p.brand_id";
            $pQueryJoin2 .= " JOIN brands b ON b.id = p.brand_id";
            $pQueryConditions .= ' AND b.name LIKE "%' . $brand . '%"';
        }

        if($store!="null") {
            // $productList->whereHas('stores', function($q) use ($store) {
            //     $q->where('name', 'LIKE', '%' . $store . '%');
            // });
            $pQueryConditions .= ' AND s.name LIKE "%' . $store . '%"';
        }

        // $productList = $productList->take(100)->get();
        $pQueryGroup = " GROUP BY id";

        $pQuery = "SELECT * FROM (" . $pQueryPluck . $pQueryJoin1 . $pQueryConditions . " UNION " . $pQueryPluck . $pQueryJoin2 . $pQueryConditions . ") a" . $pQueryGroup . $pQuerySort . " LIMIT 100";

        $productList = DB::select(DB::raw($pQuery));

        $productList = Product::hydrate($productList);

        return response()->json([
            'error' => false,
            'message' => 'Pesquisa concluída.',
            'productList' => new ProductResourceCollection($productList)
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function valueList(String $name)
    {
        $value = Valuelist::where('name', $name)->first();

        return response()->json([
            'error' => false,
            'message' => 'Pesquisa concluída.',
            'value' => new ValuelistResource($value)
        ]);
    }


}
