@extends('layouts.master')

@section('main')

<div class="col-12 col-md-6">
  <h3>Blocked People</h3>
  <div class="clean-white h-100 pl-3 pt-3">
    @foreach($blocked as $block)
    <p><a href="{{url("/user/profile/".$block->id)}}">
        <img src="{{asset('/storage/profile_pics/'.$block->pics[0]->name)}}" alt="" class="rounded-circle mr-2"
        style="height:3em;width:3em">
          <strong>{{$block->name}}</strong>

      </a>

      <a href='{{"unblock/to/$block->id"}}' class="ml-2">Unblock</a>
    </p>

    @endforeach
  </div>
</div>



@endsection
