<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Report_typeCollection extends ResourceCollection
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
            $this->collection->transform(function($reportType) {
                return [
                    'id' => $reportType->id,
                    'type' => $reportType->type
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
