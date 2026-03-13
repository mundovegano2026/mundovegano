<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\Product;
use App\Status;
use DB;

class BrandsController extends Controller
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
        $data["breadcrumbs"] = ["Início", "Marcas"];
        $data["search"] = "";

        // New product query
        $queryBrand = Brand::query();

        //Add sorting
        $queryBrand->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryBrand->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["brands"] = $queryBrand->paginate(10);

        return view('admin.brands.index')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Marcas"];
        $data["brands"] = Brand::where('status_id', 1)->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.validation.brands')->with('data', $data);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_brand';
        $data["status"] = Status::find(2);
        
        if($newRecord) {
            $modalName = 'admin.inc.modal_new_brand';
            $data["brand"] = new Brand;
        } else {
            $data["brand"] = Brand::find($id);
            $data["status"] = $data["brand"]->status;
        }

        $data["statuses"] = Status::all();
    
        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_brand';
        
        $data["brand"] = Brand::find($id);    

        return view($modalName)->with('data', $data);
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
        $data["breadcrumbs"] = ["Início", "Marcas"];
        $data["brands"] = Brand::orderBy('id', 'desc')
            ->paginate(10);
        $data["id"] = $id;
        return view('admin.brands.index')->with('data', $data);
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
            'name' => 'required'
        ]);

        $brand = new Brand;
        $brand->name = $request->input('name');
        $brand->status_id = $request->input('status');

        $brand->save();        

        return redirect('/admin/brands')->with('success', 'Marca Criada');
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

        $brand = Brand::find($id);
        $brand->name = $request->input('name');
        $brand->status_id = $request->input('status'); 

        $brand->save();

        $returnLink = '/admin/brands/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/brands';

        return redirect($returnLink)->with('success', 'Marca atualizada.');
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

        $result = ["type" => "success", "message" => "Marca eliminada."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $brand = Brand::find($request->id);

            $productList = Product::where('brand_id', $request->id)->get();

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

            $brand->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["message"] = "Erro ao eliminar marca.";
        }


        $returnLink = '/admin/brands/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/brands';

        return redirect($returnLink)->with($result["type"], $result["message"]);
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {        
        $brand = Brand::find($id);
        $brand->active = 1;
        $brand->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/brands');
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        $brand = Brand::find($id);
        $brand->active = 0;
        $brand->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/brands');
    }

    public function fetch(Request $request) {
        
        try {

            if($request->get('query'))
            {
                $query = $request->get('query');
                $data = DB::table('brands')
                ->where('name', 'LIKE', "%{$query}%")
                ->take(10)
                ->get();

                if(!count($data)) return "";

                $output = '<ul class="dropdown-menu" style="display:block; position:relative">';

                foreach($data as $row)
                {
                $output .= '
                <li class="suggestion"><a href="#">'.$row->name.'</a></li>
                ';
                }
                $output .= '</ul>';
                
                echo $output;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        
    }
    
    public function validateName(Request $request) {
        
        if($request->get('name'))
        {
            $data = Brand::where('name', $request->get('name'))
                ->take(1)
                ->get();

            echo (count($data) >= 1);
       
        }
        echo false;
    }

}