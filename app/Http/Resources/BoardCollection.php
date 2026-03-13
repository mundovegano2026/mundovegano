<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Forum_post;

class BoardCollection extends ResourceCollection
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
            $this->collection->transform(function($board) {
                return [
                    'id' => $board->id,
                    'name' => $board->name,
                    'posts' => isset($board->forum_posts) ? count($board->forum_posts->where('forum_post_parent_id', 0)) : 0,
                    'last_date' => $board->forum_latest->first() ? \Carbon\Carbon::parse($board->forum_latest->first()->updated_at)->formatLocalized('%d de %B, %Y') : null
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
