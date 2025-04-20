<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://connectshare.xyz/">
    <meta property="og:site_name" content="Connect">
    <meta property="og:image" itemprop="image primaryImageOfPage" content="https://connectonline.space/favicon.ico">
    <meta name="description" content="Connect is a social network application where you can post how you feel, what's on your mind with your friends, public or only you.">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <style>
    .btn-success{
      background: #034b00;
      color: white;
    }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand font-weight-bold" href="{{ url('/') }}" style="color: #034b00;">
                  <em>
                    {{ config('app.name', 'Laravel') }}
                  </em>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
