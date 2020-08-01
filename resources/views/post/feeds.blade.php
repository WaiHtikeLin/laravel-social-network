@extends('layouts.master')

@section('main')
  @foreach($feeds as $feed)
    <ul>
      <li>{{ $feed->owner->name }}</li>
      <li>{{ $feed->privacy }}</li>
      <li>{{ $feed->texts }}</li>

      <li><button type="button" class="like-button" data-feed-id={{ $feed->id }}>Like</button>
          <button type="button" class="comment-button" data-feed-id={{ $feed->id }}>Comment</button>
          <button type="button" class="share-button" data-feed-id={{ $feed->id }}>Share</button>
      </li>

    </ul>
  @endforeach

<script type="text/javascript">
  document.querySelector('.like-button').onclick = async () => {
    let id= this.data-feed-id;
    let response= await fetch('{{url('')}}/toggle/like/to/'+id, {

    });
  };
</script>
@endsection
