<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $table = 'status';

    /**
    * Get the products owned by the status.
    */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
    * Get the products owned by the status.
    */
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    /**
    * Get the product reports owned by the status.
    */
    public function product_reports()
    {
        return $this->hasMany('App\Product_report');
    }

    /**
    * Get the stores owned by the status.
    */
    public function stores()
    {
        return $this->hasMany('App\Store');
    }

    /**
    * Get the brands owned by the status.
    */
    public function brands()
    {
        return $this->hasMany('App\Brand');
    }

    /**
    * Get the brands owned by the status.
    */
    public function tags()
    {
        return $this->hasMany('App\Tag');
    }
}
