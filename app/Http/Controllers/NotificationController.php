<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth");
  }

  public function index()
  {
    return view('menu.notifications');
  }

  public function getNotifications($page=null)
  { $user=Auth::user();
    $user->unreadNotifications()->update(['read_at'=> now()]);

    if($page)
      return $user->notifications()->offset($page*10)->limit(10)->get();
    else
      return $user->notifications()->limit(10)->get();
  }

  public function getUnreadNotifications()
  { $user=Auth::user();
    $all=$user->unreadNotifications()->get();
    $all->markAsRead();
    return $all;
  }

  public function checkNoti($id)
  { $user=Auth::user();
    $noti=$user->notifications()->where('id',$id)->first();
    $data=$noti->data;

    if($data['status']!='checked')
    {
      $data['status']='checked';
      $noti->data=$data;
      $noti->save();
    }

  }



  public function countUnreadNoti()
  {
    return [Auth::user()->unreadNotifications()->count()];
  }



  public function remove($id)
  {
    return Auth::user()->notifications()->where('id',$id)->delete();
  }
}
