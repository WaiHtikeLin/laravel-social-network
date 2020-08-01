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

    public function showFollowers()
    {
      return view('user.followers',['followers'=>Auth::user()->followers]);
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

    public function handleFriendship(User $user)
    {
      if($this->isNotUserAvailable($user))
        return response()->view('user.errors.profileerror',[],403);
      $response=$user->handleFriendship();
      return $response;
    }

    public function acceptFriendRequest($id)
    {
      $user=Auth::user();
      if($user->acceptFriendRequest($id))
        return true;
    }

    public function rejectFriendRequest($id)
    {
      $user=Auth::user();
      if($user->rejectFriendRequest($id))
        return true;
    }

    public function unfriend()
    {
      $user=Auth::user();
      if($user->unfriend())
        return true;
    }

    public function handleFollowing(User $user)
    {
      if($this->isNotUserAvailable($user))
        return response()->view('user.errors.profileerror',[],403);
      $response=$user->handleFollowing();
      return $response;
    }

    public function handleBlocking($id)
    {
      $user=Auth::user();
      $user->handleBlocking($id);
      return redirect('/users');

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
      if($this->isNotUserAvailable($user))
        return response()->view('user.errors.profileerror',[],403);
      $friendship_status=$user->getFriendshipStatus();
      $follow_status=$user->getFollowStatus();
      return view('user.profile',[
        'user'=>$user,
        'friendship_status'=>$friendship_status,
        'follow_status'=>$follow_status]);
      //return $user;
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
