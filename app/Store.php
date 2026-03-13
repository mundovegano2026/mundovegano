<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Store extends Model
{     

    /**
     * Get the products owned by the store.
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_stores', 'store_id', 'product_id', 'id')->withPivot("price");
    }

    /**
    * Get the chain that owns the store.
    */
     public function chain()
     {
         return $this->belongsTo('App\Chain');
     } 

     /**
     * Get the status that owns the store.
     */
      public function status()
      {
          return $this->belongsTo('App\Status');
      }  

     /**
     * Get the caop that owns the store.
     */
      public function caop()
      {
          return $this->belongsTo('App\Caop');
      } 


}
