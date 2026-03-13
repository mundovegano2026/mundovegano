<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report_type extends Model
{

    protected $table = 'report_types';
    
    /**
    * Get the tags owned by the product.
    */
    public function reports()
    {
        return $this->hasMany('App\Product_report');
    }
    
}
