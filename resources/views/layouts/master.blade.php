<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connect</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
      #themecolor {
        color:#007505
      }

      .unchecked {
        background-color: #f7f7f7;
      }
    </style>
  </head>
  <body>
    <passport-clients></passport-clients>
    <passport-authorized-clients></passport-authorized-clients>
    <passport-personal-access-tokens></passport-personal-access-tokens>
    <ul>
      <li><a href="#">Home</a>
      </li>

      <li><a href="#">Messages</a>
      </li>
      <li><select class="" name="" id="friend_requests">
            <option value="">Friend Requests</option>
          </select>
      </li>
      <li>
        <div class="dropdown" id="notifications">
          <button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Notifications
          </button>
          <span id="noticount"></span>
          <div class="dropdown-menu" id="noti-menu" aria-labelledby="dropdownMenuButton"></div>
      </div>
      </li>
      <li><a href="#">Search</a>
      </li>
      <li><a href="{{url('')}}/create/post">Create Post</a>
      </li>
      <li><a href='{{ url('') }}'>{{ Auth::user()->name }}</a>
      </li>
      <li><a href="{{ route('logout') }}"
         onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
          {{ __('Logout') }}
      </a>
      </li>
    </ul>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <script type="text/javascript">

      function createNoti(noti)
      {
            let link,innerlink;

              //$('.dropdown-menu').append('<a class="dropdown-item">'+noti.data.name+'</a>');
              innerlink=$('<a>',{
                href : noti.data.nameroute
              }).html(noti.data.name);

              link=$('<a>',{
                href : noti.data.notiroute,
                class : "dropdown-item "+noti.data.status
              })
              .click(function(event) {
                event.preventDefault();
                let element=$(this);
                $.ajax({
                  url: '{{url('')}}/check/noti/'+noti.id,
                  type: 'PATCH',

                })
                .done(function() {

                  if(element.hasClass('unchecked'))
                    element.removeClass('unchecked');
                  location.href='{{url('')}}'+noti.data.notiroute;

                })
                .fail(function() {
                  console.log("error");
                })
                .always(function() {
                  console.log("complete");
                });
              });

                return link.append(innerlink).append(' '+noti.data.msg);



      }

      function getAllNoti()
      {
        $.get('{{ url('/notifications') }}',function(data)
          {
            $.each(data,function(index, noti) {
              $("#noti-menu").append(createNoti(noti));

            });


          });

        $.get('{{url('')}}/count/unreadnoti',function(count)
      {
        $("#noticount").html(count);
      })
      }

      function getNewNoti()
      {
        $.get('{{ url('/unreadnotifications') }}',function(data)
          { let noti=[];
            $.each(data,function(index, msg) {
                noti.push(createNoti(msg));

            });

            $("#noti-menu").prepend(noti);


          });
      }

      getAllNoti();


      let id={{ Auth::id() }};

      let newnotifications=$('<a>',{
        href: '#',
        class : 'dropdown-item'
      }).click(function(event) {
        $(this).hide();
        getNewNoti();
      }).prependTo('#noti-menu').hide();

      Echo.private('App.User.'+id).notification((noti) => {

          if(!$("#noticount").html())
            $("#noticount").html('0');
          let count=parseInt($("#noticount").html());
          count+=1;
          $("#noticount").html(count);
          newnotifications.html('new'+count+' notifications');
          newnotifications.show();


        }).listen('FriendRequested',(data)=>{
          if(confirm(data.requester.name+" sent you a friend request."))
            $.post('{{url('')}}/accept/to/'+data.requester.id, function() {
              alert('Now Friends');
            });
          else
            $.ajax({
              url: '{{url('')}}/reject/to/'+data.requester.id,
              type: 'delete'
            })
            .done(function() {
              console.log("success");
              alert('You rejected');
            })
            .fail(function() {
              console.log("error");
            })
            .always(function() {
              console.log("complete");
            });

                  });
      $("#notifications").on('show.bs.dropdown',function(){

                                            if($('#noticount').html())
                                            { newnotifications.hide();
                                              $.ajax({
                                                url: '{{url('')}}/read/noti',
                                                type: 'PATCH'
                                              })
                                              .done(function() {
                                                $("#noti-menu").html("");
                                                getAllNoti();
                                                $('#noticount').html('');
                                              })
                                              .fail(function() {
                                                console.log("check your connection");
                                              })
                                              .always(function() {
                                                console.log("complete");
                                              });





                                            }
                                          });


    </script>
    <!-- <nav class="navbar navbar-expand navbar-light bg-light fixed-top">

       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <a class="navbar-brand font-weight-bolder font-italic" href="#" id="themecolor">Connect</a>
        <ul class="navbar-nav">
          <li class="nav-item active">
             <a class="nav-link" href="#" id="themecolor">Home<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
             <a class="nav-link" href="#" id="themecolor">Messages</a>
          </li>

          <li class="nav-item">
             <a class="nav-link" href="#" id="themecolor">Notifications</a>
          </li>

          <li class="nav-item d-md-none">
             <a class="nav-link" href="#" id="themecolor">Search</a>
          </li>


        </ul>
        <form class="form-inline d-none d-xs-none d-md-inline">
          <input class="form-control " type="text">
          <button class="btn btn-outline-success" type="submit">
            Search
          </button>
        </form>
        <ul class="navbar-nav ml-md-auto">
          <li class="nav-item dropdown">
             <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown">Wai Htike Lin</a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
               <a class="dropdown-item" href="#">Action</a> <a class="dropdown-item" href="#">Another action</a> <a class="dropdown-item" href="#">Something else here</a>
              <div class="dropdown-divider">
              </div> <a class="dropdown-item" href="#">Separated link</a>
            </div>
          </li>
        </ul>

    </nav> -->

    @yield('main')
  </body>
</html>
