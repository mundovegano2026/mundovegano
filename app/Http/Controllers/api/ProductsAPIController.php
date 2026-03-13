<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Product;
use App\Review;
use App\Product_report;
use App\Product_history;
use App\Report_type;
use App\Tag;
use App\User;
use App\Brand;
use App\Store;
use App\Chain;
use App\View_store;
use App\Image;
use App\Caop;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\Store as StoreResource;
use App\Http\Resources\Review as ReviewResource;
use App\Http\Resources\Report as ReportResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\ProductCollection as ProductCollectionResource;
use App\Http\Resources\Report_typeCollection as Report_typeCollectionResource;
use App\Http\Resources\View_storeCollection as View_storeCollectionResource;
use DB;
use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

class ProductsAPIController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get product
        $product = Product::with(['stores', 'chains', 'chains.stores', 'tags', 'user', 'admin', 'reviews'])->findOrFail($id);
        $chainList = $product->chains->pluck('id')->toArray();

        // Get User Location
        // $ip = \Request::ip();
        $ip = request()->ip();
        if($ip == '127.0.0.1')
            $ip = '93.108.241.222';
        $locationData = \Location::get($ip);

        // Initialise Proj4
        $proj4 = new Proj4php();

        // Create two different projections.
        $projL93    = new Proj('EPSG:3763', $proj4);
        $projWGS84  = new Proj('EPSG:4326', $proj4);

        // Create a point.
        $pointSrc = new Point($locationData->longitude, $locationData->latitude, $projWGS84);

        // echo "Source: " . $pointSrc->toShortString() . " in WGS84 <br>";

        // Transform the point between datums.
        $pointDest = $proj4->transform($projL93, $pointSrc);

        // $distance = 10000;

        $rawSelect = "*, ST_AsText(location), ST_Length(

                                        LineString(
                                            location,
                                            ST_GeomFromText(
                                                'POINT(" . $pointDest->x . " " . $pointDest->y . ")'
                                            )
                                        )

                                ) AS dist";


        $chainStoreList = Store::select(DB::raw($rawSelect))
                        ->whereNotNull('location')
                        // ->havingRaw('dist < ?', [$distance])
                        ->havingRaw('dist > ?', [0])
                        ->with(['chain'])
                        ->get();


        foreach($chainStoreList as $store) {

            $chainStore = new View_store($store->toArray());
            // $chainStore->pivot = array('price' => $chain->pivot_price);
            $chainStore->pivot = array('price' => $store->chain->pivot_price);
            $chainStore->id = rand(1000, 2000);
            $product->view_stores->push($chainStore);

        }


        $productTags = array_column($product->tags->toArray(), 'name');
        $associatedTags = Tag::with([
            'products' => function($query) {
                $query->take(10);
            }
        ])->whereIn('name', $productTags)->where('status_id', 2)->take(5)->get();
        $associatedProducts = array();
        foreach($associatedTags as $tag) {
            foreach($tag->products as $prod) {
                if($prod->id != $product->id) {
                    array_push($associatedProducts, new ProductResource($prod));
                }
            }
        }

        $product->similarProducts = $associatedProducts;

        return new ProductResource($product);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCoord(Request $request)
    {
        // Get product
        $product = Product::with(['stores', 'chains', 'chains.stores', 'tags', 'user', 'admin', 'reviews'])->findOrFail($request->id);

        // Get Product stores
        $storeIdList = $product->stores->pluck('id')->toArray();
        $fullStoreList = new View_storeCollectionResource(array());

        // Get Product chains
        $chainList = $product->chains->pluck('id')->toArray();
        $product->chainList = $chainList;

        // Get User Location

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

        $distance = 10000;

        $rawSelect = "*, ST_AsText(location) as text_location, ST_Length(

                                LineString(
                                    location,
                                    ST_GeomFromText(
                                        'POINT(" . $pointDest->x . " " . $pointDest->y . ")'
                                    )
                                )

                        ) AS dist";


        $storeList = View_store::select(DB::raw($rawSelect))
                        ->with('chain', 'products')
                        ->whereIn('chain_id', $chainList)
                        ->orWhereIn('id', $storeIdList)
                        ->whereNotNull('location')
                        // ->havingRaw('dist < ?', [$distance])
                        ->havingRaw('dist > ?', [0])
                        ->get();

        foreach($storeList as $storeItem) {

            //$store = new View_store($storeItem->toArray());
            $store = $storeItem;

            $storeProduct = $storeItem->products->where('id', $product->id)->first();

            $store->distance = $storeItem->dist;

            // If store directly associated to product
            if($storeProduct && $storeProduct->pivot) {
                $store->pivot = array('price' => $storeProduct->pivot->price);
            } else {
                // If store associated through a chain
                $chainProduct = $product->chains->where('id', $store->chain_id)->first();
                $store->pivot = array('price' => $chainProduct->pivot->price);
            }

            $store->id = rand(1000, 2000);

            $fullStoreList->push($store);

        }

        // Add stores to product, sorted by distance
        $product->view_stores = $fullStoreList->sortBy("distance");

        // Product Tags and Associated Products
        $productTags = array_column($product->tags->toArray(), 'name');
        $associatedTags = Tag::with('products')->whereIn('name', $productTags)->where('status_id', 2)->take(5)->get();
        $associatedProducts = array();
        foreach($associatedTags as $tag) {
            foreach($tag->products as $prod) {
                if($prod->id != $product->id) {
                    array_push($associatedProducts, new ProductResource($prod));
                }
            }
        }

        $product->similarProducts = $associatedProducts;

        return new ProductResource($product);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newList(Request $request)
    {

        // Get User Location
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

        // Get product
        $rawSelect = "ST_Length(

            LineString(
                location,
                ST_GeomFromText(
                    'POINT(" . $pointDest->x . " " . $pointDest->y . ")'
                )
            )

        ) AS dist";
        // $productList = Product::with(['tags'])->orderBy('created_at', 'desc')->where('status_id', 2)->limit(10)->get();
        $productList = Product::with(['tags']);

        $productList = $productList->withCount(['stores as min_distance' => function($query) use ($rawSelect) {
            $query->select(DB::raw($rawSelect))->orderby('dist')->limit(1); // Need to order by closest!?
        }])->orderBy('min_distance')->having('min_distance', '<', 5000); // Need to configure distance!?

        $productList = $productList->where('status_id', 2)->limit(10)->get();







        return new ProductCollectionResource($productList);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showName($name)
    {
        // Get product
        $product = Product::with(['stores', 'tags', 'user', 'admin', 'reviews'])->where('name', $name)->first();


        $productTags = array_column($product->tags->toArray(), 'name');
        $associatedTags = Tag::with('products')->whereIn('name', $productTags)->take(5)->get();
        $associatedProducts = array();
        foreach($associatedTags as $tag) {
            foreach($tag->products as $prod) {
                if($prod->id != $product->id) {
                    array_push($associatedProducts, new ProductResource($prod));
                }
            }
        }
        foreach($product->stores as $store) {
            $store->text_location = View_store::find($store->id)->text_location;
            $store->text_location = str_replace('POINT','', $store->text_location);
            $store->text_location = str_replace('(', '', $store->text_location);
            $store->text_location = str_replace(')', '', $store->text_location);
        }
        $product->similarProducts = $associatedProducts;

        return new ProductResource($product);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNameCoord(Request $request)
    {
        // Get product
        $product = Product::with(['stores', 'tags', 'user', 'admin', 'reviews'])->where('name', $request->name)->first();
        $chainList = $product->chains->pluck('id')->toArray();
        $product->chainList = $chainList;

        // Get Product stores
        $storeIdList = $product->stores->pluck('id')->toArray();
        $closeStoreList = new View_storeCollectionResource(array());

        // Get User Location
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

        $distance = 10000;

        $rawSelect = "*, ST_AsText(location) as text_location, ST_Length(

                                LineString(
                                    location,
                                    ST_GeomFromText(
                                        'POINT(" . $pointDest->x . " " . $pointDest->y . ")'
                                    )
                                )

                        ) AS dist";

        $storeList = Store::select(DB::raw($rawSelect))
        ->with('chain', 'products')
        ->whereIn('chain_id', $chainList)
        ->orWhereIn('id', $storeIdList)
        ->whereNotNull('location')
        ->havingRaw('dist < ?', [$distance])
        ->get();


        foreach($storeList as $storeItem) {

            $store = new View_store($storeItem->toArray());

            $storeProduct = $storeItem->products->where('id', $product->id)->first();

            // If store directly associated to product
            if($storeProduct && $storeProduct->pivot) {
                $store->pivot = array('price' => $storeProduct->pivot->price);
            } else {
                // If store associated through a chain
                $chainProduct = $product->chains->where('id', $store->chain_id)->first();
                $store->pivot = array('price' => $chainProduct->pivot->price);
            }

            $store->id = rand(1000, 2000);

            $closeStoreList->push($store);

        }

        $product->view_stores = $closeStoreList;


        $productTags = array_column($product->tags->toArray(), 'name');
        $associatedTags = Tag::with('products')->whereIn('name', $productTags)->take(5)->get();
        $associatedProducts = array();
        foreach($associatedTags as $tag) {
            foreach($tag->products as $prod) {
                if($prod->id != $product->id) {
                    array_push($associatedProducts, new ProductResource($prod));
                }
            }
        }

        // FIX STore location??
        foreach($product->stores as $store) {
            $store->text_location = View_store::find($store->id)->text_location;
            $store->text_location = str_replace('POINT','', $store->text_location);
            $store->text_location = str_replace('(', '', $store->text_location);
            $store->text_location = str_replace(')', '', $store->text_location);
        }

        $product->similarProducts = $associatedProducts;

        return new ProductResource($product);

    }

    public function getDicofre($coords)
    {
        $coordArray = explode(",", $coords);

        $data["dicofre"] = Caop::select(
            DB::raw('dicofre','distrito'))
        ->whereRaw( 'ST_Contains(SHAPE, ST_PointFromText("POINT(' . $coordArray[0] . ' ' . $coordArray[1] . ')"))' )
        ->firstOrFail();

        $data["distritos"] = Caop::select(
            DB::raw("SUBSTRING(dicofre, 1, 2) AS dicofre_calc"),'distrito')
            ->groupBy(DB::raw('SUBSTRING(dicofre, 1, 2)'))
            ->orderBy('distrito', 'asc')
            ->pluck('distrito', 'dicofre_calc')
            ->toArray();

        $data["concelhos"] = Caop::select(
            DB::raw("SUBSTRING(dicofre, 1, 4) AS dicofre_calc"),'concelho')
            ->whereRaw('SUBSTRING(dicofre, 1, 2) = ' . substr($data["dicofre"]->dicofre,0,2))
            ->groupBy(DB::raw('SUBSTRING(dicofre, 1, 4)'))
            ->orderBy('concelho', 'asc')
            ->pluck('concelho', 'dicofre_calc')
            ->toArray();

        $data["freguesias"] = Caop::orderBy('freguesia', 'asc')
            ->whereRaw('SUBSTRING(dicofre, 1, 4) = ' . substr($data["dicofre"]->dicofre,0,4))
            ->groupBy('freguesia')
            ->pluck('freguesia', 'dicofre')
            ->toArray();

        return $data;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeguest(Request $request)
    {

        $request->newUser = json_decode($request->newUser, true);

        if($request->newUser["name"] != "" && $request->newUser["email"]) {
            $newUser = new User;
            $newUser->name = $request->newUser["name"];
            $newUser->email = $request->newUser["email"];
            $newUser->password = $this->rand_str(15);
            $newUser->save();
            $request->username = $newUser->name;
        } else {
            // Activate not to allow annonymous records
            // return response()->json([
            //     'error' => true,
            //     'message' => 'Dados de utilizador incompletos.',
            // ]);
        }

        return $this->store($request);

    }

    private function rand_str($len) {
        $characters = '0123456789-=+{}[]:;@#~.?/&gt;,&lt;|\!"£$%^&amp;*()abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomstr = '';
        for ($i = 0; $i < $len; $i++) {
          $randomstr .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomstr;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $response = [[
            'error' => false,
            'message' => 'Produto Registado.'
        ]];

        DB::beginTransaction();

        try {

            $data = $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3072'

            ]);

            $request->product = json_decode($request->product, true);

            $req = new Request($request->product);
            $userId = $request->user() != null ? $request->user()->id : ($request->username != null && $request->username != "" ? User::where('name', $request->username)->first()->id : 0);

            $data = $this->validate($req, [
                'name' => 'required',
                'category' => 'required',
                'stores' => 'required'

            ]);

            $product = new Product($request->product); // Get base product model
            $product->user_id = $userId;
            // Brand
            if(isset($request->product["brand"]["name"]) && $request->product["brand"]["name"] != "") {
                $brand = Brand::where('name', $request->product["brand"]["name"])->first();
                if(!$brand){
                    $brand = new Brand;
                    $brand->name = $request->product["brand"]["name"];
                    $brand->save();
                }
                $product->brand_id = $brand->id;
            }

            // Category
            $product->category_id = $request->product["category"];

            // Images
            // Handle Photo Upload
            $fileNameToStore = "";
            if($request->hasFile('image')) {

                // Get filename with the extension
                $fileNameWithExt = $request->file('image')->getClientOriginalName();;
                // Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get just extension
                $fileExtension = $request->file('image')->getClientOriginalExtension();
                // FileName to store
                $fileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
                // Upload Image
                //$path = $request->file('image')->storeAs('public/product_images', $fileNameToStore);
                if(!Storage::disk('publicProductImages')->put('storage/product_images/' . $fileNameToStore, file_get_contents($request->file('image')))) {
                    return false;
                }

                // $path = $request->file('image')->storeAs('public_html/storage/product_images', $fileNameToStore);
            }

            // Handle extra photos uploads
            $files = $request->file('extra');
            $extraFiles = array();
            if($request->hasFile('extra'))
            {
                foreach ($files as $file) {
                    if($file!=null) {
                        // Get filename with the extension
                        $fileNameWithExt = $file->getClientOriginalName();;
                        // Get just filename
                        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                        // Get just extension
                        $fileExtension = $file->getClientOriginalExtension();
                        // FileName to store
                        $extraFileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
                        // Upload Image
                        $path = $file->storePubliclyAs('/public/product_images', $extraFileNameToStore);

                        // Add to file list
                        array_push($extraFiles, $extraFileNameToStore);
                    }
                }
            }

            $product->save();

            // Save image
            $image = new Image;
            $image->product_id = $product->id;
            $image->path = $fileNameToStore;
            $image->type_id = 1; // Mark as main product image
            $image->save();

            // Save extra images
            if(count($extraFiles) > 0) {
                foreach($extraFiles as $photo) {
                    $image = new Image;
                    $image->product_id = $product->id;
                    $image->path = $photo;
                    $image->type_id = 2; // Mark as extra product image
                    $image->save();
                }
            }

            // Add stores
            $storeList = explode(";", $request->product["stores"]);
            foreach($storeList as $storeInfo) {
                $storeParts = explode("||", $storeInfo);
                $store = Store::where('name', $storeParts[0])->first();
                $price = isset($storeParts[1]) ? $storeParts[1] : 0;

                // Check for Chain
                $chain = Chain::find($store->chain_id);

                if($chain != null) {
                    $product->chains()->attach($chain, [ 'price'=> $price, 'status_id' => 2, 'user' => $userId ]);
                } else {
                    $product->stores()->attach($store, [ 'price'=> $price, 'status_id' => 2, 'user' => $userId ]);
                }
            }

            // Create Tags
            $newTags = explode(";", $request->product["tags"]);

            foreach($newTags as $newTag) {
                $product->tags()->attach(Tag::where('name', $newTag)->take(1)->get());
            }

            // Add Review
            if($request->product["comment"] != "" || $request->product["rating"]) {
                $review = new Review;
                $review->product_id = $product->id;
                $review->status_id = 2;
                $review->score = $request->product["rating"];
                $review->comment = $request->product["comment"];
                $review->user_id = $userId;

                $product->reviews()->save($review);
            }

            $product->save();

            // Log success history
            $log = new Product_history($product->toArray());
            $log->track_type = "INSERT";
            $log->track_user_id = $userId;
            $log->save();

            DB::commit();

            $response["product"] = new ProductResource($product);
            $response["error"] = false;

        } catch (\Exception $e) {
            DB::rollback();
            $response["error"] = true;
            $response["message"] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchReview(Request $request, $product)
    {
        $userReview = null;
        // Get user
        if($request->user()!=null) {
            $user = User::with(["reviews"])->find($request->user()->id);

            $userReview = $user->reviews()->where('product_id', $product)->first();
        }

        return response()->json([
            'error' => false,
            'message' => 'Comentários obtidos com sucesso.',
            'product' => $userReview == null ? null : new ReviewResource($userReview)
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchReviewName(Request $request)
    {
        $userReview = null;
        // Get user
        if($request->user()!=null) {
            $user = User::with(["reviews"])->find($request->user()->id);
            $product = Product::where('name', $request->productName)->first();
            $userReview = $user->reviews()->where('product_id', $product->id)->first();
        }

        return response()->json([
            'error' => false,
            'message' => 'Comentários obtidos com sucesso.',
            'product' => $userReview == null ? null : new ReviewResource($userReview)
        ]);

    }

    public function review(Request $request) {

        $product = Product::findOrFail($request->productId);

        $userReviews = $product->reviews()->where('user_id', $request->user()->id)->get();

        if(count($userReviews)) {
            if($request->user()) {
                $review = $userReviews->first();
                $review->product_id = $request->productId;
                $review->score = $request->rating;
                $review->comment = $request->comment;
                if(!$review->save()) {
                    abort(404); // Failed operation
                }
            } else {
                abort(403); // Duplicate record
            }
        } else {

            $review = new Review;
            $review->product_id = $request->productId;
            $review->status_id = 2;
            $review->score = $request->rating;
            $review->comment = $request->comment;
            $review->user_id = $request->user()->id;

            if(!$product->reviews()->save($review)) {
                abort(404); // Failed operation
            }

        }


        return new ReviewResource($review);
    }

    public function report(Request $request) {

        $response = [
            'error' => false,
            'message' => 'Produto reportado. A administração irá rever a situação. Obrigado!',
            'report' => null
        ];
        $userId = $request->user() != null ? $request->user()->id : ($request->username != null && $request->username != "" ? User::where('name', $request->username)->first()->id : 0);
        $product = Product::findOrFail($request->product);
        $previousReport = $userId != 0 ? Product_report::where('user_id', $request->user()->id)->where('product_id', $product->id)->where('status_id', 0)->first() : null;

        if($previousReport!=null) {

            $response['error'] = true;
            $response['message'] = 'Produto já reportado. A administração irá rever a situação. Obrigado';

        } else {

            $message = isset($request->message) ? $request->message : '';
            $report = new Product_report;
            $report->obs = $request->message;
            $report->report_type_id = $request->report_type;
            $report->product_id = $product->id;
            $report->user_id = $userId;

            try {

                if(!$product->reports()->save($report)) {
                    $response['error'] = true;
                    $response['message'] = 'Erro ao reportar produto';
                }
            }
            catch(Exception $e) {
                $response['error'] = true;
                $response['message'] = 'Erro ao reportar produto';
            }

        }


        return response()->json($response);

    }

    public function updatePrice(Request $request) {

        $response = [
            'error' => false,
            'message' => 'Preço atualizado.',
            'product' => null
        ];
        $product = Product::findOrFail($request->product);
        $store = Store::findOrFail($request->store);

        if($product==null || $store==null) {

            $response['error'] = true;
            $response['message'] = 'Produto já reportado. A administração irá rever a situação. Obrigado';

        } else {

            try {
                $product->stores()->attach($store, [ 'price'=> $request->newPrice, 'user'=> $request->user()!=null ? $request->user()->id : 0 ]);
                $product->save();

                $response["product"] = new ProductResource($product);
            }
            catch(Exception $e) {
                $response['error'] = true;
                $response['message'] = 'Erro ao atualizar preço';
            }

        }


        return response()->json($response);

    }

    public function fetchname(Request $request) {
        return
        $productList = DB::table('products')
            ->where('status_id', 2)
            ->where('name', 'LIKE', '%' . $request->name . '%')
            ->orderBy('name', 'ASC')
            ->take(10)
            ->get();

        return new ProductCollectionResource($productList);

    }

    public function getReportTypes(Request $request) {

        $reportTypeList = Report_type::all();

        return response()->json([
            'error' => false,
            'message' => 'Tipos de denúncia obtidos.',
            'reportTypes' => new Report_typeCollectionResource($reportTypeList)
        ]);

    }

}
