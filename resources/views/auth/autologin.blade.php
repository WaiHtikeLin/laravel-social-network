@extends('layouts.app')

@section('content')
<div class="d-flex flex-column align-items-center">
  <p>Please wait</p>
  <div class="spinner-border spinner-border-sm" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>

<script type="text/javascript">

const data={
  'email' : 'guest@gmail.com',
  'password' : 'guest12345'
};

fetch('/login',{
  method: 'POST',
  headers:{
    'Content-Type': 'application/json;charset=utf-8',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content

  },

  body: JSON.stringify(data)
}).then(response=>{
  if(response.ok)
    location.href='{{url('/home')}}';
});


</script>
@endsection
