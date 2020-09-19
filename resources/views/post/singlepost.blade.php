@extends('layouts.master')

@section('link-to-home')
nav-item-active
@endsection

@section('main')

<div class="col-12 col-md-6" id="feed_{{$id}}">
</div>
@include('post.posts')
<script type="text/javascript">
  fetch('{{url("/get/post/$id")}}').then(response=>{
    if(response.ok)
      return response.json();
    throw new Error();
  }).then(feed=>{
    document.querySelector('#feed_{{$id}}').append(renderFeed(feed));
  });
</script>

@endsection
