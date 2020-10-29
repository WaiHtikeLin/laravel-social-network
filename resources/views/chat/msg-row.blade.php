<div class="col-1 pr-0">
    @isset($image)
    <a href='{{url("/user/profile/$user->id")}}'>
      <img src="{{ asset("storage/profile_pics/$profile_pic") }}" alt=""
      style="height:2em;width:2em" class="rounded-circle mr-0">
    </a>

    @endisset

  </div>
  <div class="col-11 clearfix pl-0">


    <div class="d-inline-block @isset($me) float-right @endisset" style="max-width:20em">

    <p class=" text-break px-5 clean-white mb-0 mt-3 shadow rounded-pill" id="msg_${msg.id}" @isset($me) data-toggle="collapse"
    data-target="#msg-action-${msg.id}" @endisset>
      ${msg.message}
    </p>
    <p class="text-right mb-0 text-muted" style="font-size:0.5em">
      @isset($me)
      <span class="send_status">${msg.seen}</span>&nbsp;&nbsp;
      @endisset

      ${msg.created_at}</p>

    @isset($me)
    <div class="collapse" id="msg-action-${msg.id}">
      <a href="#edit-msg-modal" class="mr-2" data-toggle="modal"
      data-msg-id="${msg.id}" onclick="getMsgToEdit(this)">Edit</a>
      <a href="#" onclick="deleteMsg(this)" data-msg-id="${msg.id}">Delete</a>
    </div>
    @endisset

    </div>


  </div>
