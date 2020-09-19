@extends('layouts.master')

@section('main')

<div class="col-12 col-md-6">
  <h3>Users</h3>

  <div class="clean-white pl-3 py-3">
    @if($users)
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

@endsection
