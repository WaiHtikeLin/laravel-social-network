<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $guarded=[];
  public function likers()
  {
    return $this->morphToMany('App\User', 'likeable');
  }

  public function replies()
  {
    return $this->hasMany('App\Reply');
  }

  public function owner()
  {
    return $this->belongsTo('App\User','user_id');
  }

  public function post()
  {
    return $this->belongsTo('App\Post');
  }
}
