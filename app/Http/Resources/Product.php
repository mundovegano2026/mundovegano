<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StoreCollection as StoreCollectionResource;
use App\Http\Resources\View_storeCollection as View_storeCollectionResource;
use App\Http\Resources\ReviewCollection as ReviewCollectionResource;
use App\Http\Resources\TagCollection as TagCollectionResource;
use App\Http\Resources\ProductCollection as ProductCollectionResource;

class Product extends JsonResource
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
            'created_at' => utf8_encode(\Carbon\Carbon::parse($this->created_at)->formatLocalized('%d de %B, %Y')),
            'user' => $this->user,
            'admin' => $this->admin,
            'category' => $this->category,
            'brand' => $this->brand,
            'obs' => $this->obs,
            'reported' => isset($this->reported) ? $this->reported : false,
            'reviews' => isset($this->reviews) ? new ReviewCollectionResource($this->reviews) : 0,
            'score' => isset($this->reviews) ? $this->reviews->avg('score') : 0,
            'relevance_score' => isset($this->relevance_score) ? $this->relevance_score : 0,
            'commentCount' => isset($this->reviews) ? $this->reviews->count() : 0,
            'image' => isset($this->images[0]) ? 'storage/product_images/' . $this->images[0]->path : '',
            'images' => $this->images,
            'tags' => new TagCollectionResource($this->tags),
            // 'stores' => new View_storeCollectionResource($this->stores),
            'stores' => new View_storeCollectionResource($this->view_stores),
            'similarProducts' => isset($this->similarProducts) ? new ProductCollectionResource($this->similarProducts) : null,
            'chainList' => isset($this->chainList) ? $this->chainList : null
        ];
    }    
    
    public function with($request) {
        return [
            'version' => '1.0.0',
            'author_url' => url('http://www.mundovegano.pt')
        ];
    }
}