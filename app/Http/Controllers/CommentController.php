<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
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



    public function index(Post $post,$page)
    {
      $user=Auth::user();
      $id=$user->id;
      $ids=$user->blocked()->pluck('id')->concat($user->blocks()->pluck('id'))->concat([$id]);

      $comments=$post->comments()->withCount(['likers','likers as like_status'=>function($query) use ($id){
        $query->where('id',$id);
      },'replies'])
      ->with(['owner.pics'=> function($query){
        $query->where([['type','profile'],['status',1]]);
      }])->whereNotIn('user_id',$ids)->latest()->offset($page*10)->limit(10)->get();

      if($page==0)
      {
        if($user->comments()->where('post_id',$post->id)->exists())
        {
          $mycomments=$post->comments()->withCount(['likers','likers as like_status'=>function($query) use ($id){
            $query->where('id',$id);
          },'replies'])
          ->with(['owner.pics'=> function($query){
            $query->where([['type','profile'],['status',1]]);
          }])->where('user_id',$id)->latest()->get();

          return $mycomments->concat($comments);
        }
      }


      return $comments;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('comment.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Post $post)
    { $user=$request->user();
      $user->load(['pics'=> function($query){
      return $query->where([['type','profile'],['status',1]]);
    }]);
      $comment=Comment::create([
        'texts'=>$request->input('comment'),
        'post_id'=> $post->id,
        'user_id' => $user->id
      ]);

      if($user->id!=$post->user_id)
        $post->owner->notify(new \App\Notifications\YourPostIsCommented($user,$comment->post_id));

      return ['id'=>$comment->id, 'texts'=> $comment->texts,'post_id'=>$comment->post_id,
      'user_id'=>$comment->user_id, 'created_at'=>$comment->created_at,
      'likers_count'=>0, 'replies_count'=>0, 'owner' => $user, 'count' => $post->comments()->count()];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {

    }

    public function get(Comment $comment)
    {
      $this->authorize('update',$comment);
      return $comment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
      return view('comment.edit',['comment'=>$comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
      $this->authorize('update',$comment);
        $comment->update([
        'texts' => $request->input('texts')

      ]);

      $id=Auth::id();

      return $comment->loadCount(['likers','likers as like_status'=>function($query) use ($id){
        $query->where('id',$id);
      },'replies'])
      ->load(['owner.pics'=> function($query){
        $query->where([['type','profile'],['status',1]]); }]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
      $this->authorize('update', $comment);
      $comment->delete();

      return [$comment->post->comments()->count()];
    }

    public function toggleLike(Comment $comment)
    {
      $user=Auth::user();
      if($comment->likers()->where('id',$user->id)->exists())
        $user->unlikeComment($comment->id);
      else {
        $user->likeComment($comment->id);

        if($user->id!=$comment->user_id)
          $comment->owner->notify(new \App\Notifications\YourCommentIsLiked($user,$comment->post_id));
      }
      return ['count' => $comment->likers()->count()];
    }
}
