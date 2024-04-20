<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
      $this->middleware("auth");
    }

    public function options()
    {
      return view('menu.options');
    }

    public function testnoti() {
      $auth_user=Auth::user();
      $auth_user->notify(new \App\Notifications\MessageSent($auth_user));
    }

    public function changeName(Request $request)
    {
      $request->validate([
        'name' => 'required'
        ]);

        $user=Auth::user();
        $user->name=$request->input('name');
        $user->save();

        return back();
    }

    public function showFriends(User $user)
    {
      $I=Auth::user();
      if(($I->can('view',$user)
      && $I->canViewInfo($user->settings->friends_privacy,$user->id)) || ($I->id==$user->id))
        return view('user.friends',['friends'=>$user->allFriends()]);
      return ['error'];
    }

    public function showFollowers(User $user)
    {
      $I=Auth::user();
      if(($I->can('view',$user)
      && $I->canViewInfo($user->settings->followers_privacy,$user->id)) || ($I->id==$user->id))
      {
        $ids=$user->friends()->pluck('id')->concat($user->acceptedFriends()->pluck('id'));

        return view('user.followers',['followers'=>$user->followers()
        ->whereNotIn('id',$ids)->with(['pics'=> function($query){
        $query->where([['type','profile'],['status',1]]);}])->get()]);
      }

      return ['error'];
    }

    public function showFollowing(User $user)
    {
      $I=Auth::user();
      if(($I->can('view',$user)
      && $I->canViewInfo($user->settings->following_privacy,$user->id)) || ($I->id==$user->id))
      {
        $ids=$user->friends()->pluck('id')->concat($user->acceptedFriends()->pluck('id'));

        return view('user.following',['following'=>$user->following()
        ->whereNotIn('id',$ids)->with(['pics'=> function($query){
        $query->where([['type','profile'],['status',1]]);}])->get()]);
      }

      return ['error'];
    }

    public function showBlocked()
    {
      return view('user.blocked',['blocked'=>Auth::user()->blocks()->with(['pics'=>function($query){
        $query->where([
          ['type','profile'],
          ['status',1]
        ]);
      }])->get()]);
    }

    public function unblock($id)
    {
      Auth::user()->blocks()->detach($id);
      return back();
    }


    public function isNotUserAvailable($user)
    {
      try {
        $this->authorize('view', $user);
      } catch (\Throwable $e) {
        //report($e);
        return true;
      }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $user=Auth::user();
        $users=$user->getAvailableUsers();
        return view('user.popularusers',['users'=> $users]);
        //return $users;
    }

    public function showRequests()
    {
      return view('menu.requests');
    }

    public function showSentRequests()
    {
      return view('user.sentrequests',['users'=>Auth::user()->sentRequests()
      ->with(['pics'=> function($query){
      $query->where([['type','profile'],['status',1]]);}])->get()]);
    }

    public function countUncheckedRequests()
    {
      return [Auth::user()->uncheckedFriendRequests()->count()];
    }

    public function getRequests($page=null)
    {
      $user=Auth::user();
      $ids=$user->uncheckedFriendRequests()->pluck('id');
      $user->friendRequests()->updateExistingPivot($ids, ['status'=>1]);

      if($page)
        return $user->friendRequests()->with(['pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);}])->offset($page*10)->limit(10)->get();
      else
      return $user->friendRequests()->with(['pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);}])->take(5)->get();
    }



    public function getUnreadRequests()
    { $user=Auth::user();
      $requests=$user->uncheckedFriendRequests;
      $ids=$requests->modelKeys();
      $user->friendRequests()->updateExistingPivot($ids, ['status'=>1]);
      return $requests;
    }


    public function handleFriendship(User $user)
    {
      if($this->isNotUserAvailable($user))
        return response()->view('user.errors.profileerror',[],403);
      if($user->handleFriendship())
        return ['ok'];
      else
        return ['error'];
    }

    public function acceptFriendRequest(User $user)
    {

      $auth_user=Auth::user();
      $auth_user->acceptFriendRequest($user);
      $user->notify(new \App\Notifications\RequestAccepted($auth_user));

    }

    public function rejectFriendRequest(User $user)
    {
      Auth::user()->rejectFriendRequest($user->id);
    }

    public function unfriend(User $friend)
    {
      $user=Auth::user();
      $user->unfriend($friend);

    }

    public function handleFollowing(User $user)
    {
      if($this->isNotUserAvailable($user))
        return response()->view('user.errors.profileerror',[],403);
      $user->handleFollowing();
    }

    public function handleBlocking($id)
    {
      $user=Auth::user();
      $user->handleBlocking($id);
      return redirect('/');

    }



    public function cancelMyRequest($id)
    {
      Auth::user()->sentRequests()->detach($id);
    }

    public function cancelMyFollow($id)
    {
      Auth::user()->following()->detach($id);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
      $mine=false;
      $me=Auth::user();
      if($user->id==$me->id)
        $mine=true;
      else
        if($this->isNotUserAvailable($user))
          return response()->view('user.errors.profileerror',[],403);

      $ids=$user->friends()->pluck('id')->concat($user->acceptedFriends()->pluck('id'));

      $user=$user->loadCount(['friends','acceptedFriends','followers'=>function($query) use ($ids){
        $query->whereNotIn('id',$ids);
      },'following'=>function($query) use ($ids){
        $query->whereNotIn('id',$ids);
      },'sentRequests']);


      $profile_pic=$user->getProfilePic();
      $friendship_status=$user->getFriendshipStatus($me->id);
      $follow_status=$user->getFollowStatus();

      $info=$user->information;
      $settings=$user->settings;
      $edu=$info->education? $info->education[0]: null;
      $work=$info->work? $info->work[0] : null;
      $address=$info->address? $info->address[0] : null;
      $email=$info->emails? $info->emails[0] : null;
      $phone=$info->ph_numbers? $info->ph_numbers[0] : null;
      $site=$info->websites? $info->websites[0] : null;
      $about=$info->about;
      $bio=$info->bio;

      $data=[
        'profile'=>true,
        'user'=>$user,
        'me'=>$me,
        'about'=>$about,
        'edu'=>$edu,
        'work'=>$work,
        'address'=>$address,
        'email'=>$email,
        'phone'=>$phone,
        'site'=>$site,
        'bio'=>$bio,
        'info'=>$info,
        'settings'=>$settings,
        'profile_pic'=>$profile_pic,
        'friendship_status'=>$friendship_status,
        'follow_status'=>$follow_status
      ];

      if($mine)
      $data['mine']=$mine;


      return view('menu.profile',$data);
      //return $user;
    }

    public function getViewablePosts(User $user,$page)
    {
      $id=Auth::id();
      if($user->id==$id)
        return $user->posts()->with(['reactions'=> function($query) use ($id){
          $query->where('id',$id);
        }])->attributes($id)->latest()->offset($page*10)->limit(10)->get();
      else
        {
          if($user->isAlreadyFriends($id))
            return $user->posts()->with(['reactions'=> function($query) use ($id){
              $query->where('id',$id);
            }])->attributes($id)->whereIn('privacy',['friend','public'])->latest()
            ->offset($page*10)->limit(10)->get();
        }

      return $user->posts()->with(['reactions'=> function($query) use ($id){
        $query->where('id',$id);
      }])->attributes($id)->where('privacy','public')->latest()->offset($page*10)->limit(10)->get();
    }

    public function showProfile()
    {
      return $this->show(Auth::user());
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($field='all')
    { $user=Auth::user();
      $information=$user->information;
      return view('user.editprofile',['info'=>$information,'user'=>$user,'field'=>$field]);
      //return $information;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    public function storeToken(Request $request) {
      $request->user()->update([
        'fcm_token' => $request->token
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
