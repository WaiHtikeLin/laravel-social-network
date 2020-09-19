@extends('layouts.master')

@section('link-to-about')
nav-item-active
@endsection

@section('main')
<div class="col-12 d-md-none clean-white">
  <nav class="nav flex-column">
     <a href="{{url('')}}/myprofile" class="nav-link">
       <img src="{{asset('storage/profile_pics/profile.jpeg')}}" alt="" style="height:3em;width:3em" class="rounded-circle mr-2">
      {{Auth::user()->name}}</a>
    <a class="nav-link" href="{{url('')}}/myprofile"><img src="{{asset('/img/profile.png')}}" alt="" class="side-menu">Profile</a>
     <a class="nav-link" data-toggle="modal" href="#newPost">
       <img src="{{asset('/img/plus.png')}}" alt="" class="side-menu">
       New Post</a>
     <a class="nav-link" href="#">
       <img src="{{asset('/img/lock.png')}}" alt="" class="side-menu">
       Private Posts</a>
    <a class="nav-link" href="#"><img src="{{asset('/img/heart.png')}}" alt="" class="side-menu">Favorites</a>
    <a class="nav-link" href="#"><img src="{{asset('/img/settings.png')}}" alt="" class="side-menu">Settings</a>

     <a class="nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();"><img src="{{asset('/img/logout.png')}}" alt="" class="side-menu">Logout</a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
    </form>

  </nav>
</div>

@endsection
