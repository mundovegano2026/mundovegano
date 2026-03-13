<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductCollection as ProductCollectionResource;

class Category extends JsonResource
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
            'level' => $this->level,
            'parent' => $this->parent_id,
            'path' => $this->path,
            'subCategories' => isset($this->subCategories) ? $this->subCategories : null,
            'productList' => isset($this->products) ? new ProductCollectionResource($this->products) : '',
            'image' => isset($this->images[0]) ? 'storage/category_images/' . $this->images->last()->path : ''
        ];
    }

    public function with($request) {
        return [
            'version' => '1.0.0',
            'author_url' => url('http://www.mundovegano.pt')
        ];
    }
}
