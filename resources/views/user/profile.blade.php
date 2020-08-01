@extends('layouts.master')

@section('main')
  <p>{{ $user->name }}</p>
@can('edit',$user)
<p><a href="{{ url('edit/profile') }}">edit</a></p>
@else

  <p><a href="#" id="add_friend">{{ $friendship_status }}</a>&nbsp;<a href="#" id="follow">{{ $follow_status }}</a>&nbsp;
    <a href="#" id="block">Block</a>
    @can('chat',$user)
    <a href="{{url('')}}/chat/to/{{$user->id}}" id="message">Message</a>
    @endcan
  </p>
@endcan
  <script>
    $(document).ready(function() {
      $("#add_friend").click(function(event) {

        $.get('{{url('request/to/'.$user->id) }}', function(val)
          {
            $("#add_friend").text(val);
          }
        );
      });

      $("#follow").click(function(event) {

        $.get('{{ url('follow/to/'.$user->id) }}', function(val)
          {
            $("#follow").text(val);
          }
        );
      });

      $("#block").click(function(event) {

        if(confirm("Are you sure"))
          location.href='{{ url('block/to/'.$user->id) }}';
      });


    });
  </script>
@endsection
