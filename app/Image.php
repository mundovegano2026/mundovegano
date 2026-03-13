<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{    
    /**
    * Get the type that owns the image.
    */
    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }  
}
