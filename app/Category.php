<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Get the products owned by the category.
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Get the images owned by the category.
     */
     public function images()
     {
         return $this->hasMany('App\Category_image');
     }
}
