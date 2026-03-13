<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ReviewCollection as ReviewCollectionResource;
use App\Http\Resources\StoreCollection as StoreCollectionResource;
use App\Http\Resources\View_storeCollection as View_storeCollectionResource;

class ProductCollection extends ResourceCollection
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
            $this->collection->transform(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'upper' => $product->upper,
                    'category' => $product->category,
                    'created_at' => utf8_encode(\Carbon\Carbon::parse($product->created_at)->formatLocalized('%d de %B, %Y')),
                    'brand' => $product->brand,
                    'obs' => $product->obs,
                    'reported' => isset($product->reported) ? $product-reported : false,
                    'reviews' => isset($product->reviews) ? new ReviewCollectionResource($product->reviews) : 0,
                    'commentCount' => isset($product->reviews) ? $product->reviews->count() : 0,
                    'image' => isset($product->images[0]) ? 'storage/product_images/' . $product->images[0]->path : '',
                    'score' => isset($product->score) ? $product->score : null,
                    'relevance_score' => isset($product->relevance_score) ? $product->relevance_score : 0,
                    'stores' => isset($product->stores) ? new View_storeCollectionResource($product->stores) : null
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
