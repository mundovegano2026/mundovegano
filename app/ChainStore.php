<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChainStore extends BaseModel
{    

    /**
    * Get the chain that owns the store.
    */
     public function chain()
     {
         return $this->belongsTo('App\Chain');
     } 

}
