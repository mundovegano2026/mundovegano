<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StoreCollection as StoreCollectionResource;
use App\Http\Resources\ReviewCollection as ReviewCollectionResource;
use App\Http\Resources\TagCollection as TagCollectionResource;
use App\Http\Resources\ProductCollection as ProductCollectionResource;

class Contact extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'topic' => $this->topic,
            'message' => $this->message,
            'user_id' => $this->user_id
        ];
    }    
    
    public function with($request) {
        return [
            'version' => '1.0.0',
            'author_url' => url('http://www.mundovegano.pt')
        ];
    }
}