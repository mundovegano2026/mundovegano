<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capacity_unit extends Model
{
    /**
     * Get the products owned by the capacity unit.
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
