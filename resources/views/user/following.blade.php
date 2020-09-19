@extends('layouts.master')

@section('main')

<div class="col-12 col-md-6">
  <h3>Following</h3>
  <div class="clean-white h-100 pl-3 pt-3">
    @foreach($following as $myfollow)
    <p><a href="{{url("/user/profile/".$myfollow->id)}}">
        <img src="{{asset('/storage/profile_pics/'.$myfollow->pics[0]->name)}}" alt="" class="rounded-circle mr-2"
        style="height:3em;width:3em">
          <strong>{{$myfollow->name}}</strong>

      </a>
    </p>

    @endforeach
  </div>
</div>



@endsection
