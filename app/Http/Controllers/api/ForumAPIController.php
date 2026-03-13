<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Resources\BoardCollection as BoardCollectionResource;
use App\Http\Resources\PostCollection as PostCollectionResource;
use App\Http\Resources\Post as PostResource;
use App\Forum_post;
use App\Forum_board;

class ForumAPIController extends Controller
{

    public function boards(Request $request) {        
     
        $data = Forum_board::with('forum_posts')->get();
        
        return response()->json([
            'error' => false,
            'message' => 'Quadros principais do forum obtidos.',
            'boards' => new BoardCollectionResource($data)
        ]);
    }

    public function posts($board_id) {        
     
        $data = Forum_post::where('forum_board_id', $board_id)->where('forum_post_parent_id', 0)->get();
        
        return response()->json([
            'error' => false,
            'message' => 'Publicações do tópico do forum obtidas.',
            'posts' => new PostCollectionResource($data)
        ]);
    }

    public function post($post_id) {        
     
        $post = Forum_post::findOrFail($post_id);
        $post->comments = Forum_post::where('forum_post_parent_id', $post_id)->get();
        
        return response()->json([
            'error' => false,
            'message' => 'Comentários da publicação do forum obtidos.',
            'post' => new PostResource($post)
        ]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->post = json_decode($request->post, true);
        $req = new Request($request->post);
        $userId = $request->user() != null ? $request->user()->id : 0;   
        
        $data = $this->validate($req, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $post = new Forum_post($request->post); // Get base product model
        $post->user_id = $userId;     
        $post->save();
        
        return response()->json([
            'error' => false,
            'message' => 'Publicação registada.',
            'post' => new PostResource($post)
        ]);
        
    }

}
