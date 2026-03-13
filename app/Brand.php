<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    /**
     * Get the products owned by the brand.
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
    * Get the status that owns the brand.
    */
     public function status()
     {
         return $this->belongsTo('App\Status');
     } 
}
