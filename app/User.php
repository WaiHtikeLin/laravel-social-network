<?php
namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Cashier\Billable;
//use Laravel\Passport\HasApiTokens;
use Auth;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canJoinRoom($roomId)
    {
      return \DB::table('chatrooms')->where([
        [
          'room_id',$roomId
        ],
        [
          'user_id', $this->id
        ]
      ])->exists();
    }

    public function settings()
    {
      return $this->hasOne('App\Setting');
    }

    public function pics()
    {
      return $this->hasMany('App\UserPic');
    }

    public function getProfilePic()
    {
      return $this->pics()->where([['type','profile'],['status',1]])->value('name');

    }

    public function information()
    {
      return $this->hasOne('App\UserInformation');
    }

    public function chatRooms()
    {
      return $this->belongsToMany('App\Room','room_members','user_id','room_id')->withTimestamps();
    }

    public function canViewInfo($privacy,$id)
    {
      if($privacy=='onlyme')
        return false;
      if($privacy=='public')
        return true;
      if($this->isAlreadyFriends($id))
        return true;
      return false;
    }

    public function posts()
    {
      return $this->hasMany('App\Post');
    }

    public function sharedPosts()
    {
      return $this->belongsToMany('App\Post','shares');
    }

    public function favorites()
    {
      return $this->belongsToMany('App\Post','favorites')->withTimestamps();
    }

    public function likedPosts()
    {
      return $this->morphedByMany('App\Post', 'likeable')->withTimestamps();
    }

    public function reactedPosts()
    {
      return $this->morphedByMany('App\Post', 'reactable')->withTimestamps();
    }

    public function likedComments()
    {
      return $this->morphedByMany('App\Comment', 'likeable')->withTimestamps();
    }

    public function likedReplies()
    {
      return $this->morphedByMany('App\Reply', 'likeable')->withTimestamps();
    }

    public function comments()
    {
      return $this->hasMany('App\Comment');
    }

    public function replies()
    {
      return $this->hasMany('App\Reply');
    }

    public function feeds()
    {
      return $this->belongsToMany('App\Post','feeds');
    }
    //
    // public function getFeeds()
    // {
    //   return $this->feeds()->with(['owner.pics'=> function($query){
    //     $query->where('type','profile');
    //   }])->withCount('likes','comments','shares')->take(10)->get();
    // }

    public static function getPopularUsers()
    {
      $id=Auth::id();
      $users=self::where('id','<>',$id)->get();

      return $users;
    }

    public function getAvailableUsers()
    {
      $blockers=$this->blocked()->pluck('id');
      $blocked=$this->blocks()->pluck('id');
      $id=$this->id;

      $ids=$blockers->concat($blocked)->concat([$id]);
      //$ids=$ids->toArray();
      $users=self::whereNotIn('id',$ids)->with(['pics'=> function($query){
        $query->where([
          ['type','profile'],
          ['status',1]
        ]);
      }])->get();
      return $users;

    }


    public function handleFriendship()
    {
      $requester=Auth::user();
      if(!$this->friendRequests()->where('id',$requester->id)->exists() &&
      !$this->sentRequests()->where('id',$requester->id)->exists())
      {
        $this->friendRequests()->attach($requester->id,['status'=>0]);
        event(new \App\Events\FriendRequested($requester,$this->id));
        return true;
      }
      return false;
    }

    public function handleBlocking($id)
    {
      if(!$this->blocks()->where('id',$id)->exists())
      {
        $this->unfriend(self::find($id));
        $this->following()->detach($id);
        $this->followers()->detach($id);
        $this->blocks()->attach($id);
      }

    }



    public function friends()
    {
      return $this->belongsToMany(self::class,'friendship','source_id','request_id')->withTimestamps();
    }

    public function acceptedFriends()
    {
      return $this->belongsToMany(self::class,'friendship','request_id','source_id')->withTimestamps();
    }

    public function allFriends()
    {
      return $this->friends()->select('name','id')->with(['pics'=> function($query){
        $query->where([['type','profile'],['status',1]]);}])->get()
            ->concat($this->acceptedFriends()->select('name','id')->with(['pics'=> function($query){
              $query->where([['type','profile'],['status',1]]);}])->get())
              ->sortByDesc(function($product){
        return $product->pivot->created_at;
      });

    }

    public function friendRequests()
    {
      return $this->belongsToMany(self::class,'friendRequests','source_id','request_id')->withPivot('status')->withTimestamps();
    }

    public function uncheckedFriendRequests()
    {
      return $this->belongsToMany(self::class,'friendRequests','source_id','request_id')->withTimestamps()
      ->wherePivot('status',0);
    }

    public function sentRequests()
    {
      return $this->belongsToMany(self::class,'friendRequests','request_id','source_id')->withPivot('status')->withTimestamps();
    }

    public function followers()
    {
      return $this->belongsToMany(self::class,'follows','source_id','follow_id')->withTimestamps();
    }

    public function following()
    {
      return $this->belongsToMany(self::class,'follows','follow_id','source_id')->withTimestamps();
    }

    public function blocks()
    {
      return $this->belongsToMany(self::class,'blocks','source_id','blocked_id')->withTimestamps();
    }

    public function blocked()
    {
      return $this->belongsToMany(self::class,'blocks','blocked_id','source_id')->withTimestamps();
    }

    public function getFriendshipStatus($id)
    {
      if($this->isAlreadyFriends($id))
        return "Friend";
      if($this->friendRequests()->where('id',$id)->exists())
        return "Requested";
      if($this->sentRequests()->where('id',$id)->exists())
        return "Accept";
      return "Add friend";



    }

    public function isAlreadyFriends($id)
    {
      return $this->friends()->where('id',$id)->exists() || $this->acceptedFriends()->where('id',$id)->exists();
    }

    public function acceptFriendRequest($user)
    {
      if(!$this->isAlreadyFriends($user->id))
      {
        $this->friendRequests()->detach($user->id);

        $this->friends()->attach($user->id);

        $posts=$this->posts()->where('privacy','friend')->pluck('id');
        $user->feeds()->attach($posts);

        $posts=$user->posts()->where('privacy','friend')->pluck('id');
        $this->feeds()->attach($posts);
      }
    }



    public function unfriend($friend)
    {

      $this->friends()->detach($friend->id);
      $this->acceptedFriends()->detach($friend->id);

      $posts=$this->posts()->where('privacy','friend')->pluck('id');
      $friend->feeds()->detach($posts);

      $posts=$friend->posts()->where('privacy','friend')->pluck('id');
      $this->feeds()->detach($posts);
    }

    public function rejectFriendRequest($id)
    {
      $this->friendRequests()->detach($id);
    }

    public function getFollowStatus()
    {
      if($this->followers()->where('id',Auth::id())->exists())
        return "Following";
      return "Follow";
    }

    public function handleFollowing()
    {

      $follower=Auth::user();
      if(!$this->followers()->where('id',$follower->id)->exists())
      {
        $this->followers()->attach($follower->id);
        $this->notify(new \App\Notifications\YouAreFollowed($follower,$this->id));

      }

    }

    public function likePost($post_id)
    {
      $this->likedPosts()->attach($post_id);
    }

    public function unlikePost($post_id)
    {
      $this->likedPosts()->detach($post_id);
    }

    public function favoritePost($post_id)
    {
      $this->favorites()->attach($post_id);
    }

    public function unfavoritePost($post_id)
    {
      $this->favorites()->detach($post_id);
    }

    public function likeComment($comment_id)
    {
      $this->likedComments()->attach($comment_id);
    }

    public function unlikeComment($comment_id)
    {
      $this->likedComments()->detach($comment_id);
    }

    public function likeReply($reply_id)
    {
      $this->likedReplies()->attach($reply_id);
    }

    public function unlikeReply($reply_id)
    {
      $this->likedReplies()->detach($reply_id);
    }


}
