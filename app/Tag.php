<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // Tags currently being stored in text field
    // /**
    //  * Get the products owned by the tag.
    //  */
    // public function products()
    // {
    //     return $this->hasMany('App\Models\Product');
    // }

    /**
    * Get the status that owns the brand.
    */
     public function status()
     {
         return $this->belongsTo('App\Status');
     } 

     /**
      * Get the products owned by the tag.
      */
     public function products()
     {
         return $this->belongsToMany('App\Product', 'product_tags');
     }

}
