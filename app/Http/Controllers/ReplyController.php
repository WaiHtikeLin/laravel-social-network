<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Comment;
use App\Post;
use Auth;
use Illuminate\Http\Request;

class ReplyController extends Controller
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


    public function index(Post $post,Comment $comment)
    {
      $user=Auth::user();
      $id=$user->id;
      $ids=$user->blocked()->pluck('id')->concat($user->blocks()->pluck('id'))->concat([$id]);

      $replies=$comment->replies()->withCount(['likes','likes as like_status'=>function($query) use ($id){
        $query->where('id',$id);
      }])->with(['owner.pics'=> function($query){
        $query->where([['type','profile'],['status',1]]);
      }])->whereNotIn('user_id',$ids)->latest()->get();

      if($user->replies()->where('comment_id',$comment->id)->exists())
      {
        $myreplies=$comment->replies()->withCount(['likes','likes as like_status'=>function($query) use ($id){
          $query->where('id',$id);}])->with(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        }])->where('user_id',$id)->latest()->get();

        return $myreplies->concat($replies);
    }
    return $replies;

  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('reply.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Post $post,Comment $comment)
    {
      $user=$request->user();
      $user->load(['pics'=> function($query){
      return $query->where([['type','profile'],['status',1]]);
      }]);

      $reply=Reply::create([
        'texts'=> $request->input('texts'),
        'comment_id' => $comment->id,
        'user_id' => $user->id

      ]);

      if($user->id!=$comment->user_id)
        $comment->owner->notify(new \App\Notifications\YourCommentIsReplied($user,$post->id));

      return ['id'=>$reply->id,'comment_id'=>$reply->comment_id,
      'user_id'=>$reply->user_id, 'texts'=> $reply->texts,'created_at'=>$reply->created_at, 'likes_count'=>0, 'owner' => $user, 'count' => $comment->replies()->count()];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      return Reply::where('id',$id)->withCount('likes')->get();
    }

    public function get(Reply $reply)
    {
      $this->authorize('update',$reply);
      return $reply;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
      return view('reply.edit',['reply'=>$reply]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {

      $user=Auth::user();
      $id=$user->id;

      if($user->can('update',$reply))
      {
        $reply->update([
          'texts' => $request->input('texts')
        ]);

        return $reply->loadCount(['likes','likes as like_status'=>function($query) use ($id){
          $query->where('id',$id);}])->load(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        }]);
      }

      return false;

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
      $this->authorize('update',$reply);
      $reply->delete();

      return [$reply->comment->replies()->count()];
    }

    public function toggleLike(Post $post, Comment $comment, Reply $reply)
    {
      $user=Auth::user();
      if($reply->likes()->where('id',$user->id)->exists())
        $user->unlikeReply($reply->id);
      else {
        $user->likeReply($reply->id);

        if($user->id!=$reply->user_id)
          $reply->owner->notify(new \App\Notifications\YourReplyIsLiked($user,$post->id));
      }
      return ['count' => $reply->likes()->count()];
    }

}
