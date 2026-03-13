<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{    
    /**
    * Get the images owned by the type.
    */
    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }
}
