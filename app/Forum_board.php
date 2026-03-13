<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum_board extends Model
{
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'user_id', 'updated_at', 'created_at'
    ];

    /**
     * Get the posts owned by the board.
     */
    public function forum_posts()
    {
        return $this->hasMany('App\Forum_post');
    }

    /**
     * Get the posts owned by the board.
     */
    public function forum_latest()
    {
        return $this->hasMany('App\Forum_post')->latest();
    }
}
