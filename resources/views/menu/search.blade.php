@extends('layouts.master')

@section('main')
<div class="col-12 col-md-6">
  <ul class="nav nav-tabs d-flex justify-content-end" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">Users</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="posts-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="false">Posts</a>
    </li>
 </ul>

 <div class="tab-content">
  <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">

    <div class="mb-2">
      <h3 class="d-inline mr-5">Users</h3>
      <a href="{{url('/users')}}">Show all available users</a>
    </div>

    <div class="clean-white pl-3 py-3">
      @if(count($users)>0)
        @foreach($users as $user)
        <p><a href="{{url("/user/profile/".$user->id)}}">
            <img src="{{asset('/storage/profile_pics/'.$user->pics[0]->name)}}" alt="" class="rounded-circle mr-2"
            style="height:3em;width:3em">
              <strong>{{$user->name}}</strong>

          </a>
        </p>
        @endforeach

      @else

        <p class="text-center">No users</p>

      @endif

    </div>
  </div>
  <div class="tab-pane fade" id="posts" role="tabpanel" aria-labelledby="posts-tab">
    <h3>Posts</h3>
    <div class="clean-white px-3" id="searched_posts">

    </div>
  </div>
</div>

</div>
@include('post.posts')

<script type="text/javascript">
function showPosts(){

let feeds_container=document.querySelector('#searched_posts');

fetch('{{"/search/posts/$value"}}').then(response=>{
  if(response.ok)
   return response.json();
  throw new Error();
}).then(posts=>{

  if(posts.length>0)
    posts.forEach(function(post){

      let divfeed=document.createElement('div');
      divfeed.id=`post_${feed.id}`;
      divfeed.append(renderFeed(post));
      feeds_container.append(divfeed);

    });
  else
    feeds_container.innerHTML=`<p class="text-center">No posts</p>`;


});

}


showPosts();
</script>

@endsection
