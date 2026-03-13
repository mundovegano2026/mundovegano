<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Category;
use App\Product;
use App\Category_image;
use App\Categorytree;
use DB;

class CategoriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth', ["except" => ["index", "show"]]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Categorias"]; 
        $data["search"] = "";
        
        // New product query
        $queryCategory = Category::query();

        //Add sorting
        $queryCategory->orderBy('name', 'asc')
        ->where('categories.parent_id', 0);

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryCategory->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["categories"] = $queryCategory->paginate(10);

        //Category to be created
        $data["category"] = new Category;


        return view('admin.categories.index')->with('data', $data);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     return view('admin.categories.create');
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data["id"] = $id;
        $data = array();
        $data["breadcrumbs"] = ["Início", "Categorias"];
        $data["search"] = '';

        // New category query
        $queryCategory = Category::query();

        //Add sorting
        $queryCategory->orderBy('name', 'asc')
        ->where('categories.parent_id', $id);

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryCategory->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["categories"] = $queryCategory->paginate(10);


        $data["original_category"] = Category::find($id);

        return view('admin.categories.index')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'level' => 'required'
        ]);

        $returnLink = '/admin/categories';

        $category = new Category;
        $category->name = $request->input('name');
        $category->level = $request->input('level');
        $category->path = "";
        $category->parent_id = $request->input('parent_id');

        if($category->parent_id != 0) {
            $returnLink .= "/" . $category->parent_id;
            $parentCategory = Category::find($category->parent_id);
            $category->path = $parentCategory->path == "" ? 
                                $parentCategory->name :
                                $parentCategory->path . "/" . $parentCategory->name;
        } 


        // Images
        // Handle Photo Upload
        $fileNameToStore = "";
        if($request->hasFile('new_main_image')) {
            // Get filename with the extension
            $fileNameWithExt = $request->file('new_main_image')->getClientOriginalName();;
            // Get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $fileExtension = $request->file('new_main_image')->getClientOriginalExtension();
            // FileName to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
            // Upload Image
            $path = $request->file('new_main_image')->storeAs('public/category_images', $fileNameToStore);
        } 
  
        $category->save();    

        // Handle category associations
        $categoryTreeEntry = new Categorytree;
        $categoryTreeEntry->category_parent = $category->id;
        $categoryTreeEntry->category_child = $category->id;
        $categoryTreeEntry->save();
        $this->buildTree($category->id, $category);
        
        // Save image
        $image = new Category_image;
        $image->category_id = $category->id;
        $image->path = $fileNameToStore;
        $image->type_id = 1; // Mark as main product image
        $image->save();

        return redirect($returnLink)->with('success', 'Categoria Criada');
    }

    public function buildTree($categoryId, $childCategory) {

        if($childCategory->parent_id) {
            $categoryTreeEntry = new Categorytree;
            $categoryTreeEntry->category_parent = $childCategory->parent_id;
            $categoryTreeEntry->category_child = $childCategory->id;
            $categoryTreeEntry->save();
            $this->buildTree($categoryId, Category::find($childCategory->parent_id));
            
        } 

        return true;

    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        
        $category = Category::find($id);
        $category->active = 1;
        $category->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        $returnLink = 'admin/categories';
        if($category->parent_id != 0) {
            $returnLink .= "/" . $category->parent_id;
        } 

        return redirect($returnLink);
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        
        $category = Category::find($id);
        $category->active = 0;
        $category->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }
        $returnLink = 'admin/categories';
        if($category->parent_id != 0) {
            $returnLink .= "/" . $category->parent_id;
        } 

        return redirect($returnLink);
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
        $this->validate($request, [
            'name' => 'required'
        ]);

        $category = Category::find($id);
        $category->name = $request->input('name');
            
        // Images
        // Handle Photo Upload
        $fileNameToStore = "";
        if($request->hasFile('main_image')) {
            
            $main_image = $category->images->where('type_id', 1)->first();
            // Delete previous main image (ACTIVATE TO DELETE UNUSED IMAGES FROM STORAGE)
            if($main_image!=null) {
                Storage::delete('product_images/' . $main_image->path);
    
                $main_image->delete();
            }

            // Get filename with the extension
            $fileNameWithExt = $request->file('main_image')->getClientOriginalName();
            // Get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $fileExtension = $request->file('main_image')->getClientOriginalExtension();
            // FileName to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $fileExtension;
            // Upload Image
            $path = $request->file('main_image')->storeAs('public/category_images', $fileNameToStore);
        } 


        $category->save();

            if($request->hasFile('main_image')) {
            // Save image
            $image = new Category_image;
            $image->category_id = $category->id;
            $image->path = $fileNameToStore;
            $image->type_id = 1; // Mark as main category image
            $image->save();

        }

        return redirect('/admin/categories/' . $category->id)->with('success', 'Categoria atualizada.');
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

        $result = ["type" => "success", "message" => "Categoria eliminada."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $category = Category::find($request->id);

            $productList = Product::where('category_id', $request->id);

            foreach($productList as $product) {

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

            }

            $subCatList = DB::table('categorytrees')
                ->where('category_parent', $category->id)
                ->where('category_child', '!=', $category->id)->get();

            foreach($subCatList as $subCat) {

                $subCategory = Category::find($subCat->category_child);

                $subProductList = Product::where('category_id', $subCategory->id);
    
                foreach($subProductList as $subProduct) {
    
                    // Delete Tags
                    DB::table('product_tags')->where('product_id', $subProduct->id)->delete();
    
                    // Delete Reviews
                    DB::table('reviews')->where('product_id', $subProduct->id)->delete();
    
                    // Delete Reports
                    DB::table('product_reports')->where('product_id', $subProduct->id)->delete();
    
                    // Delete Stores
                    DB::table('product_stores')->where('product_id', $subProduct->id)->delete();
    
                    // Delete History
                    DB::table('product_history')->where('product_id', $subProduct->id)->delete();
    
                    // Delete Images
                    DB::table('images')->where('product_id', $subProduct->id)->delete();  
    
                    $subProduct->delete();
    
                }
    
                $subSubCatList = DB::table('categorytrees')
                ->where('category_parent', $subCategory->id)
                ->where('category_child', '!=', $subCategory->id)->get();

                foreach($subSubCatList as $subSubCat) {

                    $subSubCategory = Category::find($subSubCat->category_child);
    
                    $subSubProductList = Product::where('category_id', $subSubCat->category_child);
        
                    foreach($subSubProductList as $subSubproduct) {
        
                        // Delete Tags
                        DB::table('product_tags')->where('product_id', $subSubproduct->id)->delete();
        
                        // Delete Reviews
                        DB::table('reviews')->where('product_id', $subSubproduct->id)->delete();
        
                        // Delete Reports
                        DB::table('product_reports')->where('product_id', $subSubproduct->id)->delete();
        
                        // Delete Stores
                        DB::table('product_stores')->where('product_id', $subSubproduct->id)->delete();
        
                        // Delete History
                        DB::table('product_history')->where('product_id', $subSubproduct->id)->delete();
        
                        // Delete Images
                        DB::table('images')->where('product_id', $subSubproduct->id)->delete();  
        
                        $subSubproduct->delete();
        
                    }
        
                    $subSubCategory->delete();   
                    // $subSubCat->delete(); 
                    DB::table('categorytrees')->where('category_parent', $subCategory->id)->delete(); 
    
                }

                $subCategory->delete();  
                DB::table('categorytrees')->where('category_parent', $category->id)->delete(); 
                // $subCat->delete();  

            }

            $category->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["error"] = $e->getMessage();
            $result["message"] = "Erro ao eliminar categoria.";
        }

        $returnLink = '/admin/categories/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/categories';
// return $result;
        return redirect($returnLink)->with($result["type"], $result["message"]);
    }


    public function fetch(Request $request) {
        
        if($request->get('id'))
        {
            $query = $request->get('query');
            $data = DB::table('categories')
                ->where('parent_id', $request->get('id'))
                ->get();

            if(count($data) > 0) {
                $output = '<select name="category" class="form-control category_select" required="required">
                            <option value="">Selecione</option>';
                foreach($data as $row)
                {
                    $output .= ' <option value="'.$row->id.'">'.$row->name.'</option>';
                }
                $output .= '</select>';
                echo $output;
            } else {
                echo '';
            }
            

        }

    }
    
}