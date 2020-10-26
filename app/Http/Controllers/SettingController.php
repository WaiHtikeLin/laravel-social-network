<?php

namespace App\Http\Controllers;

use App\Setting;
use Auth;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function __construct()
    {
      $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $settings=Auth::user()->settings;

      $friends_privacy=$settings->friends_privacy;
      $followers_privacy=$settings->followers_privacy;
      $following_privacy=$settings->following_privacy;


      return view('user.settings',['friends_privacy'=>$friends_privacy,
      'followers_privacy'=>$followers_privacy,'following_privacy'=>$following_privacy]);
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
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      Auth::user()->settings()->update([
        'friends_privacy'=>$request->input('friends_privacy'),
        'followers_privacy'=>$request->input('followers_privacy'),
        'following_privacy'=>$request->input('following_privacy'),

      ]);

      $request->session()->flash('status', 'Settings are successfully updated');

      return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
