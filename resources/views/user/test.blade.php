@extends('layouts.master')

@section('main')
<script type="text/javascript">
  let id={{ Auth::id() }};
  Echo.private('App.User.'+id).notification((notification) => {
        alert(notification.name);
    });
</script>
<p>Hello</p>
@endsection
