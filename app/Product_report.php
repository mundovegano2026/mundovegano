<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_report extends Model
{
    /**
     * Get the products owned by the product report.
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
    * Get the report type that owns the product.
    */
    public function type()
    {
        return $this->belongsTo('App\Report_type');
    } 
    
}
