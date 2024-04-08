<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://connectonline.space/">
    <meta property="og:site_name" content="Connect">
    <meta property="og:image" itemprop="image primaryImageOfPage" content="https://connectonline.space/favicon.ico">
    <meta name="description" content="Connect is a social network application where you can post how you feel, what's on your mind with your friends, public or only you.">
    <title>Connect</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
      .main-color{
        color: #034b00;
      }

      .reacted{
        color: #034b00;
      }

      .nav-item:hover .nav-link:hover{
        background: #f8f9fa;
      }

      .checked{
        background: #f8f9fa;
      }

      .nav-item-active{
        border-bottom:2px solid #034b00;
      }

      .clean-white{
        background: white;
      }

      .btn-success{
        background: #034b00;
        color: white;
      }



      .react-count{
        height: 1em;
        width: 1em;
      }

      .copy-post{
        height:1em;
        width:1em;
      }

      .noti-wrap:hover{

        cursor: pointer;
        background: #c1d4e7;
      }

      .noti-wrap{
        border-bottom: 1px solid black;
      }

      .side-menu{
        height: 1.5em;
        width: 1.5em;
        margin-right: 5px
      }

      .side-item:hover{
        background: white;
      }

      .info-icon
      {
        height: 2em;
        width: 2em;
      }

      .info-privacy{
        height: 1em;
        width: 1em;
      }

      .side-nav-active{
        color: #034b00;
      }

    </style>
  </head>
  <body>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>

    <script type="text/javascript">
          $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>

    <nav class="navbar sticky-top navbar-expand d-none d-md-block clean-white mb-2">
      <div class="container-fluid">
        <a class="navbar-brand font-weight-bold main-color mr-5" href="#"><em>Connect</em></a>

    <div class="collapse navbar-collapse justify-content-around">
      <form class="d-flex" action="{{url('/search')}}" method="post">
          <input class="form-control mr-2" type="search" placeholder="Search"
          aria-label="Search" size="40" required name="search">
          <button type="submit" name="button" class="border-0 btn btn-success">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search text-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
          </button>
      </form>

      <ul class="nav nav-pills">
        <li class="nav-item @yield('link-to-feeds')">
          <a class="nav-link" href="{{url('')}}/home" data-toggle="tooltip" data-placement="left" title="Home">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door main-color" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5H9.5a.5.5 0 0 1-.5-.5v-4H7v4a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6zM2.5 7.707V14H6v-4a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v4h3.5V7.707L8 2.207l-5.5 5.5z"/>
              <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
            </svg>
          </a>
        </li>
        <li class="nav-item @yield('link-to-messages')">
          <div class="dropdown" id="messages">
            <button class="btn btn-light clean-white border-0" type="button" id="messages_trigger"
            data-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" data-placement="left" title="Messages">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-dots main-color" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
              <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg>

              <span class="badge bg-danger align-top rounded-circle" id="messages_count"></span>
            </button>

                      <ul class="dropdown-menu dropdown-menu-right shadow"
                      aria-labelledby="messages_trigger" style="width:35em;max-height:30em;overflow-y:auto;">


                        <div id="messages_menu">

                        </div>


                        <li class="dropdown-item text-center"><a href="{{url('/messages')}}">See all Messages</a></li>


                      </ul>

          </div>

        </li>
        <li class="nav-item @yield('link-to-requests')">
          <div class="dropdown" id="requests">
            <button class="btn btn-light clean-white border-0" type="button" id="requests_trigger"
            data-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" data-placement="left" title="Requests">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-people main-color" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.995-.944v-.002.002zM7.022 13h7.956a.274.274 0 0 0 .014-.002l.008-.002c-.002-.264-.167-1.03-.76-1.72C13.688 10.629 12.718 10 11 10c-1.717 0-2.687.63-3.24 1.276-.593.69-.759 1.457-.76 1.72a1.05 1.05 0 0 0 .022.004zm7.973.056v-.002.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10c-1.668.02-2.615.64-3.16 1.276C1.163 11.97 1 12.739 1 13h3c0-1.045.323-2.086.92-3zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
            </svg>

              <span class="badge bg-danger align-top rounded-circle" id="requests_count"></span>
            </button>

                      <ul class="dropdown-menu dropdown-menu-right shadow"
                      aria-labelledby="requests_trigger" style="width:35em;max-height:30em;overflow-y:auto;">


                        <div id="requests_menu">

                        </div>


                        <li class="dropdown-item text-center"><a href="{{url('/requests')}}">See all Requests</a></li>


                      </ul>

          </div>


        </li>
        <li class="nav-item @yield('link-to-noti')">
          <div class="dropdown" id="notifications">
            <button class="btn btn-light clean-white border-0" type="button" id="notifications_trigger"
            data-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" data-placement="left" title="Notifications">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell main-color" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
              </svg>

              <span class="badge bg-danger align-top rounded-circle" id="noti_count"></span>
            </button>

                      <ul class="dropdown-menu dropdown-menu-right shadow"
                      aria-labelledby="notifications_trigger" style="width:35em;max-height:30em;overflow-y:auto;">


                        <div id="noti_menu">

                        </div>


                        <li class="dropdown-item text-center"><a href="{{url('/notifications')}}">See all notifications</a></li>
                      </ul>


        </li>



      </ul>

      <button type="button" name="button" class="border-0 btn btn-success"
      data-toggle="tooltip" data-placement="left" title="Create new post" onclick="showCreatePostModal()">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus-circle text-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
        <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        </svg>
      </button>




    </div>

      </div>
    </nav>

    <div class="d-flex d-md-none clean-white">
      <h5 class="main-color font-weight-bold mr-auto my-auto"><em>Connect</em></h5>
      <button type="button" name="button" class="border-0 btn btn-success mr-2">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search text-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
          <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
        </svg>
      </button>

      <button type="button" name="button" class="border-0 btn btn-success" data-toggle="tooltip" data-placement="left" title="Create new post" onclick="showCreatePostModal()">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus-circle text-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
        <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        </svg>
      </button>


    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="createNewPost"
    aria-hidden="true" data-backdrop="static"
    data-keyboard="false" id="newPost">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-0">
            <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&lt;</span>
              <span class="ml-2">New Post</span>
            </button>
            <!-- <div class="" data-dismiss="modal" aria-label="Close">

            </div> -->
          </div>
          <div class="modal-body">
            <form class="" action="{{url('')}}/store/post" method="post">

              @csrf

              <div class="row mb-3">
                <label class="col-3 col-form-label">Privacy:</label>
                <div class="col-5">
                  <select class="form-select" name="privacy" aria-label="Choose privacy">
                    <option value="onlyme">Only me</option>
                    <option value="friend">Friends</option>
                    <option value="public">Public</option>
                  </select>
                </div>

              </div>

              <textarea name="texts" class="form-control mb-3" placeholder="Type something on your mind..." required></textarea>

              <div class="form-check form-switch mb-3">
                <label class="form-check-label">Shareable</label>
                <input type="checkbox" class="form-check-input" value="1" name="shareable">
              </div>

              <div class="form-check form-switch mb-3">
                <label class="form-check-label">Copyable with credit</label>
                <input type="checkbox" class="form-check-input" value="1" name="copyable">
              </div>

              <div class="float-right">
                <button type="button" name="button" class="btn btn-dark mr-2" data-dismiss="modal" aria-label="Close">Cancel</button>
                <input type="submit" name="" value="Post" class="btn btn-light">
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="editPost"
    aria-hidden="true" data-backdrop="static"
    data-keyboard="false" id="edit-post">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-0">
            <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&lt;</span>
              <span class="ml-2">Edit Post</span>
            </button>
            <!-- <div class="" data-dismiss="modal" aria-label="Close">

            </div> -->
          </div>
          <div class="modal-body">
            <form method="post" onsubmit="return checkEdit(this)" data-feed-id=''>

              @csrf
              @method('patch')
              <div class="row mb-3">
                <label class="col-3 col-form-label">Privacy:</label>
                <div class="col-5">
                  <select class="form-select" name="privacy" aria-label="Choose privacy">
                    <option value="onlyme">Only me</option>
                    <option value="friend">Friends</option>
                    <option value="public">Public</option>
                  </select>
                </div>

              </div>

              <textarea name="texts" class="form-control mb-3" placeholder="Type something on your mind..."></textarea>

              <div class="form-check form-switch mb-3">
                <label class="form-check-label">Shareable</label>
                <input type="checkbox" class="form-check-input" value="1" name="shareable">
              </div>

              <div class="form-check form-switch mb-3">
                <label class="form-check-label">Copyable with credit</label>
                <input type="checkbox" class="form-check-input" value="1" name="copyable">
              </div>

              <p class="text edit-error" data-feed-id=''></p>

              <div class="float-right">
                <button type="button" name="button" class="btn btn-dark mr-2" data-dismiss="modal" aria-label="Close">Cancel</button>
                <input type="submit" name="" value="Update" class="btn btn-light">
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="editPost"
    aria-hidden="true" data-backdrop="static"
    data-keyboard="false" id="edit-copy-post">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-0">
            <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&lt;</span>
              <span class="ml-2">Edit Post</span>
            </button>
            <!-- <div class="" data-dismiss="modal" aria-label="Close">

            </div> -->
          </div>
          <div class="modal-body">
            <form method="post" onsubmit="return checkEdit(this)" data-feed-id=''>

              @csrf
              @method('patch')
              <div class="row mb-3">
                <label class="col-3 col-form-label">Privacy:</label>
                <div class="col-5">
                  <select class="form-select" name="privacy" aria-label="Choose privacy">
                    <option value="onlyme">Only me</option>
                    <option value="friend">Friends</option>
                    <option value="public">Public</option>
                  </select>
                </div>

              </div>

              <textarea name="texts" class="form-control mb-3" placeholder="Type something on your mind..."></textarea>

              <div class="form-check form-switch mb-3">
                <label class="form-check-label">Shareable</label>
                <input type="checkbox" class="form-check-input" value="1" name="shareable">
              </div>



              <p class="text edit-error" data-feed-id=''></p>

              <div class="float-right">
                <button type="button" name="button" class="btn btn-dark mr-2" data-dismiss="modal" aria-label="Close">Cancel</button>
                <input type="submit" name="" value="Update" class="btn btn-light">
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="editPost"
    aria-hidden="true" data-backdrop="static"
    data-keyboard="false" id="edit-comment" style="z-index:9999">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-0">
            <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&lt;</span>
              <span class="ml-2">Edit Comment</span>
            </button>
            <!-- <div class="" data-dismiss="modal" aria-label="Close">

            </div> -->
          </div>
          <div class="modal-body">
            <form method="post" onsubmit="return updateComment(this)">

              @csrf
              @method('patch')

              <input class="form-control rounded-pill" type="text" name="texts" value=""
              aria-label="Text box to edit comment" aria-describedby="edit-comment">
              <div class="float-right">
                <button type="button" name="button" class="btn btn-dark mr-2" data-dismiss="modal" aria-label="Close">Cancel</button>
                <input type="submit" name="" value="Update" class="btn btn-light">
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" aria-labelledby="editPost"
    aria-hidden="true" data-backdrop="static"
    data-keyboard="false" id="edit-reply" style="z-index:9999">
        <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-0">
            <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&lt;</span>
              <span class="ml-2">Edit Reply</span>
            </button>
            <!-- <div class="" data-dismiss="modal" aria-label="Close">

            </div> -->
          </div>
          <div class="modal-body">
            <form method="post" onsubmit="return updateReply(this)">
              @csrf
              @method('patch')
              <input class="form-control rounded-pill" type="text" name="texts" value=""
              aria-label="Text box to edit reply" aria-describedby="edit-reply">
              <div class="float-right">
                <button type="button" name="button" class="btn btn-dark mr-2" data-dismiss="modal" aria-label="Close">Cancel</button>
                <input type="submit" name="" value="Update" class="btn btn-light">
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>


    @include('components.mobile-menu')




    <div class="container-md px-0">

      <div class="row justify-content-center">
        <div class="col-md-3 d-none d-md-block">

          @include('components.side-nav')
        </div>

        @yield('main')
      </div>
    </div>







    <script type="text/javascript">

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('li[data-toggle="tooltip"]'));
      let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

    function showCreatePostModal()
    {
      let modal=new bootstrap.Modal(document.querySelector("#newPost"));
      modal.show();
    }

    function getTimeDiff(date_str)
    {
      let date=Date.parse(date_str);
      const date_diff=Date.now()-date;
      let posted_time=Math.floor(date_diff/60000);
      if(posted_time<1)
        return 'Just now';
      if(posted_time<2)
        return '1 min';
      if(posted_time<60)
        return posted_time+' mins';

      if(posted_time<120)
        return '1 hr';
      posted_time=Math.floor(posted_time/60);
      if(posted_time<24)
      {
        return posted_time+' hrs';
    }
      posted_time=Math.floor(posted_time/24);
      if(posted_time<7)
      {
        let day;
        date=new Date(date);
        switch (date.getDay()) {
          case 0:
            day='Sun';
            break;
          case 1:
            day='Mon';
            break;
          case 2:
            day='Tues';
            break;
          case 3:
            day='Wed';
            break;
          case 4:
            day='Thurs';

            break;
          case 5:
            day='Fri';
            break;
          case 6:
            day='Sat';
            break;
          default:

        }

        let hrs=date.getHours();

        let mins=date.getMinutes();
        if(hrs<12)
        { if(hrs==0)
            hrs=12;
          return day+' at '+hrs+':'+mins+' am';
        }
        else
        { if(hrs>12)
            hrs-=12;
          return day+' at '+hrs+':'+mins+' pm';
        }
    }


      if(posted_time<14)
        return '1 week';
      const posted_t=Math.floor(posted_time/7);

      if(posted_t<4)
        return posted_t+' weeks';


      if(posted_time<60)
        return '1 month';

      posted_time=Math.floor(posted_time/30);
      if(posted_time<12)
        return posted_time+' months';
      if(posted_time<24)
        return '1 y';
      posted_time=Math.floor(posted_time/12);
      return posted_time+' yrs';
    }


    function createNoti(noti,wrap)
    {
      noti.created_at=getTimeDiff(noti.created_at);
      wrap.className+=" d-flex px-3 py-2 noti-wrap "+noti.data.status+" noti_"+noti.id;
      wrap.onclick=()=>{

        fetch('{{url('')}}/check/noti/'+noti.id,{
          method: 'PATCH',
          headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }).then(response=>{
          if(response.ok)
          {
            if(wrap.classList.contains('unchecked'))
              wrap.classList.remove('unchecked');
            location.href='{{url('')}}'+noti.data.notiroute;
          }
          throw new Error();

        });
  };
      wrap.innerHTML=`<div class="mr-2">
        <a href="{{url('')}}/user/profile/${noti.data.id}">
          <img src="{{ asset('storage/profile_pics/${noti.data.profile_pic}') }}" alt=""
          style="height:4em;width:4em" class="rounded-circle">
        </a>

      </div>

      <div class="flex-fill">
        <p class="mb-0"><a href="{{url('')}}/user/profile/${noti.data.id}"
          class="text-decoration-none react-link text-dark"><strong>${noti.data.name}</strong></a>&nbsp;${noti.data.msg}<br>${noti.created_at}</p>
        <p class="text-right m-0">
          <button type="button" name="button" class="btn btn-light clean-white border-0 noti-remove"
          data-noti-id='${noti.id}' onclick="removeNoti(this)">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
            </svg>
          </button>
        </p>
      </div>`;

      return wrap;
    }

    function createRequest(request,wrap)
    {
      request.pivot.created_at=getTimeDiff(request.pivot.created_at);
      wrap.className+=" d-flex px-3 py-2 noti-wrap request_from_"+request.id;

      wrap.onclick=()=>{

            location.href='{{url('')}}/user/profile/'+request.id;
          };

      wrap.innerHTML=`<div class="mr-2">
        <a href="{{url('')}}/user/profile/${request.id}">
          <img src="{{ asset('storage/profile_pics/${request.pics[0].name}') }}" alt=""
          style="height:4em;width:4em" class="rounded-circle">
        </a>

      </div>

      <div class="flex-fill">
        <p class="mb-0"><a href="{{url('')}}/user/profile/${request.id}"
          class="text-decoration-none react-link text-dark"><strong>${request.name}</strong></a> sent you a friend request<br>${request.pivot.created_at}</p>
        <p class="text-right m-0">
          <button type="button" name="button" class="btn btn-success border-0" data-request-id="${request.id}"
          onclick="acceptRequest(this)">Accept</button>
          <button type="button" name="button" class="btn btn-danger border-0"
          data-request-id='${request.id}' onclick="rejectRequest(this)">
              Reject
          </button>
        </p>
      </div>`;

      return wrap;
    }
    function getMessageTemplate(msg,count)
    {
      if(count>0)
        return `<div class="clearfix"><p>
        <strong id="latest_msg">${msg}</strong></p>
        <p class="float-right">
        <span class="badge bg-success rounded-circle" id="msg_count">${count}</span>
        </p>
        </div>`;
      else
        return `<p id="latest_msg">${msg}</p>`;
    }
    function createMessage(msg,user,profile_pic,count,wrap)
    {
      let created_at=getTimeDiff(msg.created_at);

      wrap.className+=" d-flex px-3 py-2 noti-wrap chat_"+msg.room_id;
      wrap.onclick=()=>{


            location.href='{{url('')}}/chat/to/'+user.id;

  };
      wrap.innerHTML=`<div class="mr-2">
        <a href="{{url('')}}/user/profile/${user.id}">
          <img src="{{ asset('storage/profile_pics/${profile_pic}') }}" alt=""
          style="height:4em;width:4em" class="rounded-circle">
        </a>

      </div>

      <div class="flex-fill">
        <p><strong>${user.name}</strong></p>
        ${getMessageTemplate(msg.message,count)}
        <p class="mb-0" id="created_at">${created_at}</p>
        <p class="text-right m-0">
          <button type="button" name="button" class="btn btn-light clean-white border-0 chat-remove"
          data-room-id='${msg.room_id}' onclick="removeChat(this)">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
            </svg>
          </button>
        </p>
      </div>`;

      return wrap;
    }


      function getNoti()
      {
        $.get('{{ url('get/notifications') }}',function(data)
          {
            $.each(data,function(index, noti) {

              let wrap=document.createElement('li');
              wrap.className="dropdown-item";
              $("#noti_menu").append(createNoti(noti,wrap));

            });


          });


      }

      function getRequests()
      {
        $.get('{{ url('get/requests') }}',function(data)
          {
              $.each(data,function(index, noti) {

                let wrap=document.createElement('li');
                wrap.className="dropdown-item";
                $("#requests_menu").append(createRequest(noti,wrap));

              });

          });


      }

      function getMessages()
      {
        $.get('{{ url('get/messages') }}',function(data)
          {
              $.each(data,function(index, data) {

                let wrap=document.createElement('li');
                wrap.id="chat_"+data.id;
                wrap.className="dropdown-item";
                $("#messages_menu").append(createMessage(data.messages[0],data.members[0],data.members[0].pics[0].name,data.messages_count,wrap));

              });

          });


      }

      async function acceptRequest(btn)
      {
        event.stopPropagation();
        const id=btn.dataset.requestId;

        let response=await fetch("{{url('')}}/accept/to/"+id,{
          method: 'POST',
          headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        if(response.ok)
          document.querySelector(".request_from_"+id).forEach(request=>{
            request.innerHTML=`You and ${id} are now friends.`;});
      }

      async function rejectRequest(btn)
      {
        event.stopPropagation();
        const id=btn.dataset.requestId;

        let response=await fetch("{{url('')}}/reject/to/"+id,{
          method: 'DELETE',
          headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        if(response.ok)
          document.querySelector(".request_from_"+id).forEach(request=>{
            request.remove();});
      }

      function getNotiCount()
      {
        $.get('{{url('')}}/count/unreadnoti',function(count)
        {

          if(count>0)
          {

            if(!$('#link-to-noti').hasClass('nav-item-active'))
            {
              $("#noti_count").html(count);
              $("#noti_count_all").html(count);
            }

          }

        });
      }

      function getRequestsCount()
      {
        $.get('{{url('')}}/count/unreadrequests',function(count)
        {

          if(count>0)
          {

            if(!$('#link-to-requests').hasClass('nav-item-active'))
            {
              $("#requests_count").html(count);
              $("#requests_count_all").html(count);
            }

          }

        });
      }

      function getMessagesCount()
      {
        $.get('{{url('')}}/count/unseenmessages',function(count)
        {

          if(count>0)
          {

              $("#messages_count").html(count);
              $("#messages_count_all").html(count);


          }

        });
      }


      getNotiCount();
      getRequestsCount();
      getMessagesCount();

      async function removeNoti(link)
      {
        event.stopPropagation();
        const id=link.dataset.notiId;
        let response=await fetch('{{url('')}}/notifications/'+id,{
          method:'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }

        });

        if(response.ok)
        {
          document.querySelectorAll(".noti_"+id).forEach(noti=>{noti.remove();});
        }
      }

      async function removeChat(btn)
      {
        event.stopPropagation();

        if(confirm('Are you sure?\nAll messages will be lost!'))
        {
          const id=btn.dataset.roomId;
          let response=await fetch('{{url('')}}/rooms/'+id,{
            method:'DELETE',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

          });

          if(response.ok)
          {
            document.querySelectorAll(".chat_"+id).forEach(chat=>{chat.remove();});
          }
        }

      }

        //getAllNoti();


      let id={{ Auth::id() }};

      function getNotiRemainder()
      {
        let elem=document.createElement('li');
        elem.className="dropdown-item text-center text-success";
        elem.id="new_noti";
        elem.innerHTML=`<a href="#" id="noti_remind" onclick="getUnreadNotifications(this)">1 new notification</a>`;
        return elem;
      }

      function getNotiRemainderForAll()
      {
        let elem=document.createElement('p');
        elem.className="text-center text-success";
        elem.id="new_noti_all";
        elem.innerHTML=`<a href="#" id="noti_remind_all" onclick="getUnreadNotifications(this)">1 new notification</a>`;
        return elem;
      }

      function getUnreadNotifications(link)
      { let new_noti=link.parentNode;
        $.get('{{ url('/get/unreadnotifications') }}',function(data)
          {
            let divfeed=document.createElement('div');
            $.each(data,function(index, msg) {
                let wrap=document.createElement('div');
                divfeed.append(createNoti(msg,wrap));
                new_noti.after(divfeed);
                new_noti.remove();

            });




          });
      }

      function getUnreadRequests(link)
      { let new_requests=link.parentNode;
        $.get('{{ url('/get/unreadrequests') }}',function(data)
          {
            let divfeed=document.createElement('div');
            $.each(data,function(index, msg) {
                let wrap=document.createElement('div');
                divfeed.append(createRequest(msg,wrap));
                new_requests.after(divfeed);
                new_requests.remove();

            });




          });
      }

      function getRequestsRemainder()
      {
        let elem=document.createElement('li');
        elem.className="dropdown-item text-center text-success";
        elem.id="new_requests";
        elem.innerHTML=`<a href="#" id="requests_remind" onclick="getUnreadRequests(this)">1 new request</a>`;
        return elem;
      }

      function getRequestsRemainderForAll()
      {
        let elem=document.createElement('p');
        elem.className="text-center text-success";
        elem.id="new_requests_all";
        elem.innerHTML=`<a href="#" id="requests_remind_all" onclick="getUnreadRequests(this)">1 new request</a>`;
        return elem;
      }

      Echo.private('App.User.'+id).notification((noti) => {
        if(!$("#noti_count").html())
          $("#noti_count").html('0');

        let count=parseInt($("#noti_count").html());
        count+=1;


        if($('#link-to-noti').hasClass('nav-item-active'))
        {
          if(count==1)
            $("#noti_menu_all").prepend(getNotiRemainderForAll());
          else
            $("#noti_remind_all").html(count+" new notifications");
        }
        else
        {
          if(count==1)
            $("#noti_menu").prepend(getNotiRemainder());
          if($("#notifications_trigger").hasClass('show'))
          {


            $("#noti_remind").html(count+" new notifications");
          }
          else
          {


            $("#noti_count").html(count);
            $("#noti_count_all").html(count);
          }

        }

      }).listen('FriendRequested',(data)=>{
        if(!$("#requests_count").html())
          $("#requests_count").html('0');

        let count=parseInt($("#requests_count").html());
        count+=1;


        if($('#link-to-requests').hasClass('nav-item-active'))
        {
          if(count==1)
            $("#requests_menu_all").prepend(getRequestsRemainderForAll());
          else
            $("#requests_remind_all").html(count+" new requests");
        }
        else
        {

          if(count==1)
            $("#requests_menu").prepend(getrequestsRemainder());

          if($("#requests_trigger").hasClass('show'))
          {


            $("#requests_remind").html(count+" new requests");
          }
          else
          { $("#requests_count").html(count);
            $("#requests_count_all").html(count);
          }

        }
      }).listen('newMessage',data=>{
          if(data.count==0)
            data.count='';

        @isset($room_id)
          if(data.message.room_id=='{{$room_id}}')
          {
            let new_message=document.createElement('div');
            chatbox.append(createNewMessage(new_message,data.message,true));
            fetch('{{url('')}}/seen/to/'+data.message.id,{
              method: 'PATCH',
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }

            });

            previous_id=data.message.sender_id;
          }
          else
          {
            document.querySelector('#messages_count').innerHTML=data.count;
            document.querySelector('#messages_count_all').innerHTML=data.count;

          }

        @else

          $("#messages_count").html(data.count);
          $("#messages_count_all").html(data.count);

          let created_at=getTimeDiff(data.message.created_at);

          if($('#link-to-messages').hasClass('nav-item-active'))
          {
            let chat=document.querySelector("#chat_all_"+data.message.room_id);
            let chats=document.querySelector("#messages_menu_all");

            if(chat)
            {
              let msg_count=chat.querySelector('#msg_count');
              if(msg_count)
                msg_count.textContent=parseInt(msg_count.textContent)+1;
              chat.querySelector("#latest_msg").innerHTML=data.message.message;
              chat.querySelector("#created_at").innerHTML=created_at;
              chats.prepend(chat);

            }
            else{
              let wrap=document.createElement('div');
              wrap.id="chat_all_"+data.message.room_id;
              chats.prepend(createMessage(data.message,data.sender,data.profile_pic,data.count,wrap));
            }
          }
          else if($("#messages_trigger").hasClass('show'))
          {
            let chat=document.querySelector("#chat_"+data.message.room_id);
            let chats=document.querySelector("#messages_menu");

            if(chat)
            {
              let msg_count=chat.querySelector('#msg_count');
              if(msg_count)
                msg_count.textContent=parseInt(msg_count.textContent)+1;
              chat.querySelector("#latest_msg").innerHTML=data.message.message;
              chat.querySelector("#created_at").innerHTML=created_at;
              chats.prepend(chat);

            }
            else{
              let wrap=document.createElement('div');
              wrap.id="chat_"+data.message.room_id;
              chats.prepend(createMessage(data.message,data.sender,data.profile_pic,data.count,wrap));
            }
          }

        @endisset
      });

      document.querySelector("#notifications").addEventListener('show.bs.dropdown',()=>{
        $('#noti_count').html('');
        $('#noti_count_all').html('');
        getNoti();

      },{once:true});

      document.querySelector("#requests").addEventListener('show.bs.dropdown',()=>{
        $('#requests_count').html('');
        $('#requests_count_all').html('');
        getRequests();

      },{once:true});

      document.querySelector("#messages").addEventListener('show.bs.dropdown',()=>{
        getMessages();

      },{once: true});

      $("#notifications").on('shown.bs.dropdown',function(){

                                            if($('#noti_count').html()!='')
                                            {
                                              getUnreadNotifications(document.querySelector("#noti_remind"));
                                              $('#noti_count').html('');
                                              $('#noti_count_all').html('');
                                            }
                                          });

$("#requests").on('shown.bs.dropdown',function(){
  if($('#requests_count').html()!=''){
    getUnreadRequests(document.querySelector("#requests_remind"));
    $('#requests_count').html('');
    $('#requests_count_all').html('');
  }
});


    </script>

  </body>
</html>
