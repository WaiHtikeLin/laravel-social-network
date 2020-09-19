@extends('layouts.master')

@section('main')
<form class="" action="{{url('')}}/update/post/{{ $post->id }}" method="post">
  @csrf
  @method('patch')
  <textarea name="texts" rows="8" cols="80">{{ $post->texts }}</textarea>

  <select class="" name="privacy">
    <option value="friend">Friend</option>
    <option value="public">Public</option>
  </select>

  <input type="submit" name="" value="OK">
</form>

<form action="/delete/post/{{ $post->id }}" method="post">
  @csrf
  @method('delete')
  <input type="submit" name="delete" value="Delete">

</form>

@endsection
