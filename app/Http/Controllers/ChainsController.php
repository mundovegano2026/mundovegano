<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chain;
use App\Product;
use App\Store;
use DB;

class ChainsController extends Controller
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
        $data["breadcrumbs"] = ["Início", "Cadeias de Lojas"];
        $data["search"] = "";
        
        // New product query
        $queryChain = Chain::query();

        //Add sorting
        $queryChain->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryChain->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["chains"] = $queryChain->paginate(10);
        
        return view('admin.chains.index')->with('data', $data);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_chain';
        
        if($newRecord) {
            $modalName = 'admin.inc.modal_new_chain';
            $data["chain"] = new Chain;
        } else {
            $data["chain"] = Chain::find($id);
        }
    
        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_chain';
        
        $data["chain"] = Chain::find($id);    

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
        $data["breadcrumbs"] = ["Início", "Cadeias de Lojas"];
        $data["chains"] = Chain::orderBy('id', 'desc')
            ->paginate(10);
        $data["id"] = $id;
        return view('admin.chains.index')->with('data', $data);
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

        $chain = new Chain;
        $chain->name = $request->input('name');

        $chain->save();        

        return redirect('/admin/chains')->with('success', 'Cadeia Criada');
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {        
        $chain = Chain::find($id);
        $chain->active = 1;
        $chain->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/chains');
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        $chain = Chain::find($id);
        $chain->active = 0;
        $chain->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/chains');
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

        $chain = Chain::find($id);
        $chain->name = $request->input('name');

        $chain->save();

        return redirect('/admin/chains/')->with('success', 'Cadeia atualizada.');
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

        $result = ["type" => "success", "message" => "Cadeia eliminada."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $chain = Chain::find($request->id);

            $storeList = Store::where('chain_id', $request->id)->get();

            foreach($storeList as $store) {

                // Delete Products
                DB::table('product_stores')->where('store_id', $store->id)->delete();    

                $store->delete();

            }

            $chain->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["error"] = $e;
            $result["message"] = "Erro ao eliminar cadeia.";
        }

        $returnLink = '/admin/chains/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/chains';

        return redirect($returnLink)->with($result["type"], $result["message"]);
    }


    public function fetch(Request $request) {
        
        try {

            if($request->get('query'))
            {
                $query = $request->get('query');
                $data = DB::table('chains')
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

    public function fetchForProduct(Request $request) {
        
        if($request->get('query'))
        {
         $query = $request->get('query');
         $data = Chain::where('name', 'LIKE', "%{$query}%")
           ->take(10)
           ->get();
         $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
         foreach($data as $row)
         {
          $output .= '
          <li class="chain-suggestion"><a href="#"><div class="chain-name">'.$row->name.'</div>
          ';
         }
         $output .= '</ul>';
         echo $output;
        }

    }
    
    public function validateName(Request $request) {
        
        if($request->get('name'))
        {
            $data = Chain::where('name', $request->get('name'))
                ->take(1)
                ->get();

            echo (count($data) >= 1);
       
        }
        echo false;
    }
}