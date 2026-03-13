<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View_store extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chain_id', 'name', 'active', 'caop_id', 'text_location', 'status_id', 'distance'
    ];

    /**
    * Get the caop that owns the store.
    */
     public function caop()
     {
         return $this->belongsTo('App\Caop');
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
        * Get the products owned by the store.
        */
       public function products()
       {
           return $this->belongsToMany('App\Product', 'product_view_stores')->withPivot("price");
       }
    
}
