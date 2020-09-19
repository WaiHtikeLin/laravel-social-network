<nav class="nav flex-column w-75">
   <a href="{{url('')}}/myprofile" class="nav-link">
     <img src="{{asset("storage/profile_pics/")}}/{{Auth::user()->getProfilePic()}}" alt="" style="height:3em;width:3em" class="rounded-circle mr-2">
    {{Auth::user()->name}}</a>
  <a class="nav-link @yield('link-to-profile') side-item" href="{{url('')}}/myprofile"><img src="{{asset('/img/profile.png')}}" alt="" class="side-menu">Profile</a>
   <a class="nav-link side-item" data-toggle="modal" href="#newPost">
     <img src="{{asset('/img/plus.png')}}" alt="" class="side-menu">
     New Post</a>
   <a class="nav-link @yield('link-to-privates') side-item" href="{{url('/posts/privates')}}">
     <img src="{{asset('/img/lock.png')}}" alt="" class="side-menu">
     Private Posts</a>
  <a class="nav-link @yield('link-to-favorites') side-item" href="{{url('/posts/favorites')}}"><img src="{{asset('/img/heart.png')}}" alt="" class="side-menu">Favorites</a>
  <a class="nav-link @yield('link-to-settings') side-item" href="{{url('/settings')}}"><img src="{{asset('/img/settings.png')}}" alt="" class="side-menu">Settings</a>

   <a class="nav-link side-item" href="{{ route('logout') }}"
      onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><img src="{{asset('/img/logout.png')}}" alt="" class="side-menu">Logout</a>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
  </form>

</nav>

  <p style="font-size:0.8em" class="mt-5 text-center">
    <a href="{{url('/privacy')}}" class="@yield('link-to-privacy')">Data Privacy</a>
    &nbsp;<a href="{{url('/terms')}}" class="@yield('link-to-terms')">Terms</a>
    &nbsp; <a href="{{url('/cookies')}}" class="@yield('link-to-cookies')">Cookies</a>
    &nbsp;<a href="{{url('/about')}}" class="@yield('link-to-about')">About</a>
    <br>
    <a href="{{url('/user/profile/1')}}" class="@yield('link-to-contact')">Contact</a>
    &nbsp; <a href="{{url('/help')}}" class="@yield('link-to-help')">Help</a> </p>

  <p class="text-center">Connect &#169;2020</p>
