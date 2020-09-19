@extends('layouts.master')

@section('link-to-noti')
nav-item-active
@endsection

@section('main')
<div class="col-12 col-md-6 clean-white" id="noti_menu_all">
</div>

<script type="text/javascript">







  function getAllNoti(page=0)
  {
    if(page>0)
      document.querySelector("#load-more-noti").remove();

    $.get('{{ url('/get/notifications') }}/'+page,function(data)
      {
        $("#noti_count_all").html('');

        if(page<1 && data.length==0)
        {
          $("#noti_menu_all").html(`<div class="d-flex vh-100">
          <p class="text-center text-muted align-self-center w-100" style="font-size:2em">No notifications</p>
          </div>`);
          return false;
        }

        $.each(data,function(index, noti) {

          let wrap=document.createElement('div');
          $("#noti_menu_all").append(createNoti(noti,wrap));


        });

        if(data.length==10)
          $("#noti_menu_all").append(createLoadMore(page+1));


      });

  }

  function createLoadMore(page)
  {
    let wrap=document.createElement('p');
    wrap.className="text-center";
    wrap.id="load-more-noti";
    wrap.innerHTML=`<button class="btn btn-light" onclick="getAllNoti(${page})">Load more</button>`;

    return wrap;
  }

  getAllNoti();

</script>

@endsection
