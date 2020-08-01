<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Post extends Model

{

  protected $fillable=['texts','privacy'];

  public function owner()
  {
    return $this->belongsTo('App\User','user_id');
  }

  public function comments()
  {
    return $this->hasMany('App\Comment');
  }

  public function likes()
  {
    return $this->morphToMany('App\User', 'likeable')->withTimestamps();
  }

  public function shares()
  {
    return $this->belongsToMany('App\User','shares')->withTimestamps();
  }

  public function users()
  {
    return $this->belongsToMany('App\User','feeds');
  }

  public function broadcast()
  {
    if($this->privacy=='friend')
      $this->users()->attach($this->owner->friends()->select('id')->get()->modelKeys());
    else
      $this->users()->attach($this->owner->followers()->select('id')->get()->modelKeys());
  }

}
