<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Forum_board;
use App\Forum_post;
use DB;

class ForumsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Fórum"];
        // $data["forums"] = Forum_board::orderBy('name', 'asc')
        //     ->paginate(10);
        $data["search"] = '';

        // New forum query
        $queryForum = Forum_board::query();

        //Add sorting
        $queryForum->orderBy('name', 'asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryForum->where('name', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["forums"] = $queryForum->paginate(10);

        return view('admin.forums.index')->with('data', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showComment($id)
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Fórum"];
        $data["forums"] = Forum_post::orderBy('title', 'asc')
            ->where('forum_post_parent_id', $id)
            ->paginate(10);
        $data["id"] = $id;
        $data["search"] = "";
        $data["original_post"] = Forum_post::find($id);
        $data["comment"] = $data["original_post"];

        return view('admin.forums.index')->with('data', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPost($id)
    {
        $data = array();
        $data["breadcrumbs"] = ["Início", "Fórum"];
        $data["id"] = $id;
        $data["original_post"] = Forum_post::find($id);
        $data["search"] = '';

        // New forum query
        $queryForum = Forum_post::query()    
            ->where('forum_post_parent_id', 0);

        //Add sorting
        $queryForum->orderBy('title', 'asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryForum->where('title', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["forums"] = $queryForum->paginate(10);

        return view('admin.forums.index')->with('data', $data);
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
        $data["breadcrumbs"] = ["Início", "Fórum"];
        $data["id"] = $id;
        $data["original_board"] = Forum_board::find($id);
        $data["search"] = '';

        // New forum query
        $queryForum = Forum_post::query()            
            ->where('forum_board_id', $id)
            ->where('forum_post_parent_id', 0);

        //Add sorting
        $queryForum->orderBy('title', 'asc');

        //Add Conditions
        if(request()->has('search') && request('search') != '') {
            $queryForum->where('title', 'LIKE', '%' . trim(request('search')) . '%');
            $data["search"] = request('search');
        }

        //Fetch list of results
        $data["forums"] = $queryForum->paginate(10);


        return view('admin.forums.index')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $returnLink = '/admin/forums';
        $originalBoard = $request->input('original_board');
        $originalPost = $request->input('original_post');
        $feedbackMsg = "";

        if($originalBoard || $originalPost) {
            $this->validate($request, [
                'title' => 'required',
                'body' => 'required'
            ]);
            $forum = new Forum_post;
            $forum->forum_board_id = $request->input('original_board');
            $forum->title = $request->input('title');
            $forum->body = $request->input('body');
            if($originalPost) { 
                $forum->forum_post_parent_id = $originalPost; 
                $parentPost = Forum_post::find($originalPost);
                $forum->forum_board_id = $parentPost->forum_board_id;
            }
            $feedbackMsg = "Publicação criada.";
            $returnLink = '/admin/forums/' . $request->input('original_board');
        } else {
            $this->validate($request, [
                'name' => 'required'
            ]);
            $forum = new Forum_board;
            $forum->name = $request->input('name');
            $feedbackMsg = "Tema criado.";
        }

        $forum->save();        

        return redirect($returnLink)->with('success', $feedbackMsg);
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

        $forumBoard = Forum_board::find($id);
        $forumBoard->name = $request->input('name');

        $forumBoard->save();

        return redirect('/admin/forums/boards/' . $forumBoard->id)->with('success', 'Tema atualizado.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePost(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $returnLink = '/admin/forums/posts/';

        $forumPost = Forum_post::find($id);
        $forumPost->title = $request->input('title');
        $forumPost->body = $request->input('body');

        $forumPost->save();

        if($forumPost->forum_post_parent_id) $returnLink = '/admin/forums/comments/';

        return redirect($returnLink . $forumPost->id)->with('success', 'Publicação atualizada.');
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