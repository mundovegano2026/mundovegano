<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Category;
use App\Product;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection as CategoryCollectionResource;
use App\Http\Resources\ProductCollection as ProductCollectionResource;
use DB;


class CategoriesAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Get User Location
        $centroid = '';

        if(false && $request->user()) {
            $distritoSearch = $request->user()->distrito;
            $concelhoSearch = $request->user()->concelho;
            if($concelhoSearch) {
                $concelhoData = Caop::select(DB::raw('astext( Centroid(SHAPE) ) as centroid'))
                ->where('concelho', $concelhoSearch)
                ->firstOrFail();
                $centroid = $concelhoData->centroid;
            } else if($distritoSearch) {
                $distritoData = Caop::select(DB::raw('astext( Centroid(SHAPE) ) as centroid'))
                ->where('concelho', $distritoSearch)
                ->firstOrFail();
                $centroid = $distritoData->centroid;
            }
        }

        // Get Main Categories
        $categories = Category::orderBy('name', 'ASC')->where('level', 1)->orWhere('level', 2)->get();

    // $productList = $productList->withCount(['stores as min_distance' => function($query) use ($centroid) {
    //     $query->select(DB::raw("coalesce(MIN( GLength( LineStringFromWKB( LineString( location, GeomFromText('" . $centroid . "') ) ) ) ) )"));
    // }])->orderBy('min_distance')->having('min_distance', '>', 0);

        // Get subcategories for each main category
        foreach( $categories as $cat ) {

            // $cat->subCategories = new CategoryCollectionResource(Category::with('products')->where('parent_id', $cat->id)->orderBy('name')->get());

            $cat->subCategories = new CategoryCollectionResource(Category::with([
                        'products' => function($query) {
                            $query->take(10);
                        }
                    ])->where('parent_id', $cat->id)->orderBy('name')->get());

            // Get subcategories for each subcategory
            foreach( $cat->subCategories as $subcat ) {

                $subcat->subCategories = new CategoryCollectionResource(Category::with([
                    'products' => function($query) {
                        $query->take(10);
                    }
                ])->where('parent_id', $subcat->id)->orderBy('name')->get());

            }
        }

        // Return collection of categories as a resource
        return CategoryResource::collection($categories);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $category = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;

    //     $category->id = $request->input('article_id');
    //     $category->title = $request->input('title');
    //     $category->body = $request->input('body');

    //     if($category->save()) {
    //         return new CategoryResource($category);
    //     }

    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productList(Request $request)
    {
        $id = $request->id;
        $sortBy = $request->order;
        $distrito = isset($request->filter) ? $request->filter["distrito"] : '';
        $concelho = isset($request->filter) ? $request->filter["concelho"] : '';
        $brand = isset($request->filter) ? $request->filter["brand"] : '';
        $store = isset($request->filter) ? $request->filter["store"] : '';


        $category = Category::with([
            'products' => function($query) {
                $query->take(10);
            }
        ])->findOrFail($id);
        $categoryId = $category->id;
        $productList = Product::whereExists(function($query) use($categoryId)
            {
                $query->select(DB::raw(1))
                    ->from('categorytrees')
                    ->whereRaw('products.status_id = 2 AND categorytrees.category_child = products.category_id AND categorytrees.category_parent = ' . $categoryId);
            }
        );

        if($sortBy == "RATE") {

            $productList = $productList->withCount(['reviews as average_rating' => function($query) {
                $query->select(DB::raw('coalesce(avg(score),0)'));
            }])->orderByDesc('average_rating');


        } else if($sortBy == "PRICE") {

            $productList = $productList->withCount(['stores as average_price' => function($query) {
                $query->select(DB::raw('coalesce(avg(price),0)'));
            }])->orderByDesc('average_price');

        } else if($sortBy == "PROX") {

            // Get User Location
            $centroid = '';
            $distritoSearch = $request->user()->distrito;
            $concelhoSearch = $request->user()->concelho;
            if($concelhoSearch) {
                $concelhoData = Caop::select(DB::raw('astext( Centroid(SHAPE) ) as centroid'))
                ->where('concelho', $concelhoSearch)
                ->firstOrFail();
                $centroid = $concelhoData->centroid;
            } else if($distritoSearch) {
                $distritoData = Caop::select(DB::raw('astext( Centroid(SHAPE) ) as centroid'))
                ->where('concelho', $distritoSearch)
                ->firstOrFail();
                $centroid = $distritoData->centroid;
            }


            $productList = $productList->withCount(['stores as min_distance' => function($query) use ($centroid) {
                $query->select(DB::raw("coalesce(MIN( GLength( LineStringFromWKB( LineString( location, GeomFromText('" . $centroid . "') ) ) ) ) )"));
            }])->orderBy('min_distance')->having('min_distance', '>', 0);


        } else if($sortBy == "NAME") {

            $productList = $productList->orderBy('name');

        } else if($sortBy == "DATE") {

                $productList = $productList->orderBy('updated_at');

        } else {
            // Sort by relevance (Default)
            $productList = $productList->orderBy("name", "DESC");
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


        $category->products = $productList;

        $subCategories = new CategoryCollectionResource(Category::with([
            'products' => function($query) {
                $query->take(10);
            }
        ])->where('parent_id', $id)->orderBy('name')->get());

        $category->subCategories = $subCategories;

        // Return single article as a resource
        return new CategoryResource($category);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with([
            'products' => function($query) {
                $query->take(10);
            }
        ])->findOrFail($id);
        $categoryId = $category->id;
        $productList = Product::whereExists(function($query) use($categoryId)
            {
                $query->select(DB::raw(1))
                    ->from('categorytrees')
                    ->whereRaw('products.status_id = 2 AND categorytrees.category_child = products.category_id AND categorytrees.category_parent = ' . $categoryId);
            }
        )->take(100)->get();

        $category->products = $productList;

        $subCategories = new CategoryCollectionResource(Category::with([
            'products' => function($query) {
                $query->take(10);
            }
        ])->where('parent_id', $id)->orderBy('name')->get());

        $category->subCategories = $subCategories;

        // Return single article as a resource
        return new CategoryResource($category);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function categoryByName($name)
    {
        // Get article
        // $category = Category::with('products')->where('name', $name)->take(1)->first();
        $category = Category::with(['products' => function ($query) {
            $query->where('status_id', 2)->take(100);
        }])->where('name', $name)->take(1)->first();

        // $subCategories = new CategoryCollectionResource(Category::with('products')->where('parent_id', $category->id)->orderBy('name')->get());
        $subCategories = new CategoryCollectionResource(Category::with(['products' => function ($query) {
            $query->where('status_id', 2)->take(100);
        }])->where('parent_id', $category->id)->orderBy('name')->get());
        $category->subCategories = $subCategories;
        // Return single article as a resource
        return new CategoryResource($category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get article
        $category = Category::findOrFail($id);

        if($category->delete()) {
            return new CategoryResource($category);
        }
    }

    public function fetch(Request $request) {

        $categoryList = DB::table('categories')
            ->where('parent_id', $request->id)
            ->orderBy('name', 'ASC')
            ->get();

        return new CategoryCollectionResource($categoryList);

    }

    public function fetchname(Request $request) {
        return
        $categoryList = DB::table('categories')
            ->where('name', 'LIKE', '%' . $request->name . '%')
            ->orderBy('name', 'ASC')
            ->take(10)
            ->get();

        return new CategoryCollectionResource($categoryList);

    }
}
