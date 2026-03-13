<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Caop as CaopResource;

class View_storeCollection extends ResourceCollection
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
            $this->collection->transform(function($view_store) {
                return [
                    'id' => $view_store->id,
                    'name' => $view_store->name,
                    'chain_id' => $view_store->chain_id,
                    'address' => $view_store->address,
                    'status' => $view_store->status_id,
                    'pivot' => $view_store->pivot,
                    'distance' => $view_store->distance,
                    'caop' => isset($view_store->caop) ? new CaopResource($view_store->caop) : null,
                    'text_location' => $view_store->text_location
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
