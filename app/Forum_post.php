<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum_post extends Model
{
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'title', 'body', 'forum_board_id', 'forum_post_parent_id', 'user_id', 'updated_at', 'created_at'
    ];

    /**
    * Get the category that owns the product.
    */
   public function forum_board()
   {
       return $this->belongsTo('App\Forum_board');
   } 

   /**
   * Get the category that owns the product.
   */
    public function comments()
    {
        return $this->hasMany('App\Forum_post', 'forum_post_parent_id');
    }   
}
