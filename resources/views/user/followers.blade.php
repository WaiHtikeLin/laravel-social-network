@extends('layouts.master')

@section('main')
<ul>
  @foreach($followers as $follower)
    <li><a href="{{ route('profile',['user'=>$follower->id]) }}">
      {{ $follower->name }}</a>

    </li>
  @endforeach
</ul>


@endsection
