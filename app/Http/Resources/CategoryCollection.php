<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ProductCollection as ProductCollectionResource;

class CategoryCollection extends ResourceCollection
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
            $this->collection->transform(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'level' => $category->level,
                    'parent' => $category->parent_id,
                    'path' => $category->path,
                    'subCategories' => isset($category->subCategories) ? $category->subCategories : null,
                    'productList' => isset($category->products) ? new ProductCollectionResource($category->products) : '',
                    'image' => isset($category->images[0]) ? 'storage/category_images/' . $category->images->last()->path : ''
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
