@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Change Profile Picture') }}</div>

                <div class="card-body">
                  <div class="d-flex flex-column justify-content-center">
                    <img src="{{asset("storage/profile_pics/")}}/{{Auth::user()->getProfilePic()}}" class="rounded-circle mb-2 align-self-center" alt=""
                    style="height:5em;width:5em" id="my-profile-pic">
                    <form method="post" enctype="multipart/form-data" onsubmit="changeProfilePic(this)">
                      @csrf
                      @method('patch')
                      <input type="file" name="profile_pic" value="">
                      <input type="submit" value="Change">
                    </form>

                  </div>
                </div>
              </div>

              <p class="mt-2 text-right"><a href="{{url('/')}}">Skip</a></p>
            </div>
          </div>
        </div>



<script type="text/javascript">
async function changeProfilePic(f)
{
  event.preventDefault();


  if(!f.profile_pic.value)
  {
    alert("Choose image.");
    return false;
  }


  let response=await fetch('{{url('change/profile_pic/start')}}',{
    method : 'POST',
    body : new FormData(f)
  });

  if(response.ok)
  {
    let new_pic=await response.json();
    document.querySelector("#my-profile-pic").src="{{asset('storage/profile_pics')}}/"+new_pic;
  }

}
</script>
@endsection
