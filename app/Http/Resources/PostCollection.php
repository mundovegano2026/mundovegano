<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\PostCollection as PostCollectionResource;
use App\User;
use App\Forum_board;
use App\Forum_post;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return 
            $this->collection->transform(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'forum_board_id' => $post->forum_board_id,
                    'path' => Forum_board::find($post->forum_board_id)->name,
                    'user' => User::find($post->user_id) != null ? User::find($post->user_id)->name : null,
                    'comments' => isset($post->comments) ? new PostCollectionResource($post->comments) : null,
                    'commentsCount' => $post->comments->count(),
                    'newest' => count($post->comments) ? \Carbon\Carbon::parse($post->comments->last()->created_at)->formatLocalized('%d de %B, %Y') : \Carbon\Carbon::parse($post->created_at)->formatLocalized('%d de %B, %Y'),
                    'created_at' => \Carbon\Carbon::parse($post->created_at)->formatLocalized('%d de %B, %Y')
                ];
            });
    }    
    
    public function with($request) {
        return [
            'version' => '1.0.0',
            'author_url' => url('http://www.mundovegano.pt')
        ];
    }
}
