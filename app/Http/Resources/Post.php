<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PostCollection as PostCollectionResource;
use App\User;
use App\Forum_board;
use App\Forum_post;

class Post extends JsonResource
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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'forum_board_id' => $this->forum_board_id,
            'path' => Forum_board::find($this->forum_board_id)->name,
            'user' => User::find($this->user_id) != null ? User::find($this->user_id)->name : null,
            'comments' => isset($this->comments) ? new PostCollectionResource($this->comments) : null,
            'commentsCount' => isset($this->comments) ? count($this->comments) : 0,
            'newest' => count($this->comments) ? \Carbon\Carbon::parse($this->comments->last()->created_at)->formatLocalized('%d de %B, %Y') : \Carbon\Carbon::parse($this->created_at)->formatLocalized('%d de %B, %Y'),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->formatLocalized('%d de %B, %Y')
        ];
    }    
    
    public function with($request) {
        return [
            'version' => '1.0.0',
            'author_url' => url('http://www.mundovegano.pt')
        ];
    }
}