@extends('layouts.master')
@section('main')
  @switch($field)
    @case('all')  @include('user.editall') @break
    @case('education')  @include('user.addedu') @break
    @case('work')  @include('user.addwork') @break
    @case('address')  @include('user.addaddress') @break
    @default  @include('user.editall')
  @endswitch
@endsection
