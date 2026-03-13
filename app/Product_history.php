<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_history extends Model
{    
    
    protected $table = 'product_history';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'name', 'category_id', 'brand_id', 'obs', 'capacity', 'user_id', 'reported'
   ];

   /**
   * Get the category that owns the product.
   */
   public function product()
   {
       return $this->belongsTo('App\Product');
   }  

    /**
    * Get the category that owns the product.
    */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }    

    /**
    * Get the category that owns the product.
    */
     public function brand()
     {
         return $this->belongsTo('App\Brand');
     }   

     /**
     * Get the category that owns the product.
     */
      public function capacity_unit()
      {
          return $this->belongsTo('App\Capacity_unit');
      }    
    
      /**
      * Get the stores owned by the product.
      */
      public function stores()
      {
          return $this->belongsToMany('App\Store', 'product_stores')->withPivot("price");
      } 
    
      /**
      * Get the stores owned by the product.
      */
      public function view_stores()
      {
          return $this->belongsToMany('App\View_store', 'product_stores', 'product_id', 'store_id', 'id')->withPivot("price");
      } 
    
      /**
      * Get the tags owned by the product.
      */
      public function tags()
      {
          return $this->belongsToMany('App\Tag', 'product_tags');
      }

      /**
      * Get the status that owns the product.
      */
      public function status()
      {
          return $this->belongsTo('App\Status');
      }  

      /**
      * Get the user that owns the product.
      */
      public function user()
      {
          return $this->belongsTo('App\User');
      }   

      /**
      * Get the admin that owns the product.
      */
      public function admin()
      {
          return $this->belongsTo('App\Admin');
      }   

    // Tags currently being stored in text field
    // /**
    // * Get the tags owned by the product.
    // */
    // public function tags()
    // {
    //     return $this->hasMany('App\Tag');
    // }
    
    /**
    * Get the tags owned by the product.
    */
    public function reports()
    {
        return $this->hasMany('App\Product_report');
    }

   /**
    * Get the images owned by the product.
    */
    public function images()
    {
        return $this->hasMany('App\Image');
    }

   /**
    * Get the reviews owned by the product.
    */
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
