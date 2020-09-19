<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
  protected $table ='replies';
  protected $fillable = [
    'texts','comment_id','user_id'
  ];

  public function likes()
  {
    return $this->morphToMany('App\User','likeable');
  }

  public function comment()
  {
    return $this->belongsTo('App\Comment');
  }

  public function owner()
  {
    return $this->belongsTo('App\User','user_id');
  }


}
