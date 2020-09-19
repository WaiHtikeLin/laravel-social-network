<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use Auth;

class SearchController extends Controller
{

  public function __construct()
  {
    $this->middleware("auth");
  }

  public function index(Request $request)
  {
    $value=$request->input('search');
    if(!$value)
      return back();
    $user=Auth::user();



    return view('menu.search',['users'=>$this->getSearchedUsers($value,$user),'value'=>$value]);

  }

  private function getSearchedUsers($value,$user)
  {
    $blockers=$user->blocked()->pluck('id');
    $blocked=$user->blocks()->pluck('id');

    $ids=$blockers->concat($blocked);
    //$ids=$ids->toArray();
    return User::whereNotIn('id',$ids)->where('name','like',"%$value%")
            ->with([
              'pics'=>function($query){
                $query->where([
                  ['type','profile'],
                  ['status',1]
                ]);
              }
              ])->get();
  }

  public function getSearchedPosts($value)
  {
    $user=Auth::user();
    $id=$user->id;

    $blockers=$user->blocked()->pluck('id');
    $blocked=$user->blocks()->pluck('id');

    $ids=$blockers->concat($blocked);

    $friend_ids=$user->friends()->pluck('id')->concat($user->acceptedFriends()->pluck('id'))->concat([$user->id]);

    return Post::whereNotIn('user_id',$ids)->where(function($query) use ($friend_ids){
      $query->where('privacy','public')
            ->orWhereIn('user_id',$friend_ids);
    })->where('texts','like',"%$value%")->with(['owner.pics'=> function($query){
        $query->where([['type','profile'],['status',1]]);
      },'reactions'=> function($query) use ($id){
        $query->where('id',$id);
      }])->attributes($id)->latest()->get();
  }
}
