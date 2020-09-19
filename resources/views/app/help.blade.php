@extends('layouts.master')

@section('link-to-help')
side-nav-active
@endsection

@section('main')

<div class="col-12 col-md-6 clean-white pt-2">

  @if(session('status'))

  <div class="alert alert-success alert-dismissible fade show" role="alert">
  {{session('status')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  @endif

  <form action="{{url('/add/question')}}" method="post">

    @csrf
    <p>
      Ask question about something you want to know with our app, we will answer shortly:
    </p>
    <textarea name="question" class="form-control mb-3" placeholder="Ask question..." required></textarea>

    <p class="text-center">
      <input type="submit" name="" value="Send" class="btn btn-light">
    </p>
  </form>
</div>

@endsection
