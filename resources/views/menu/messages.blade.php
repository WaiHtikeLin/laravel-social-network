@extends('layouts.master')

@section('link-to-messages')
nav-item-active
@endsection

@section('main')
<div class="col-12 col-md-6 clean-white" id="messages_menu_all">

</div>

<script type="text/javascript">
function getAllMessages(page=0)
{
  if(page>0)
    document.querySelector("#load-more-messages").remove();

  $.get('{{ url('get/messages/all') }}/'+page,function(data)
    {

      if(page<1 && data.length==0)
      {
        $("#messages_menu_all").html(`<div class="d-flex vh-100">
        <p class="text-center text-muted align-self-center w-100" style="font-size:2em">No messages</p>
        </div>`);
        return false;
      }

        $.each(data,function(index, data) {

          let wrap=document.createElement('div');
          wrap.id="chat_all_"+data.id;
          $("#messages_menu_all").append(createMessage(data.messages[0],data.members[0],data.members[0].pics[0].name,data.messages_count,wrap));

        });

        if(data.length==10)
          $("#messages_menu_all").append(createLoadMore(page+1));

    });


}

function createLoadMore(page)
{
  let wrap=document.createElement('p');
  wrap.className="text-center";
  wrap.id="load-more-requests";
  wrap.innerHTML=`<button class="btn btn-light" onclick="getAllMessages(${page})">Load more</button>`;

  return wrap;
}

getAllMessages();
</script>
@endsection
