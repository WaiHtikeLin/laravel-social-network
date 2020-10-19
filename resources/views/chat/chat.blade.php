@extends('layouts.master')

@section('main')

<div class="col-12 col-md-6">
    <div class="position-relative">
      <div class="d-flex flex-column mb-5">
        <img src="{{asset("storage/profile_pics/$profile_pic")}}" class="rounded-circle mb-2 align-self-center" alt=""
        style="height:5em;width:5em">
        <p class="mb-2 align-self-center">{{$user->name}}</p>
      </div>

      <div id="chatbox">

      </div>

      <div class="mt-2">
        <form id="send_message_form">
          @csrf
          <div class="input-group">
            <input class="form-control rounded-pill mr-2"
            id="send-message-text" type="text" name="message" value="" placeholder="Enter message..."
            aria-label="Text box to message" disabled>
            <input class="btn btn-light rounded-pill" type="submit" name="send-message" value="Send" disabled>
          </div>

        </form>
      </div>

      <div class="modal fade" tabindex="-1" aria-labelledby="edit msg" aria-hidden="true"
      id="edit-msg-modal">
      <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header p-0">
          <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&lt;</span>
            <span class="ml-2">Edit message</span>
          </button>

        </div>

        <div class="modal-body">
          <form onsubmit="updateMsg(this)" method="post">
          <div class="input-group">

            @csrf
            @method('patch')

            <input class="form-control rounded-pill mr-2"
            type="text" name="message" placeholder="Edit message..."
            aria-label="Text box to edit message" aria-describedby="edit message" required>
            <input class="btn btn-success rounded-pill" type="submit" value="Ok">
          </div>
        </form>
        </div>

        <div class="modal-footer">
          <p class="text-center w-100">
            <button type="button" name="button" class="btn btn-dark"
            data-dismiss="modal" aria-label="Close">Close</button>
          </p>
        </div>
      </div>
    </div>
    </div>

    </div>

</div>




<script type="text/javascript">
  let previous_id=null;
  function createNewMessage(wrap,msg)
  {
    wrap.className="row";
    wrap.id="msg_row_"+msg.id;
    msg.created_at=getTimeDiff(msg.created_at);
    if(msg.seen==1)
      msg.seen="Seen";
    else
      msg.seen="Sent";

    if(msg.sender_id==msg.user_id)
      wrap.innerHTML=`@include('chat.msg-row',['me'=>true])`;
    else if(previous_id && previous_id==msg.sender_id)
      wrap.innerHTML=`@include('chat.msg-row')`;
    else
      wrap.innerHTML=`@include('chat.msg-row',['image'=>true])`;

    return wrap;
  }

function getChatMessages(index)
{
  if(index>0)
    document.querySelector("#load-more-chat-messages").remove();

  fetch("{{url('')}}/get/messages/{{$user->id}}/"+index).then(response=>{
    if(response.ok)
      return response.json();
  }).then(msgs=>{


    for(i=msgs.length-1;i>=0;i--)
    {
      let wrap=document.createElement('div');
      chatbox.append(createNewMessage(wrap,msgs[i]));

      previous_id=msgs[i].sender_id;
    }

    if(msgs.length==20)
      chatbox.prepend(createLoadMoreChatMessages(index+1));




  });
}

function createLoadMoreChatMessages(index)
{
  let wrap=document.createElement('p');
  wrap.className="text-center";
  wrap.id="load-more-chat-messages";
  wrap.innerHTML=`<button class="btn btn-light" onclick="getChatMessages(${index})">Load more</button>`;

  return wrap;
}

getChatMessages(0);

document.querySelector("#send_message_form input[type='text']").disabled=false;
document.querySelector("#send_message_form input[type='submit']").disabled=false;

  Echo.private('App.User.{{Auth::id()}}').listen('editedMessage',(data)=>{
    if(data.room_id=='{{$room_id}}')
      document.querySelector("#msg_"+data.id).innerHTML=data.message;

  }).listen('sawMessage', data=>{
    if(data.room_id=='{{$room_id}}')
      document.querySelectorAll(".send_status").forEach(status=>{status.textContent='Seen';});
  });

  send_message_form.onsubmit= async (e) => {
    e.preventDefault();

    let response=await fetch ('{{url('')}}/send/message/to/{{$user->id}}', {
      method : 'post',
      body : new FormData(send_message_form)
    });

    if(response.ok)
    {
      let msg=await response.json();
      let new_message=document.createElement('div');
      chatbox.append(createNewMessage(new_message,msg));

      previous_id=msg.sender_id;
      document.querySelector("#send-message-text").value='';
    }
    else
      alert("error");
  }
  // document.querySelector("#send-message").addEventListener('click',async () => {
  //   try {
  //     let msg=await (await fetch('{{url('')}}/send/message/to/{{$user->id}}',
  //   {
  //     method : 'post',
  //     headers : {
  //
  //     }
  //     body : JSON.stringify(data)
  //
  //   })).json();
  //     alert(msg);
  //   } catch (e) {
  //     alert(e);
  //   } finally {
  //
  //   }
  //
  //   });

  function getMsgToEdit(link)
  { event.preventDefault();

    let id=link.dataset.msgId;

    let value=document.querySelector("#msg_"+id).textContent;
    document.querySelector("#edit-msg-modal input[type='text']").value=value;
    document.querySelector("#edit-msg-modal form").dataset.msgId=id;


  }

  async function updateMsg(f)
  { event.preventDefault();
    let id=f.dataset.msgId;
    let response=await fetch('{{url('')}}/update/message/'+id, {
      method : 'POST',
      body : new FormData(f)
    });

    if(response.ok)
    {
      let msg=await response.json();
      if(msg!='error')
      {
        bootstrap.Modal.getInstance(document.querySelector("#edit-msg-modal")).hide();
        document.querySelector(`#msg_${id}`).innerHTML=msg;
      }

  }
}

async function deleteMsg(link)
{
  event.preventDefault();
  if(confirm("Are you sure?\nIt'll be deleted only on your side."))
  {
    let id=link.dataset.msgId;

    let response=await fetch('{{url('')}}/delete/message/'+id, {
      method : 'DELETE',
      headers : {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    if(response.ok)
    { let result=await response.json();

      if(result!='error')
        document.querySelector(`#msg_row_${id}`).remove();
  }
  }


}

</script>
@endsection
