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

    <div class="clean-white d-flex">
      <a class="btn btn-link text-decoration-none" onclick="history.back();">Back</a>
      <form class="flex-fill d-flex" action="{{url('/search')}}" method="post">

          @csrf
          <input class="form-control" type="search" placeholder="Search users or posts"
          aria-label="Search" required name="search" autofocus>
          <button type="submit" name="button" class="border-0 btn btn-success">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search text-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
          </button>
      </form>
    </div>

  </body>

</html>
