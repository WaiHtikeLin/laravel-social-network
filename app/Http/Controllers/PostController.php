<?php

namespace App\Http\Controllers;
use Auth;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct()
    {
      $this->middleware("auth");
      $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type="feeds")
    {
      $user=Auth::user();

      $class="nav-item-active";
      if($type=='privates' || $type=='favorites')
        $class="side-nav-active";

      if($type=="privates")
        return view('menu.feeds',['user'=>$user,'url'=>url("/get/privates"), 'mine'=>true,
        'profile_pic'=>$user->getProfilePic(), 'type'=> $type, 'class'=>$class]);
      else
        return view('menu.feeds',['user'=>Auth::user(),'url'=>url("/get/$type"),'type'=>$type, 'class'=>$class]);

    }

    public function getPrivates($page)
    { $user=Auth::user();
      $id=$user->id;
      return $user->posts()->with(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        },'reactions'=> function($query) use ($id){
          $query->where('id',$id);
        }])->attributes($user->id)->where('privacy', 'onlyme')->latest()->offset($page*10)->limit(10)->get();
    }

    public function getFavorites($page)
    {
      $user=Auth::user();
      $id=$user->id;
      $ids=$user->blocked()->pluck('id')->concat($user->blocks()->pluck('id'));
      $friendIds=$user->friends()->pluck('id')->concat($user->acceptedFriends()->pluck('id'));
      $post_ids=$user->favorites()->where('privacy','friend')->whereNotIn('posts.user_id',$friendIds)->pluck('id');

      return $user->favorites()->with(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        },'reactions'=> function($query) use ($id){
          $query->where('id',$id);
        }])->attributes($user->id)->whereIn('privacy', ['friend','public'])->whereNotIn('posts.user_id',$ids)
      ->whereNotIn('id',$post_ids)->latest()->offset($page*10)->limit(10)->get();
    }

    public function getFeeds($page)
    {
      $user=Auth::user();
      $id=$user->id;

      $ids=$user->following()->pluck('id');
      $post_ids=$user->feeds()->whereIn('posts.user_id',$ids)->pluck('id');
        //$posts=Post::getfollowedPostsFrom(Auth::id());
        //return view("post.allposts",['posts' => $posts]);
      return Post::with(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        },'reactions'=> function($query) use ($id){
          $query->where('id',$id);
        }])->attributes($id)->whereIn('user_id',$ids)->Where(function($query) use ($post_ids){
          $query->where('privacy','public')
                ->orWhereIn('id',$post_ids);
        })
        ->latest()->offset($page*10)->limit(10)->get();
    }

    public function getShares(Post $post,$page)
    {
      $user=Auth::user();
      $id=$user->id;
      $ids=$user->blocked()->pluck('id')->concat($user->blocks()->pluck('id'))->concat([$id]);

      $sharers=$post->shares()->select('user_id')->with(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        }])->whereIn('posts.privacy',['friend','public'])->whereNotIn('posts.user_id',$ids)->groupBy('user_id')
        ->offset($page*10)->limit(10)->get();

      // $sharers=$post->sharesViewable()->select('id','name')
      // ->whereNotIn('id',$ids)
      // ->with(['pics'=>function($query){
      //   $query->where([['type','profile'],['status',1]])->limit(1);
      // }])->get();

      if($page==0)
      {
        if($post->shares()->where('user_id',$id)->exists()){

          $myshare=$post->shares()->select('user_id')->with(['owner.pics'=>function($query){
            $query->where([['type','profile'],['status',1]]);
          }])->where('user_id',$id)->first();


          $myshare->owner->name.=' (You)';

          return collect([$myshare])->concat($sharers);
        }
      }

      return $sharers;
    }

    public function getLikes(Post $post)
    {
      $user=Auth::user();
      $id=$user->id;
      $ids=$user->blocked()->pluck('id')->concat($user->blocks()->pluck('id'))->concat([$id]);

      $likers=$post->likes()->select('id','name')
      ->whereNotIn('id',$ids)
      ->with(['pics'=>function($query){
        $query->where([['type','profile'],['status',1]]);
      }])->get();

      if($user->likedPosts()->where('id',$post->id)->exists())
      {
        $user=$post->likes()->select('id','name')
        ->where('id',$id)
        ->with(['pics'=>function($query){
          $query->where([['type','profile'],['status',1]]);
        }])->first();
        $user->name.=' (You)';

        return collect([$user])->concat($likers);
      }

      return $likers;
    }

    public function getReactions(Post $post,$page)
    {
      $user=Auth::user();
      $id=$user->id;
      $ids=$user->blocked()->pluck('id')->concat($user->blocks()->pluck('id'))->concat([$id]);

      $reactions=$post->reactions()->whereNotIn('id',$ids)
      ->with(['pics'=>function($query){
        $query->where([['type','profile'],['status',1]]);
      }])->offset($page*10)->limit(10)->get();

      if($page==0)
      {
        if($user->reactedPosts()->where('id',$post->id)->exists())
        {
          $user=$post->reactions()->where('id',$id)
          ->with(['pics'=>function($query){
            $query->where([['type','profile'],['status',1]]);
          }])->first();
          $user->name.=' (You)';

          return collect([$user])->concat($reactions);
        }
      }

      return $reactions;
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->storePost($request);
      return redirect('/myprofile');
    }

    private function storePost($request,$share_id=null,$copy_id=null)
    {
      $user=Auth::user();

      if($copy_id)
        $copyable=1;
      else
        $copyable=$request->boolean('copyable');

      $post=Post::create([
          'texts' => $request->input('texts'),
          'privacy' => $request->input('privacy'),
          'user_id' => $user->id,
          'shareable' => $request->boolean('shareable'),
          'copyable' => $copyable,
          'share_id' => $share_id,
          'copy_id'=> $copy_id
        ]);

      $this->broadcast($post,$user);
    }

    private function broadcast($post,$user)
    { $privacy=$post->privacy;
      if($privacy=='friend')
      {
        $ids=$user->friends()->pluck('id')
        ->concat($user->acceptedFriends()->pluck('id'));

        $post->users()->sync($ids);
      }
      else if($privacy=='public' || $privacy=='onlyme')
      {

        $ids=$post->users()->pluck('id');
        if($ids)
          $post->users()->detach($ids);
      }
    }

    public function storeSharedPost(Request $request,Post $post)
    {

      if($post->shareable)
      {
        $this->storePost($request,$post->id);
        //$post->shares()->attach(Auth::id(),['type'=>$request->input('privacy')]);
        $user=$request->user();

        if($user->id!=$post->user_id)
          $post->owner->notify(new \App\Notifications\YourPostIsShared($user,$post->id));
        return redirect('/myprofile');
      }
      return ['error'];

    }

    public function storeCopiedPost(Request $request,Post $post)
    {

      if($post->copyable){
        $this->storePost($request,null,$post->id);
        return ['ok'];
      }

      return ['error'];
      // $post->shares()->attach(Auth::id(),['type'=>$request->input('privacy')]);
      // $post->owner->notify(new \App\Notifications\YourPostIsShared($request->user(),$post->id));
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function getPost(Post $post)
    {
      $id=Auth::id();
        $post=$post->load(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        },'reactions'=> function($query) use ($id){
          $query->where('id',$id);
        }])->loadCount(['reactions','reactions as reaction_status'=> function($query) use ($id){
            $query->where('id',$id);
          },'comments','shares']);
        return $post;
    }


    public function show(Post $post)
    { $user=Auth::user();

      if($user->can('update', $post))
        return view('post.singlepost',['id'=>$post->id,'mypost'=>true,'user'=>$user]);
      return view('post.singlepost',['id'=>$post->id]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
      return view('post.edit',['post'=>$post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $user=Auth::user();

        if($post->copy_id)
          $copyable=1;
        else
          $copyable=$request->boolean('copyable');

        $privacy=$request->input('privacy');

        if($post->privacy!=$privacy)
          {$post->update([
            'texts' => $request->input('texts'),
            'privacy' => $privacy,
            'shareable' => $request->boolean('shareable'),
            'copyable' => $copyable
          ]);
          $this->broadcast($post,$user);
        }

        else

        $post->update([
          'texts' => $request->input('texts'),
          'shareable' => $request->boolean('shareable'),
          'copyable' => $copyable
        ]);

        $id=$user->id;

        $post=$post->load(['owner.pics'=> function($query){
          $query->where([['type','profile'],['status',1]]);
        }, 'reactions'=> function($query) use ($id){
          $query->where('id',$id);
        }])->loadCount(['reactions','reactions as reaction_status'=> function($query) use ($id){
            $query->where('id',$id);
          },'comments','shares']);

          return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
      $post->delete();
    }

    public function toggleLike(Post $post)
    {
      $user=Auth::user();
      if($post->likes()->where('id',$user->id)->exists())
      {
        $user->unlikePost($post->id);
        //delete notification
      }
      else {
        $user->likePost($post->id);
        if($user->id!=$post->user_id)
          $post->owner->notify(new \App\Notifications\YourPostIsLiked($user,$post->id));
      }

      return ['count' => $post->likes()->count()];
    }

    public function addReaction($reaction, Post $post, $form=null)
    {

      if($form)
      {
        if(strlen($reaction)>7)
          return ['error_length'];

        $c=mb_ord($reaction,'utf-8');
        if($c<127456)
          return ['error_emoji'];
      }


      $user=Auth::user();
      $id=$user->id;
      if($post->reactions()->where('id',$id)->exists())
        $post->reactions()->updateExistingPivot($id, ['emoji'=>$reaction]);
      else
      {
        $post->reactions()->attach($id, ['emoji'=>$reaction]);

        if($user->id!=$post->user_id)
          $post->owner->notify(new \App\Notifications\YourPostIsReacted($user,$post->id,$reaction));

      }


      return ['reaction'=> $reaction,'count' => $post->reactions()->count()];
    }

    public function removeReaction(Post $post)
    {
      $post->reactions()->detach(Auth::id());

      return [$post->reactions()->count()];
    }

    public function toggleFavorite(Post $post)
    {
      $user=Auth::user();
      if($user->favorites()->where('id',$post->id)->exists())
      {
        $user->unfavoritePost($post->id);
        //delete notification
      }
      else
      {
        $user->favoritePost($post->id);
      }

    }
}
