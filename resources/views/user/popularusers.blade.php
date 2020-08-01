@extends('layouts.master')

@section('main')
  <ul>
  @foreach($users as $user)
    <li><a href="user/profile/{{ $user->id }}">{{ $user->name }}</a>
    </li>

  @endforeach
  </ul>

@endsection
