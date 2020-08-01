@extends('layouts.master')

@section('main')
<div class="" id="chatbox">
  @foreach($messages as $msg)
    @if($msg->sender_id==$msg->user_id)
      <p class="txt-right">{{$msg->message}}</p>
    @else
      <p>{{$msg->message}}</p>
    @endif
  @endforeach
</div>

  <form class="" action="" method="post" id="send_message_form">
    <input type="text" name="message" value="">
    <input type="submit" name="send-message" value="Send">
    @csrf
  </form>
<script type="text/javascript">

  Echo.join('chat.{{$room_id}}').listen('newMessage',(data)=>{
    let new_message=document.createElement('p');
    new_message.innerHTML=data.message;
    chatbox.append(new_message);
  });

  send_message_form.onsubmit= async (e) => {
    e.preventDefault();

    let response=await fetch('{{url('')}}/send/message/to/{{$user->id}}', {
      method : 'post',
      headers : {
        'X-Socket-ID' : Echo.socketId()
      },
      body : new FormData(send_message_form)
    });

    if(response.ok)
    {
      let msg=await response.json();
      let new_message=document.createElement('p');
      new_message.innerHTML=msg;
      chatbox.append(new_message);
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
</script>
@endsection
