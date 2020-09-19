@extends('layouts.master')

@section('link-to-requests')
nav-item-active
@endsection

@section('main')

<div class="col-12 col-md-6">
  <p class="text-right mb-2"><a href="{{url('/sentrequests')}}">View sent requests</a> </p>

  <div class="clean-white" id="requests_menu_all">
    
  </div>
</div>

<script type="text/javascript">

function getAllRequests(page=0)
  {
    if(page>0)
      document.querySelector("#load-more-requests").remove();

    $.get('{{ url('/get/requests') }}/'+page,function(data)
      {
        if(page<1 && data.length==0)
        {
          $("#requests_menu_all").html(`<div class="d-flex vh-100">
          <p class="text-center text-muted align-self-center w-100" style="font-size:2em">No requests</p>
          </div>`);
          return false;
        }

        $("#requests_count_all").html('');
        $.each(data,function(index, request) {

          let wrap=document.createElement('div');
          $("#requests_menu_all").append(createRequest(request,wrap));


        });

        if(data.length==10)
          $("#requests_menu_all").append(createLoadMore(page+1));

      });

  }

  function createLoadMore(page)
  {
    let wrap=document.createElement('p');
    wrap.className="text-center";
    wrap.id="load-more-requests";
    wrap.innerHTML=`<button class="btn btn-light" onclick="getAllRequests(${page})">Load more</button>`;

    return wrap;
  }

  getAllRequests();

</script>
@endsection
