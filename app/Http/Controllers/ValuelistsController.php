<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Valuelist;
use DB;

class ValuelistsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Valores"];
        $data["search"] = "";

        // New product query
        $queryValuelist = Valuelist::query();

        //Add sorting
        $queryValuelist->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryValuelist->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["valuelists"] = $queryValuelist->paginate(10);

        return view('admin.valuelists.index')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Valores"];
        $data["valuelists"] = Valuelist::orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.validation.valuelists')->with('data', $data);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_valuelist';
        
        if($newRecord) {
            $modalName = 'admin.inc.modal_new_valuelist';
            $data["valuelist"] = new Valuelist;
        } else {
            $data["valuelist"] = Valuelist::find($id);
        }

        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_valuelist';
        
        $data["valuelist"] = Valuelist::find($id);    

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
        $data["breadcrumbs"] = ["Início", "Valores"];
        $data["valuelists"] = Valuelist::orderBy('id', 'desc')
            ->paginate(10);
        $data["id"] = $id;
        return view('admin.valuelists.index')->with('data', $data);
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

        $valuelist = new Valuelist;
        $valuelist->name = $request->input('name');

        $valuelist->save();        

        return redirect('/admin/valuelists')->with('success', 'Valor Criado');
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

        $valuelist = Valuelist::find($id);
        $valuelist->name = $request->input('name');
        $valuelist->value = $request->input('value');

        $valuelist->save();

        $returnLink = '/admin/valuelists/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/valuelists';

        return redirect($returnLink)->with('success', 'Valor atualizado.');
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

        $result = ["type" => "success", "message" => "Valor eliminado."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $valuelist = Valuelist::find($request->id);    

            $valuelist->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["error"] = $e;
            $result["message"] = "Erro ao eliminar valor.";
        }

        $returnLink = '/admin/valuelists/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/valuelists';

        return redirect($returnLink)->with($result["type"], $result["message"]);
    }
    public function fetch(Request $request) {
        
        try {

            if($request->get('query'))
            {
                $query = $request->get('query');
                $data = DB::table('valuelists')
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
            $data = Valuelist::where('name', $request->get('name'))
                ->take(1)
                ->get();

            echo (count($data) >= 1);
       
        }
        echo false;
    }
}
