<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPic extends Model
{
  protected $fillable=['user_id','type', 'name', 'status'];

  public function owner()
  {
    return $this->belongsTo('App\User','user_id');
  }

}
