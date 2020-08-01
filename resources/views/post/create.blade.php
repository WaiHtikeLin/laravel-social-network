@extends('layouts.master')

@section('main')
<form class="" action="{{url('')}}/store/post" method="post">
  @csrf
  <textarea name="texts" rows="8" cols="80"></textarea>

  <select class="" name="privacy">
    <option value="friend">Friend</option>
    <option value="public">Public</option>
  </select>

  <input type="submit" name="" value="OK">
</form>

@endsection
