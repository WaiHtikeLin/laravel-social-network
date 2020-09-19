<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Post extends Model

{

  protected $fillable=['texts','privacy', 'user_id','shareable','copyable','share_id','copy_id'];

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

  public function reactions()
  {
    return $this->morphToMany('App\User', 'reactable')->withPivot('emoji')->withTimestamps();
  }

  public function scopeAttributes($query,$user_id)
  {
    $query->withCount(['reactions','reactions as reaction_status'=>function($query) use ($user_id){
      $query->where('id',$user_id);
    },
    'likes','likes as like_status'=> function($query) use ($user_id){
        $query->where('id',$user_id);
      },'favorites as favorite_status'=> function($query) use ($user_id){
          $query->where('id',$user_id);
        },'comments','shares']);
  }


  public function shares()
  {
    return $this->hasMany(self::class,'share_id');
  }

  public function favorites()
  {
    return $this->belongsToMany('App\User','favorites');
  }
  // public function sharesToPublic()
  // {
  //   return $this->belongsToMany('App\User','shares')->withPivot('type')->withTimestamps()->wherePivot('type','public');
  // }
  //
  // public function sharesViewable()
  // {
  //   return $this->belongsToMany('App\User','shares')->withPivot('type')->withTimestamps()
  //   ->wherePivotIn('type',['public','friend']);
  // }

  public function users()
  {
    return $this->belongsToMany('App\User','feeds');
  }
  //
  // public function broadcast()
  // {
  //     $this->users()->attach($this->owner->followers()->select('id')->get()->modelKeys());
  // }

  // public function getPrivacyImg()
  // {
  //   return $this->privacy=='public' ? url('/img/world.png') : url('/img/user.png');
  // }

}
