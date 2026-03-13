<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\View_store;
use App\Caop;
use App\Chain;
use App\Status;
use DB;

class StoresController extends Controller
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
        $data["breadcrumbs"] = ["Início", "Lojas"];     
        $data["search"] = "";

        // New store query
        $queryStore = View_store::query();

        //Add sorting
        $queryStore->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryStore->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["stores"] = $queryStore->paginate(10);

        return view('admin.stores.index')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Lojas"];
        $data["stores"] = Store::where('status_id', 1)->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.validation.stores')->with('data', $data);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_store';
        
        if($newRecord) {
            $modalName = 'admin.inc.modal_new_store';
            $data["store"] = new Store;
        } else {
            $data["store"] = View_store::find($id);
            $data["store"]->text_location = str_replace('POINT','', $data["store"]->text_location);
            $data["store"]->text_location = str_replace('(', '', $data["store"]->text_location);
            $data["store"]->text_location = str_replace(')', '', $data["store"]->text_location);
        }
        
        // $data["chains"] = Chain::orderBy('name', 'asc')
        //     ->pluck('name', 'id')
        //     ->toArray();
        
        $data["distritos"] = Caop::select(
            DB::raw("SUBSTRING(dicofre, 1, 2) AS dicofre_calc"),'distrito')
            ->groupBy(DB::raw('SUBSTRING(dicofre, 1, 2)'))
            ->orderBy('distrito', 'asc')
            ->pluck('distrito', 'dicofre_calc')
            ->toArray();

        if(!$newRecord) {
            $data["concelhos"] = Caop::select(
                DB::raw("SUBSTRING(dicofre, 1, 4) AS dicofre_calc"),'concelho')
                ->whereRaw('SUBSTRING(dicofre, 1, 2) = ' . substr($data["store"]->caop->dicofre,0,2))
                ->groupBy(DB::raw('SUBSTRING(dicofre, 1, 4)'))
                ->orderBy('concelho', 'asc')
                ->pluck('concelho', 'dicofre_calc')
                ->toArray();

            $data["freguesias"] = Caop::orderBy('freguesia', 'asc')
                ->whereRaw('SUBSTRING(dicofre, 1, 4) = ' . substr($data["store"]->caop->dicofre,0,4))
                ->groupBy('freguesia')
                ->pluck('freguesia', 'dicofre')
                ->toArray();
        }

        $data["statuses"] = Status::all();
        $data["status"] = $data["store"]->status;
        
        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_store';
        
        $data["store"] = Store::find($id);    

        return view($modalName)->with('data', $data);
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
        $data = array();
        $data["breadcrumbs"] = ["Início", "Lojas"];
        $data["stores"] = Store::orderBy('id', 'desc')
            ->paginate(10);
        $data["id"] = $id;
        return view('admin.stores.index')->with('data', $data);
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
            'freguesia' => 'required'
        ]);

        $store = new Store;
        $store->name = $request->input('name');
        $store->address = $request->input('address');
        $store->status_id = 2; // Valid store

        // Chain
        $chain = Chain::where('name', $request->input('chain'))->first();
        if($chain !== null) {
            $store->chain_id = $chain->id;
        }

        if($request->input('location') != '') {
            $coordinates = explode(' ', $request->input('location'));
            if(str_contains($coordinates[0], ',')) {
                $coordinates = explode(',', $coordinates[0]);
            } 
            $store->location = DB::raw("(ST_PointFromText('POINT(" . $coordinates[0] . " " . $coordinates[1] . ")'))");
        } else {
            $store->location = null;
        }

        // if(isset($coordinates) && count($coordinates) > 1) {
        //     $store->location = DB::raw("(ST_PointFromText('POINT(" . $coordinates[0] . " " . $coordinates[1] . ")'))");
        // } else {
        //     $store->location = null;
        // }
        $caop = Caop::where('dicofre', $request->input('freguesia'))->first()->id;
        $store->caop_id = $caop;

        $store->save();        

        return redirect('/admin/stores')->with('success', 'Loja Criada');
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
            'name' => 'required',
            'freguesia' => 'required'
        ]);

        $store = Store::find($id);
        $store->name = $request->input('name');
        $store->status_id = $request->input('status'); 
        $store->address = $request->input('address');

        // Chain
        $chain = Chain::where('name', $request->input('chain'))->first();
        if($chain !== null) {
            $store->chain_id = $chain->id;
        } else {
            $store->chain_id = 0;
        }

        $coordinates = explode(',', $request->input('location'));

        if(count($coordinates) > 1) {
            $store->location = DB::raw("(ST_PointFromText('POINT(" . $coordinates[0] . " " . $coordinates[1] . ")'))");
        } else {
            $store->location = null;
        }
        $caop = Caop::where('dicofre', $request->input('freguesia'))->firstOrFail()->id;
        $store->caop_id = $caop;

        $store->save();

        $returnLink = '/admin/stores/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/stores';

        return redirect($returnLink)->with('success', 'Loja atualizada.');
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

        $result = ["type" => "success", "message" => "Loja eliminada."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $store = Store::find($request->id);

            // Delete Products
            DB::table('product_stores')->where('store_id', $store->id)->delete(); 

            $storeList = Store::where('chain_id', $request->id)->get();

            $store->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["error"] = $e;
            $result["message"] = "Erro ao eliminar loja.";
        }

        $returnLink = '/admin/stores/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/stores';

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
        $store = Store::find($id);
        $store->active = 1;
        $store->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/stores');
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        $store = Store::find($id);
        $store->active = 0;
        $store->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/stores');
    }

    public function fetch(Request $request) {
        
        if($request->get('query'))
        {
         $query = $request->get('query');
         $data = Store::with('caop')
           ->where('name', 'LIKE', "%{$query}%")
           ->take(10)
           ->get();
         $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
         foreach($data as $row)
         {
          $output .= '
          <li class="store-suggestion"><a href="#"><div class="store-name">'.$row->name.'</div>
          <div>'.$row->caop->concelho.'</div></a></li>
          ';
         }
         $output .= '</ul>';
         echo $output;
        }

    }
    
    public function validateName(Request $request) {
        
        if($request->get('name') && $request->get('chain'))
        {
            $chain = Chain::where('name', $request->get('chain'))->first();
            $data = Store::where('name', $request->get('name'))
                ->where('chain_id', $chain->id)
                ->take(1)
                ->get();

            echo (count($data) >= 1);
       
        }
        echo false;
    }
}