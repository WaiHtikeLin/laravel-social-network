@extends('layouts.master')

@section('link-to-settings')
side-nav-active
@endsection

@section('main')
<div class="col-12 col-md-6 clean-white">

  @if(session('status'))

  <div class="alert alert-success alert-dismissible fade show" role="alert">
  {{session('status')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  @endif

  <p class="text-right"><a href="#" onclick="editSettings(this)">Edit settings</a> </p>
  <form action="{{url('/update/settings')}}" method="post">
    @method('patch')
    @csrf

    <div class="row mb-3">
      <label class="col-8 col-form-label">Who can see your friends list?</label>
      <div class="col-4">
        <select class="form-select" name="friends_privacy" aria-label="Choose privacy" disabled>
          <option value="onlyme" {{$friends_privacy['onlyme']}}>Only me</option>
          <option value="friend" {{$friends_privacy['friend']}}>Friends</option>
          <option value="public" {{$friends_privacy['public']}}>Public</option>
        </select>
      </div>

    </div>

    <div class="row mb-3">
      <label class="col-8 col-form-label">Who can see your followers list?</label>
      <div class="col-4">
        <select class="form-select" name="followers_privacy" aria-label="Choose privacy" disabled>
          <option value="onlyme" {{$followers_privacy['onlyme']}}>Only me</option>
          <option value="friend" {{$followers_privacy['friend']}}>Friends</option>
          <option value="public" {{$followers_privacy['public']}}>Public</option>
        </select>
      </div>

    </div>

    <div class="row mb-3">
      <label class="col-8 col-form-label">Who can see your following people list?</label>
      <div class="col-4">
        <select class="form-select" name="following_privacy" aria-label="Choose privacy" disabled>
          <option value="onlyme" {{$following_privacy['onlyme']}}>Only me</option>
          <option value="friend" {{$following_privacy['friend']}}>Friends</option>
          <option value="public" {{$following_privacy['public']}}>Public</option>
        </select>
      </div>

    </div>

    <p class="text-center"><input type="submit" class="d-none" value="Update Settings" id="update-settings" /></p>

  </form>

  <p class="mt-5"><a href="{{url('/blocked')}}">Show blocked people</a></p>
</div>
<script type="text/javascript">
  function editSettings(link)
  {
    event.preventDefault();
    let f=link.parentNode.nextElementSibling;

    f.querySelectorAll('select').forEach(s=>{s.disabled=false;});
    f.querySelector('#update-settings').classList.remove('d-none');
    link.remove();
  }
</script>
@endsection
