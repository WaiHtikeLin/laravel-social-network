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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { $user=Auth::user();
      //$posts=Post::getfollowedPostsFrom(Auth::id());
      //return view("post.allposts",['posts' => $posts]);
      $feeds=$user->getFeeds();
      return view('post.feeds',['feeds'=>$feeds]);
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
    { $post=Post::create([
        'texts' => $request->input('texts'),
        'privacy' => $request->input('privacy'),
        'user_id' => Auth::id()
      ]);
      $post->broadcast();

      return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $post=Post::where('id',$id)->withCount('likes','comments','shares')->with('owner')->get();
      return $post;
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


      if($post->privacy==$request->input('privacy'))
          $post->update([
            'texts' => $request->input('texts')]);
      else
        {
          $post->update([
            'texts' => $request->input('texts'),
            'privacy' => $request->input('privacy')
          ]);

          $post->broadcast();
        }

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Post::destroy($id);
    }

    public function toggleLike(Post $post)
    {
      $id=Auth::id();
      return $post->likes()->where('id',$id)->exists() ? $post->likes()->detach($id) : $post->likes()->attach($id);
    }
}
