<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
  public function getNotifications()
  { $user=Auth::user();
    return $user->notifications()->take(10)->get();
  }

  public function getUnreadNotifications()
  { $user=Auth::user();
    return $user->unreadNotifications;
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
    return true;

  }

  public function readNoti()
  {
    $user=Auth::user();
    $user->unreadNotifications()->update(['read_at' => now()]);
    return true;
  }

  public function countUnreadNoti()
  {
    return Auth::user()->unreadNotifications()->count();
  }
}
