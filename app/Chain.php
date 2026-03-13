<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    /**
     * Get the stores owned by the chain.
     */
    public function stores()
    {
        return $this->hasMany('App\Store');
    }
    /**
     * Get the stores owned by the chain.
     */
    // public function view_stores()
    // {
    //     return $this->hasMany('App\View_store');
    // }
    
    /**
    * Get the stores owned by the chain.
    */
    public function view_stores()
    {
        return $this->belongsToMany('App\View_store', 'chain_stores', 'chain_id', 'store_id', 'id')->withPivot("price");
    } 

    /**
     * Get the products owned by the store.
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_chains', 'chain_id', 'product_id', 'id')->withPivot("price", "exceptions");
    }
}
