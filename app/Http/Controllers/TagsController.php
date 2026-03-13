<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Status;
use DB;

class TagsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Tags"];
        $data["search"] = "";

        // New product query
        $queryTag = Tag::query();

        //Add sorting
        $queryTag->orderBy('name','asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryTag->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["tags"] = $queryTag->paginate(10);

        return view('admin.tags.index')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validation()
    {
        $data = array();
        $data["breadcrumbs"] = ["Validação", "Tags"];
        $data["tags"] = Tag::where('status_id', 1)->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.validation.tags')->with('data', $data);
    }

    public function loadModal($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_edit_tag';
        $data["status"] = Status::find(2);
        
        if($newRecord) {
            $modalName = 'admin.inc.modal_new_tag';
            $data["tag"] = new Tag;
        } else {
            $data["tag"] = Tag::find($id);
            $data["status"] = $data["tag"]->status;
        }

        $data["statuses"] = Status::all();
    
        return view($modalName)->with('data', $data);
    }

    public function loadModalDelete($id) {

        $newRecord = $id == 0;
        $data = array();
        $modalName = 'admin.inc.modal_delete_tag';
        
        $data["tag"] = Tag::find($id);    

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
        $data["breadcrumbs"] = ["Início", "Tags"];
        $data["tags"] = Tag::orderBy('id', 'desc')
            ->paginate(10);
        $data["id"] = $id;
        return view('admin.tags.index')->with('data', $data);
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

        $tag = new Tag;
        $tag->name = $request->input('name');
        $tag->status_id = $request->input('status');

        $tag->save();        

        return redirect('/admin/tags')->with('success', 'Tag Criada');
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

        $tag = Tag::find($id);
        $tag->name = $request->input('name');
        $tag->status_id = $request->input('status'); 

        $tag->save();

        $returnLink = '/admin/tags/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/tags';

        return redirect($returnLink)->with('success', 'Tag atualizada.');
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

        $result = ["type" => "success", "message" => "Tag eliminada."];
        
        $this->validate($request, [
            'id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $tag = Tag::find($request->id);

            // Delete Products
            DB::table('product_tags')->where('tag_id', $tag->id)->delete();    

            $tag->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result["type"] = "danger";
            $result["error"] = $e;
            $result["message"] = "Erro ao eliminar tag.";
        }

        $returnLink = '/admin/tags/';
        if($request->input("validation") == "true") $returnLink = 'admin/validation/tags';

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
        $tag = Tag::find($id);
        $tag->active = 1;
        $tag->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/tags');
    }

    /**
     * Activate the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        $tag = Tag::find($id);
        $tag->active = 0;
        $tag->save();
        // Check for correct user
        // if(auth()->user()->id != $category->user_id) {
        //     return redirect('posts')->with('error', 'Unauthorized page');
        // }

        return redirect('admin/tags');
    }

    public function fetch(Request $request) {
        
        try {

            if($request->get('query'))
            {
                $query = $request->get('query');
                $data = DB::table('tags')
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
            $data = Tag::where('name', $request->get('name'))
                ->take(1)
                ->get();

            echo (count($data) >= 1);
       
        }
        echo false;
    }
}
