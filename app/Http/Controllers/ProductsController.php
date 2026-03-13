<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Product_history;
use App\Brand;
use App\Store;
use App\Chain;
use App\Category;
use App\Image;
use App\Status;
use App\Capacity_unit;
use App\Tag;
use DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = array();
        $data["breadcrumbs"] = ["Início", "Produtos"];
        $data["search"] = "";
        $data["prod_id"] = "";
        // New product query
        $queryProduct = Product::query();

        //Add sorting
        $queryProduct->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryProduct->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }
        if(request()->has('prod_id') && request('prod_id') != '') {
            $queryProduct->where('id', trim(request('prod_id')));
            $data["prod_id"] = request('prod_id');
        }

        //Fetch list of results
        $data["products"] = $queryProduct->paginate(10);

        return view('admin.products.index')->with('data', $data);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth', ["except" => ["index", "show"]]);
    // }

    public function search() {

        $data = array();
        $data["breadcrumbs"] = ["Início", "Produtos"];
        $data["search"] = "";

        // New product query
        $queryProduct = Product::query();

        //Add sorting
        $queryProduct->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryProduct->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["products"] = $queryProduct->paginate(10);

        return view('admin.products.index')->with('data', $data)->withQuery(request('search'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Produtos"];
        $data["products"] = Product::where('status_id', 1)->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.validation.products')->with('data', $data);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_product';

        if($newRecord) {
            $modalName = 'admin.inc.modal_new_product';
            $data["product"] = new Product;
            $data["db_main_image"] = "";
        } else {
            $data["product"] = Product::find($id);
            if(isset($data["product"]->category)) {
                $data["product_categories"] = $this->getCategoryList($data["product"]->category->id);
            } else {
                $data["product_categories"] = $this->getCategoryList(1);
            }
            $data["db_main_image"] = $data["product"]->images->where('type_id', 1)->first();
        }

        // Fetch first level categories
        $data["categories"] = Category::where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        // STORES
        $data["stores"] = "";
        $data["uniqueStoreIds"] = [];
        $data["uniqueStores"] = [];
        // foreach($data["product"]->stores as $store) {
        for($i = count($data["product"]->stores)-1; $i >= 0; $i--) {

            $prod_store = $data["product"]->stores[$i];

            if(!in_array($prod_store->id, $data["uniqueStoreIds"])) {
                array_push($data["uniqueStoreIds"], $prod_store->id);
                array_push($data["uniqueStores"], $prod_store);

                if($data["stores"] != "") $data["stores"] .= ";";
                $data["stores"] .= $prod_store->name;
            }
        }

        // CHAINS
        $data["chains"] = "";
        $data["uniqueChainIds"] = [];
        $data["uniqueChains"] = [];

        for($i = count($data["product"]->chains)-1; $i >= 0; $i--) {

            $prod_chain = $data["product"]->chains[$i];

            if(!in_array($prod_chain->id, $data["uniqueChainIds"])) {
                array_push($data["uniqueChainIds"], $prod_chain->id);
                array_push($data["uniqueChains"], $prod_chain);

                if($data["chains"] != "") $data["chains"] .= ";";
                $data["chains"] .= $prod_chain->name;
            }
        }

        $data["statuses"] = Status::all();
        $data["status"] = $data["product"]->status;

        $data["capacity_unit_list"] = Capacity_unit::orderBy('name', 'asc')->get();

        $data["tags"] = Tag::all()->pluck('name');

        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_product';

        $data["product"] = Product::find($id);

        return view($modalName)->with('data', $data);
    }

    private function getCategoryList($categoryId) {

        $categoryList = array();
        $category = Category::find($categoryId);
        $firstCategoryList = Category::where('parent_id', $category->parent_id)
                            ->orderBy('name', 'asc')
                            ->get();
        array_unshift($categoryList, array("selected" => $category, "list" => $firstCategoryList));
        $max_attempts = 20;
        $parent_id = $category->parent_id;
        while($parent_id!=0 && $max_attempts>0) {
            $max_attempts--;
            $newCategory = Category::find($parent_id);
            $newCategoryList = Category::where('parent_id', $newCategory->parent_id)
                            ->orderBy('name', 'asc')
                            ->get();
            array_unshift($categoryList, array("selected" => $newCategory, "list" => $newCategoryList));
            $parent_id = $newCategory->parent_id;
        }

        return $categoryList;


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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Produtos"];
        $data["products"] = Product::orderBy('id', 'desc')
            ->paginate(10);
        $data["id"] = $id;
        return view('admin.products.index')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $result = ["type" => "success", "message" => "Produto criado"];

        $this->validate($request, [
            'name' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $product = new Product;
            $product->name = $request->input('name'); // Product Name
            $product->obs = $request->input('obs'); // Product extra info
            $product->admin_id = $request->user()->id; // Admin ID
            $product->status_id = 2; // Automatically Validate Product

            // Capacity
            if($request->input('capacity') != '') {
                $product->capacity = $request->input('capacity');
                $product->capacity_unit_id = Capacity_unit::where('symbol', $request->input('capacity_unit'))->first()->id;
            }

            // Brand
            $brand = Brand::where('name', $request->input('brand'))->first();
            if($brand !== null) {
                $product->brand_id = $brand->id;
            }
            $product->category_id = $request->input('category');

            // Images
            // Handle Photo Upload
            $fileNameToStore = "";
            if($request->hasFile('main_image')) {
                // Get filename with the extension
                $fileNameWithExt = $request->file('main_image')->getClientOriginalName();;
                // Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get just extension
                $fileExtension = $request->file('main_image')->getClientOriginalExtension();
                // FileName to store
                $fileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
                // Upload Image
                if(!Storage::disk('publicProductImages')->put('storage/product_images/' . $fileNameToStore, file_get_contents($request->file('main_image')))) {
                    return false;
                }
            }

            // Handle extra photos uploads
            $files = $request->file('extra_photos');
            $extraFiles = array();
            $extraFileNameToStore = "";
            if(is_array($files) && count($files) > 0) {
                foreach ($files as $file) {
                    // Get filename with the extension
                    $fileNameWithExt = $file->getClientOriginalName();;
                    // Get just filename
                    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    // Get just extension
                    $fileExtension = $file->getClientOriginalExtension();
                    // FileName to store
                    $extraFileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
                    // Upload Image
                    // $path = $file->storeAs('public/product_images', $extraFileNameToStore);
                    if(!Storage::disk('publicProductImages')->put('storage/product_images/' . $extraFileNameToStore, file_get_contents($file))) {
                        return false;
                    }
                    // Add to file list
                    array_push($extraFiles, $extraFileNameToStore);
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
            $storeList = explode(";", $request->input("stores"));
            foreach($storeList as $storeInfo) {
                $storeParts = explode("||", $storeInfo);
                $store = Store::where('name', $storeParts[0])->take(1)->get();
                $price = isset($storeParts[1]) ? $storeParts[1] : 0;
                $product->stores()->attach($store, [ 'price'=> $price ]);
            }

            // Add chains
            $chainList = explode(";", $request->input("chains"));
            foreach($chainList as $chainInfo) {
                $chainParts = explode("||", $chainInfo);
                $chain = Chain::where('name', $chainParts[0])->take(1)->get();
                $price = isset($chainParts[1]) ? $chainParts[1] : 0;
                $product->chains()->attach($chain, [ 'price'=> $price ]);
            }

            // Create Tags
            $newTags = explode(";", $request->input('tags'));

            foreach($newTags as $newTag) {
                $product->tags()->attach(Tag::where('name', $newTag)->take(1)->get());
            }

            $product->save();

            // Log success history
            $log = new Product_history($product->toArray());
            $log->track_type = "INSERT";
            $log->track_user_id = $request->user()->id;
            $log->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["message"] = "Erro ao criar produto.";
        }

        return redirect('/admin/products')->with($result["type"], $result["message"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $result = ["type" => "success", "message" => "Produto atualizado."];

        $this->validate($request, [
            'name' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $product = Product::with('stores', 'stores.chain')->find($id);

            $product->name = $request->input('name'); // Product Name
            $product->obs = $request->input('obs'); // Product extra info
            $product->status_id = $request->input('status'); // Product status
            // $product->tags = $request->input('tags'); // Product tags

            // Capacity
            if($request->input('capacity') != '') {
                $product->capacity = $request->input('capacity');
                $product->capacity_unit_id = Capacity_unit::where('symbol', $request->input('capacity_unit'))->first()->id;
            }

            // Brand
            $brand = Brand::where('name', $request->input('brand'))->first();
            if($brand !== null) {
                $product->brand_id = $brand->id;
            } else {
                $product->brand_id = 0;
            }
            $product->category_id = $request->input('category');


            // Images
            // Handle Photo Upload
            $fileNameToStore = "";
            if($request->hasFile('main_image')) {

                $main_image = $product->images->where('type_id', 1)->first();
                // Delete previous main image (ACTIVATE TO DELETE UNUSED IMAGES FROM STORAGE)
                // if($main_image!=null) {
                //     Storage::delete('product_images/' . $main_image->path);

                //     $main_image->delete();
                // }

                // Get filename with the extension
                $fileNameWithExt = $request->file('main_image')->getClientOriginalName();;
                // Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get just extension
                $fileExtension = $request->file('main_image')->getClientOriginalExtension();
                // FileName to store
                $fileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
                // Upload Image
                // $path = $request->file('main_image')->storeAs('public/product_images', $fileNameToStore);
                if(!Storage::disk('publicProductImages')->put('storage/product_images/' . $fileNameToStore, file_get_contents($request->file('main_image')))) {
                    return false;
                }
            }

            // Handle extra photos uploads
            $files = $request->file('extra_photos');
            $extraFiles = array();
            $extraFileNameToStore = "";
            if(is_array($files) && count($files) > 0) {
                foreach ($files as $file) {
                    // Get filename with the extension
                    $fileNameWithExt = $file->getClientOriginalName();;
                    // Get just filename
                    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    // Get just extension
                    $fileExtension = $file->getClientOriginalExtension();
                    // FileName to store
                    $extraFileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
                    // Upload Image
                    // $path = $file->storeAs('public/product_images', $extraFileNameToStore);
                    if(!Storage::disk('publicProductImages')->put('storage/product_images/' . $extraFileNameToStore, file_get_contents($file))) {
                        return false;
                    }
                    // Add to file list
                    array_push($extraFiles, $extraFileNameToStore);
                }
            }


            $product->save();

            // Save image
            if($request->hasFile('main_image')) {
                $oldMainImage = DB::table('images')->where('product_id', $product->id)->where('type_id', 1)->delete();
                $image = new Image;
                $image->product_id = $product->id;
                $image->path = $fileNameToStore;
                $image->type_id = 1; // Mark as main product image
                $image->save();
            }

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

            // Delete Extra Images (ACTIVATE TO DELETE UNUSED IMAGES FROM STORAGE)
            $imageIdListToDelete = explode(";", $request->input('deleted_images'));
            foreach($imageIdListToDelete as $imageId) {
                $imageToDelete = Image::find($imageId);
                if($imageToDelete!=null) $imageToDelete->delete();
            }

            // Chains
            $chainList = explode(";", $request->input("chains"));
            $chainSimpleList = array();

            // Delete removed chains
            foreach($product->chains as $chain) {
                if(!in_array($chain->name, $chainList)) {
                    $product->chains()->detach($chain);
                }
            }

            foreach($chainList as $chainInfo) {
                $chainParts = explode("||", $chainInfo);
                array_push($chainSimpleList, $chainParts[0]);
                // If chain didn't exist previously, add it to product
                if($product->chains->where('name', $chainParts[0])->first() == null) {
                    $chain = Chain::where('name', $chainParts[0])->take(1)->get();
                    $price = isset($chainParts[1]) ? $chainParts[1] : 0;
                    $product->chains()->attach($chain, [ 'price'=> $price ]);
                }
            }

            // Stores
            $storeList = explode(";", $request->input("stores"));

            // Delete removed stores
            foreach($product->stores as $store) {
                // If store being removed, remove from db
                if(!in_array($store->name, $storeList)) {
                    $product->stores()->detach($store);
                }

                // If store included in a chain, remove from db
                if($store->chain != null && in_array($store->chain->name, $chainSimpleList)) {
                    $product->stores()->detach($store);
                }
            }
            foreach($storeList as $storeInfo) {
                $storeParts = explode("||", $storeInfo);
                // If store didn't exist previously, add it to product
                if($product->stores->where('name', $storeParts[0])->first() == null) {
                    $store = Store::where('name', $storeParts[0])->take(1)->get();
                    $price = isset($storeParts[1]) ? $storeParts[1] : 0;
                    $product->stores()->attach($store, [ 'price'=> $price ]);
                }
            }

            // Update Tags
            $currentTagNameList = array_column($product->tags->toArray(), 'name');
            $currentTags = $product->tags;
            $newTags = explode(";", $request->input('tags'));
            foreach($currentTagNameList as $tag) {
                if(!in_array($tag, $newTags)) {
                    $currentTags->where('name', $tag)->first()->delete();
                }
            }
            foreach($newTags as $newTag) {
                if(!in_array($newTag, $currentTagNameList)) {
                    $product->tags()->attach(Tag::where('name', $newTag)->take(1)->get());
                }
            }

            $product->save();

            // Log success history
            $log = new Product_history($product->toArray());
            $log->product_id = $product->id;
            $log->track_type = "UPDATE";
            $log->track_user_id = $request->user()->id;
            $log->track_changes = json_encode($product->getChanges());
            $log->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["message"] = "Erro ao atualizar produto.";
            echo $e;
            return;
        }


        $returnLink = '/admin/products/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/products';

        return redirect($returnLink)->with($result["type"], $result["message"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {

        $result = ["type" => "success", "message" => "Produto eliminado."];

        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $product = Product::find($request->id);

            // Delete Tags
            DB::table('product_tags')->where('product_id', $product->id)->delete();

            // Delete Reviews
            DB::table('reviews')->where('product_id', $product->id)->delete();

            // Delete Reports
            DB::table('product_reports')->where('product_id', $product->id)->delete();

            // Delete Stores
            DB::table('product_stores')->where('product_id', $product->id)->delete();

            // Delete History
            DB::table('product_history')->where('product_id', $product->id)->delete();

            // Delete Images
            DB::table('images')->where('product_id', $product->id)->delete();

            $product->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["message"] = "Erro ao eliminar produto.";
        }


        $returnLink = '/admin/products/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/products';

        return redirect($returnLink)->with($result["type"], $result["message"]);
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {

        $result = [
            'error' => false,
            'message' => 'Produto atualizado.'
        ];

        DB::beginTransaction();

        try {

            $product = Product::find($request->id);
            $product->active = 1;
            $product->save();
            // Check for correct user
            // if(auth()->user()->id != $category->user_id) {
            //     return redirect('posts')->with('error', 'Unauthorized page');
            // }

            // Log success history
            $log = new Product_history($product->toArray());
            $log->product_id = $product->id;
            $log->track_type = "ACTIVATE";
            $log->track_user_id = $request->user()->id;
            $log->track_changes = json_encode($product->getChanges());
            $log->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["error"] = true;
            $result["message"] = "Erro ao atualizar produto.";
        }

        return response()->json($result);
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate(Request $request)
    {

        $result = [
            'error' => false,
            'message' => 'Produto atualizado.'
        ];

        DB::beginTransaction();

        try {

            $product = Product::find($request->id);
            $product->active = 0;
            $product->save();
            // Check for correct user
            // if(auth()->user()->id != $category->user_id) {
            //     return redirect('posts')->with('error', 'Unauthorized page');
            // }

            // Log success history
            $log = new Product_history($product->toArray());
            $log->product_id = $product->id;
            $log->track_type = "INACTIVATE";
            $log->track_user_id = $request->user()->id;
            $log->track_changes = json_encode($product->getChanges());
            $log->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["error"] = true;
            $result["message"] = "Erro ao atualizar produto.";

        }

        return response()->json($result);
    }


    public function validateName(Request $request) {

        if($request->get('name'))
        {
            $data = Product::where('name', $request->get('name'));

            if($request->get('brand')) {
                $brand = Brand::where('name', $request->get('brand'))->first();
                $data = $data->where('brand_id', $brand->id);
            }
            $data = $data->take(1)
                        ->get();

            echo (count($data) >= 1);

        }

        echo false;

    }
}
