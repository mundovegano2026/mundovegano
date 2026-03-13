<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Get the users owned by the role.
     */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
