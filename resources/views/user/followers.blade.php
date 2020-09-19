@extends('layouts.master')

@section('main')

<div class="col-12 col-md-6">
  <h3>Followers</h3>
  <div class="clean-white h-100 pl-3 pt-3">
    @foreach($followers as $follower)
    <p><a href="{{url("/user/profile/".$follower->id)}}">
        <img src="{{asset('/storage/profile_pics/'.$follower->pics[0]->name)}}" alt="" class="rounded-circle mr-2"
        style="height:3em;width:3em">
          <strong>{{$follower->name}}</strong>

      </a>
    </p>

    @endforeach
  </div>
</div>



@endsection
