<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Caop as CaopResource;

class StoreCollection extends ResourceCollection
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
            $this->collection->transform(function($store) {
                return [
                    'id' => $store->id,
                    'name' => $store->name,
                    'status' => $store->status_id,
                    'chain_id' => $store->chain_id,
                    'products' => $store->products,
                    'address' => $store->address,
                    'caop' => isset($store->caop) ? new CaopResource($store->caop) : null,
                    'text_location' => $store->text_location
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
