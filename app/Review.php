<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class Review extends Model
{
   /**
   * Get the product that owns the review.
   */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }  

    /**
    * Get the status that owns the product.
    */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }  

    /**
    * Get the user that owns the review.
    */
     public function user()
     {
         return $this->belongsTo('App\User');
     }  
     
     public function getCreatedAtAttribute($date)
     {
        setlocale(LC_TIME, 'pt-PT');     
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->formatLocalized('%d de %B de %Y');
     }
     
     public function getUpdatedAtAttribute($date)
     {
        setlocale(LC_TIME, 'pt-PT');     
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->formatLocalized('%d de %B de %Y');
     }

}
