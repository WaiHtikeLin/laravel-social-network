<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  protected $guarded = [];

  protected $keyType = 'string';

  public $incrementing = false;

  public function members()
  {
    return $this->belongsToMany('App\User','room_members')->withTimestamps();
  }

  public function messages()
  {
    return $this->hasMany('App\ChatMessage');
  }
}
