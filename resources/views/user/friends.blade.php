@extends('layouts.master')

@section('main')

<div class="col-12 col-md-6">
  <h3>Friends</h3>
  <div class="clean-white h-100 pl-3 pt-3">
    @foreach($friends as $friend)
    <p><a href="{{url("/user/profile/".$friend->id)}}">
        <img src="{{asset('/storage/profile_pics/'.$friend->pics[0]->name)}}" alt="" class="rounded-circle mr-2"
        style="height:3em;width:3em">
          <strong>{{$friend->name}}</strong>

      </a>
    </p>

    @endforeach
  </div>

</div>



@endsection
