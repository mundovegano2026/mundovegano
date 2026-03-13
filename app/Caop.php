<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caop extends Model
{

    protected $table = 'caops';

    /**
     * Get the stores owned by the caop.
     */
    public function view_stores()
    {
        return $this->hasMany('App\View_store');
    }

    /**
     * Get the stores owned by the caop.
     */
    public function stores()
    {
        return $this->hasMany('App\Store');
    }
    
}
