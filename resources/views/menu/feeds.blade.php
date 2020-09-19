@extends('layouts.master')

@section("link-to-$type")
{{$class}}
@endsection

@section('main')

<div class="col-12 col-md-6 feeds">
</div>
  @include('post.posts')

<script type="text/javascript">
function showFeeds(page=0){

  if(page>0)
    document.querySelector("#load-more-posts").remove();

  let feeds_container=document.querySelector('.feeds');

  fetch('{{$url}}/'+page).then(response=>{
    if(response.ok)
     return response.json();
    throw new Error();
  }).then(feeds=>{

    if(page<1 && feeds.length==0)
    {

      @if($type=='feeds')

      feeds_container.innerHTML=`
      <div class="d-flex vh-100">
      <p class="text-center text-muted align-self-center w-100" style="font-size:2em">
        Start follow <a href="{{url('/users')}}">users</a> to see their posts
      </p>
      </div>`;

      @elseif($type=='privates')

      feeds_container.innerHTML=`
      <div class="d-flex vh-100">
      <p class="text-center text-muted align-self-center w-100" style="font-size:2em">
        No private posts</p>
      </div>`;

      @else

      feeds_container.innerHTML=`
      <div class="d-flex vh-100">
      <p class="text-center text-muted align-self-center w-100" style="font-size:2em">
        No favorite posts</p>
      </div>`;

      @endif

      return false;
    }
    feeds.forEach(function(feed){

      let divfeed=document.createElement('div');
      divfeed.id=`feed_${feed.id}`;
      divfeed.append(renderFeed(feed));
      feeds_container.append(divfeed);

    });

    if(feeds.length==10)
      feeds_container.append(createLoadMore(page+1));
});

}

function createLoadMore(page)
{
  let wrap=document.createElement('p');
  wrap.className="text-center";
  wrap.id="load-more-posts";
  wrap.innerHTML=`<button class="btn btn-light" onclick="showFeeds(${page})">Load more</button>`;

  return wrap;
}

showFeeds();
</script>
@endsection
