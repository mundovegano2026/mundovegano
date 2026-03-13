<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Status;
use DB;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Users"];
        $data["search"] = "";

        // New product query
        $queryUser = User::query();

        //Add sorting
        $queryUser->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryUser->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["users"] = $queryUser->paginate(10);

        return view('admin.users.index')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Users"];
        $data["users"] = User::where('status_id', 1)->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.validation.users')->with('data', $data);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_user';
        $data["status"] = Status::find(2);
        
        if($newRecord) {
            $modalName = 'admin.inc.modal_new_user';
            $data["user"] = new User;
        } else {
            $data["user"] = User::find($id);
            $data["status"] = $data["user"]->status;
        }

        $data["statuses"] = Status::all();
    
        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_user';
        
        $data["user"] = User::find($id);    

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
        $data["breadcrumbs"] = ["Início", "Users"];
        $data["users"] = User::orderBy('id', 'desc')
            ->paginate(10);
        $data["id"] = $id;
        return view('admin.users.index')->with('data', $data);
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

        $user = new User;
        $user->name = $request->input('name');
        $user->status_id = $request->input('status');

        $user->save();        

        return redirect('/admin/users')->with('success', 'Utilizador Criado');
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

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->status_id = $request->input('status'); 

        $user->save();

        $returnLink = '/admin/users/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/users';

        return redirect($returnLink)->with('success', 'Utilizador atualizado.');
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

        $result = ["type" => "success", "message" => "Utilizador eliminado."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $user = User::find($request->id);

            // Delete Products
            // DB::table('product_tags')->where('tag_id', $tag->id)->delete();    

            $user->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["error"] = $e;
            $result["message"] = "Erro ao eliminar utilizador.";
        }

        $returnLink = '/admin/users/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/users';

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
        $user = User::find($id);
        $user->active = 1;
        $user->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/users');
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        $user = User::find($id);
        $user->active = 0;
        $user->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/users');
    }

    public function fetch(Request $request) {
        
        try {

            if($request->get('query'))
            {
                $query = $request->get('query');
                $data = DB::table('users')
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
            $data = User::where('name', $request->get('name'))
                ->take(1)
                ->get();

            echo (count($data) >= 1);
       
        }
        echo false;
    }
}
