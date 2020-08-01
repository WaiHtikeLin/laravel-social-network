<?php
namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Events\FriendRequested;
use App\Notifications\youAreFollowed;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use Auth;
class User extends Authenticatable
{
    use Notifiable,Billable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
      return ChatMessage::where([
        [
          'room_id',$roomId
        ],
        [
          'user_id', $this->id
        ]
      ])->exists();
    }
    public function information()
    {
      return $this->hasOne('App\UserInformation');
    }

    public function posts()
    {
      return $this->hasMany('App\Post');
    }

    public function likedPosts()
    {
      return $this->morphedByMany('App\Post', 'likeable')->withTimestamps();
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

    public function getFeeds()
    {
      return $this->feeds()->with('owner')->withCount('likes','comments','shares')->take(10)->get();
    }

    public static function getPopularUsers()
    {
      $id=Auth::id();
      $users=self::where('id','<>',$id)->get();

      return $users;
    }

    public function getAvailableUsers()
    {
      $blockers=$this->blocked()->select('id')->get()->modelKeys();
      $blocked=$this->blocks()->select('id')->get()->modelKeys();
      $id=$this->id;

      $ids=array_merge($blockers,$blocked,[$id]);
      //$ids=$ids->toArray();
      $users=self::whereNotIn('id',$ids)->get();
      return $users;

    }
    public function handleFriendship()
    {
      $requester=Auth::user();
      if($this->friendRequests()->where('id',$requester->id)->exists())
      {
        $this->friendRequests()->detach($requester->id);

        return "Add Friend";

      }
      $this->friendRequests()->attach($requester->id);
      event(new FriendRequested($requester,$this->id));
      return "Requested";

    }

    public function handleBlocking($id)
    {
      if(!$this->blocks()->where('id',$id)->exists())
        $this->blocks()->attach($id);
    }



    public function friends()
    {
      return $this->belongsToMany(self::class,'friendship','source_id','request_id')->withTimestamps();
    }

    public function acceptedFriends()
    {
      return $this->belongsToMany(self::class,'friendship','request_id','source_id')->withTimestamps();
    }

    public function friendRequests()
    {
      return $this->belongsToMany(self::class,'friendRequests','source_id','request_id')->withTimestamps();
    }

    public function sentRequests()
    {
      return $this->belongsToMany(self::class,'friendRequests','request_id','source_id')->withTimestamps();
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

    public function getFriendshipStatus()
    { $id=Auth::id();
      if($this->isAlreadyFriends($id))
        return "Friend";
      if($this->friendRequests()->where('id',$id)->exists())
        return "Requested";
      if($this->sentRequests()->where('id',$id)->exists())
        return "Accept Request";
      return "Add Friend";



    }

    private function isAlreadyFriends($id)
    {
      return $this->friends()->where('id',$id)->exists() || $this->acceptedFriends()->where('id',$id)->exists();
    }

    public function acceptFriendRequest($id)
    {
      if($this->isAlreadyFriends($id))
        return true;
      $this->friends()->attach($id);
      $user=self::findOrFail($id);
      return true;
      $user->notify(new Notifications\RequestAccepted($this));
    }

    public function unfriend($id)
    {
      $this->friends()->detach($id);
      $this->acceptedFriends()->detach($id);
      return true;
    }

    public function rejectFriendRequest($id)
    {
      $this->friendRequests()->detach($id);
      return true;
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
      if($this->followers()->where('id',$follower->id)->exists())
      {
        $this->followers()->detach($follower->id);

        return "Follow";

      }
      $this->followers()->attach($follower->id);
      $this->notify(new youAreFollowed($follower));
      return "Following";

    }

}
