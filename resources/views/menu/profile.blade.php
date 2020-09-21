@extends('layouts.master')

@section('link-to-profile')
side-nav-active
@endsection

@if($user->id==1)
  @section('link-to-contact')
  side-nav-active
  @endsection
@endif

@section('main')

<div class="col-12 col-md-6">
  <div class="d-flex flex-column mb-5">

    <img src="{{asset("storage/profile_pics/$profile_pic")}}"
    class="rounded-circle mb-2 align-self-center" alt=""
    style="height:5em;width:5em" id="my-profile-pic">

    @isset($mine)
    <p class="mb-2 align-self-center">

        <a href="#change-profile-pic" data-toggle="collapse" aria-expanded="false">Change</a>
    </p>

    <div class="collapse align-self-center mb-2" id="change-profile-pic">
      <form action="{{'/change/profile_pic'}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <input type="file" name="profile_pic" id="profile_pic"
        onchange="document.getElementById('my-profile-pic').src = window.URL.createObjectURL(this.files[0])" required>
        <input type="submit" value="Change">
      </form>
    </div>

    @endisset

    <p class="mb-2 align-self-center">
      <strong>{{$user->name}}</strong>
      @isset($mine)
        <a href="#edit-name" class="ml-2" data-toggle="collapse" aria-expanded="false">Edit</a>
      @endisset</p>

    @isset($mine)
    <div class="collapse align-self-center" id="edit-name">

      <form action="{{url('/change/name')}}" method="post">

        @csrf
        @method('patch')
        <input type="text" name="name" placeholder="Enter new name" required>
        <button type="submit" name="button">Update</button>
      </form>

    </div>
    @endisset



    @if($about && (($me->canViewInfo($about['privacy'],$info->user_id) || isset($mine))))
    <p class="mb-2 align-self-center" id="about-info">{{$about['name']}}</p>
    @endif



    @empty($mine)
    <div class="btn-group align-self-center" role="group" aria-label="profile options">
      <div id="friendship">
        @include('user.friendshipstatus',['status'=>$friendship_status])
      </div>

      <div id="follow">
        @include('user.followstatus',['status'=>$follow_status])
      </div>


      <button type="button" name="button" class="btn btn-primary mr-1 rounded-pill" onclick="location.href='{{url('')}}/chat/to/{{$user->id}}';">Message</button>
      <button type="button" name="button" class="btn btn-danger mr-1 rounded-pill" onclick="block()">Block</button>
    </div>



    @if($friendship_status=='Accept')

    <div class="align-self-center" id="friend-request-in-profile">
      <p class="text-center">{{$user->name}} sent you a friend request</p>
      <p class="text-center">
        <div class="btn-group" role="group" aria-label="request options">
          <button type="button" name="button" class="btn btn-success mr-1 rounded-pill" onclick="acceptFriend()">Accept</button>
          <button type="button" name="button" class="btn btn-danger mr-1 rounded-pill" onclick="rejectFriend()">Reject</button>
        </div>
      </p>

    </div>

    @endif
    @endempty
  </div>



  <p>Overview</p>

<div class="about clean-white px-2 mb-2">

  @if($edu && (($me->canViewInfo($edu['privacy'],$info->user_id) || isset($mine))))
  <p><img src="{{asset("img/book.png")}}" alt="" class="info-icon mr-2">{{$edu['name']}}</p>
  @endif

  @if($work && (($me->canViewInfo($work['privacy'],$info->user_id) || isset($mine))))
  <p><img src="{{asset("img/work.png")}}" alt="" class="info-icon mr-2">{{$work['name']}}</p>
  @endif

  @if($address && (($me->canViewInfo($address['privacy'],$info->user_id) || isset($mine))))
  <p><img src="{{asset("img/location.png")}}" alt="" class="info-icon mr-2">{{$address['name']}}</p>
  @endif

  @if($email && (($me->canViewInfo($email['privacy'],$info->user_id) || isset($mine))))
  <p><img src="{{asset("img/email.png")}}" alt="" class="info-icon mr-2">{{$email['name']}}</p>
  @endif

  @if($site && (($me->canViewInfo($site['privacy'],$info->user_id) || isset($mine))))
  <p><img src="{{asset("img/global.png")}}" alt="" class="info-icon mr-2">{{$site['name']}}</p>
  @endif

  @if($phone && (($me->canViewInfo($phone['privacy'],$info->user_id) || isset($mine))))
  <p><img src="{{asset("img/phone.png")}}" alt="" class="info-icon mr-2">{{$phone['name']}}</p>
  @endif

  @if($me->canViewInfo($settings->friends_privacy,$settings->user_id) || isset($mine))
    @if($user->friends_count || $user->accepted_friends_count)
    <p><a href="{{url("/users/$user->id/friends")}}">{{$user->friends_count+$user->accepted_friends_count}} friend(s)</a></p>
    @endif
  @endif

  @if($me->canViewInfo($settings->followers_privacy,$settings->user_id) || isset($mine))
    @if($user->followers_count)
    <p><a href="{{url("/users/$user->id/followers")}}">{{$user->followers_count}} follower(s)</a></p>
    @endif
  @endif

  @if($me->canViewInfo($settings->following_privacy,$settings->user_id) || isset($mine))
    @if($user->following_count)
    <p><a href="{{url("users/$user->id/following")}}">{{$user->following_count}} following</a></p>
    @endif
  @endif

  @isset($mine)
    @if($user->sentRequests_count)
      <p><a href="{{url('sentrequests')}}">{{$user->sentRequests_count}} sent request(s)</a></p>
    @endif
  @endisset

  <div style="height:50px" class="d-flex justify-content-center align-items-center">
    <a data-toggle="collapse" href="#all_informations"
    aria-expanded="false" aria-controls="all informations">More</a>
  </div>
</div>

@isset($mine)
<div class="collapse" id="all_informations">



  <p class="p-2">About
    <a href="#add-about" class="ml-2 @if($info->about) d-none @endif" data-toggle="collapse" aria-expanded="false"
    aria-controls="add about">Add</a>

  </p>

  <div class="collapse" id="add-about">
    @include('user.addinfo',['title'=>'about','wrap'=>'#about-content'])
  </div>

@if($info->about)
<div class="clean-white mb-2 p-2">


  <div id="about-content">

    <div>
      <p>{{$info->about['name']}}<img src="{{asset('img')}}/{{$info->about['privacy']}}.png" alt="" class="ml-2 info-privacy">
      <a href="#edit-about" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
      <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="about" data-id="">Delete</a>
    </p>
      <div class="collapse" id="edit-about">
        @include('user.addinfo',['edit'=>true,'name'=>$info->about['name'],'privacy'=>$info->about['privacy'],'id'=>'','title'=>'about'])
      </div>
    </div>



</div>



</div>
@endif



  <p>
    Education
    <a href="#add-edu" class="ml-2 mr-2" data-toggle="collapse" aria-expanded="false" aria-controls="add edu">Add</a>

  </p>

  <div class="collapse" id="add-edu">
    @include('user.addinfo',['title'=>'education','wrap'=>'#edu-list'])
  </div>





  <div class="clean-white mb-2">


    <ul id="edu-list">
  @if($info->education)

    @foreach($info->education as $id=>$edu)

    <div>
      <li id="education_{{$id}}">{{$edu['name']}}
        <img src="{{asset('img')}}/{{$edu['privacy']}}.png" alt="" class="ml-2 info-privacy">
        <a href="#edit_education_{{$id}}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
        <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="education" data-id={{$id}}>Delete</a>
      </li>

      <div class="collapse" id="edit_education_{{$id}}">
        @include('user.addinfo',['edit'=>true,'name'=>$edu['name'],'privacy'=>$edu['privacy'],'title'=>'education'])
      </div>
    </div>

    @endforeach

    @endif
  </ul>
</div>



<p>
  Work
  <a href="#add-work" class="ml-2" data-toggle="collapse" aria-expanded="false" aria-controls="add work">Add</a>
</p>

<div class="collapse" id="add-work">
  @include('user.addinfo',['title'=>'work','wrap'=>'#work-list'])
</div>

  <div class="clean-white mb-2">

    <ul id="work-list">

@if($info->work)
    @foreach($info->work as $id=>$w)

    <div>
      <li id="work_{{$id}}">{{$w['name']}}

        <img src="{{asset('img')}}/{{$w['privacy']}}.png" alt="" class="ml-2 info-privacy">
        <a href="#edit_work_{{$id}}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
        <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="work" data-id={{$id}}>Delete</a>
      </li>

      <div class="collapse" id="edit_work_{{$id}}">
        @include('user.addinfo',['edit'=>true,'name'=>$w['name'],'privacy'=>$w['privacy'],'title'=>'work'])
      </div>
    </div>


    @endforeach
  @endif
  </ul>
</div>



<p>
  Address
  <a href="#add-address" class="ml-2" data-toggle="collapse" aria-expanded="false" aria-controls="add address">Add</a>
</p>

<div class="collapse" id="add-address">
  @include('user.addinfo',['title'=>'address','wrap'=>'#address-list'])
</div>

  <div class="clean-white mb-2">

    <ul id="address-list">
@if($info->address)

    @foreach($info->address as $id=>$a)
      <div>
        <li id="address_{{$id}}">{{$a['name']}}
        <img src="{{asset('img')}}/{{$a['privacy']}}.png" alt="" class="ml-2 info-privacy">
        <a href="#edit_address_{{$id}}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
        <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="address" data-id={{$id}}>Delete</a>
      </li>

      <div class="collapse" id="edit_address_{{$id}}">
        @include('user.addinfo',['edit'=>true,'name'=>$a['name'],'privacy'=>$a['privacy'],'title'=>'address'])
      </div>
      </div>


    @endforeach
    @endif
  </ul>
  </div>



<p>
  Email
  <a href="#add-email" class="ml-2" data-toggle="collapse" aria-expanded="false" aria-controls="add email">Add</a>
</p>

<div class="collapse" id="add-email">
  @include('user.addinfo' ,['title'=>'email','wrap'=>'#email-list'])
</div>

  <div class="clean-white mb-2">

    <ul id="email-list">

@if($info->emails)
    @foreach($info->emails as $id=>$e)

    <div>
      <li id="email_{{$id}}">{{$e['name']}}

        <img src="{{asset('img')}}/{{$e['privacy']}}.png" alt="" class="ml-2 info-privacy">
        <a href="#edit_email_{{$id}}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
        <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="emails" data-id={{$id}}>Delete</a>
      </li>

      <div class="collapse" id="edit_email_{{$id}}">
        @include('user.addinfo',['edit'=>true,'name'=>$e['name'],'privacy'=>$e['privacy'],'title'=>'email'])
      </div>
    </div>

    @endforeach

    @endif
  </ul>
</div>


<p>
  Website
  <a href="#add-site" class="ml-2" data-toggle="collapse" aria-expanded="false" aria-controls="add site">Add</a>
</p>

<div class="collapse" id="add-site">
  @include('user.addinfo',['title'=>'site','wrap'=>'#site-list'])
</div>

  <div class="clean-white mb-2">

    <ul id="site-list">

@if($info->websites)
    @foreach($info->websites as $id=>$w)
    <div>
      <li id="site_{{$id}}">{{$w['name']}}
        <img src="{{asset('img')}}/{{$w['privacy']}}.png" alt="" class="ml-2 info-privacy">
        <a href="#edit_site_{{$id}}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
        <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="websites" data-id={{$id}}>Delete</a>
      </li>

      <div class="collapse" id="edit_site_{{$id}}">
        @include('user.addinfo',['edit'=>true,'name'=>$w['name'],'privacy'=>$w['privacy'],'title'=>'site'])
      </div>
    </div>

    @endforeach
  @endif
  </ul>
</div>



<p>
  Phone
  <a href="#add-phone" class="ml-2" data-toggle="collapse" aria-expanded="false" aria-controls="add phone">Add</a>
</p>

<div class="collapse" id="add-phone">
  @include('user.addinfo',['title'=>'phone','wrap'=>'#phone-list'])
</div>


  <div class="clean-white mb-2">

    <ul id="phone-list">
@if($info->ph_numbers)

    @foreach($info->ph_numbers as $id=>$p)

    <div>
      <li id="phone_{{$id}}">{{$p['name']}}

        <img src="{{asset('img')}}/{{$p['privacy']}}.png" alt="" class="ml-2 info-privacy">
        <a href="#edit_phone_{{$id}}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
        <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="ph_numbers" data-id={{$id}}>Delete</a>
      </li>

      <div class="collapse" id="edit_phone_{{$id}}">
        @include('user.addinfo',['edit'=>true,'name'=>$p['name'],'privacy'=>$p['privacy'],'title'=>'phone'])
      </div>
    </div>

    @endforeach
  @endif
  </ul>
  </div>


<p class="p-2">Bio
  <a href="#add-bio" class="ml-2 @if($info->bio) d-none @endif" data-toggle="collapse" aria-expanded="false" aria-controls="add bio">Add</a>
</p>

<div class="collapse" id="add-bio">
  @include('user.addinfo',['title'=>'bio','wrap'=>'#bio-content'])
</div>




  <div class="clean-white mb-2">


<div id="bio-content">

  @if($info->bio)
  <div>
    <p>{{$info->bio['name']}}
    <img src="{{asset('img')}}/{{$info->bio['privacy']}}.png" alt="" class="ml-2 info-privacy">
    <a href="#edit_bio_{{$id}}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
    <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="bio" data-id="">Delete</a>
  </p>

    <div class="collapse" id="edit_bio_{{$id}}">
      @include('user.addinfo',['edit'=>true,'name'=>$info->bio['name'],'privacy'=>$info->bio['privacy'],'id'=>'','title'=>'bio'])
    </div>
  </div>


  @endif
</div>

  </div>




</div>

@else
<div class="collapse" id="all_informations">

  @if($info->education)
  <div class="clean-white mb-2">
    <p style="border-bottom:2px solid green" class="p-2">Education</p>
    <ul>


    @foreach($info->education as $edu)
      @if($me->canViewInfo($edu['privacy'],$info->user_id))
      <li>{{$edu['name']}}</li>
      @endif
    @endforeach
  </ul>
</div>
@endif

  @if($info->work)
  <div class="clean-white mb-2">
    <p style="border-bottom:2px solid green" class="p-2">Work</p>
    <ul>


    @foreach($info->work as $w)
      @if($me->canViewInfo($w['privacy'],$info->user_id))
      <li>{{$w['name']}}</li>
      @endif
    @endforeach
  </ul>
</div>
@endif

@if($info->address)
  <div class="clean-white mb-2">
    <p style="border-bottom:2px solid green" class="p-2">Address</p>
    <ul>


    @foreach($info->address as $a)
      @if($me->canViewInfo($a['privacy'],$info->user_id))
      <li>{{$a['name']}}</li>
      @endif
    @endforeach
  </ul>
  </div>
@endif

@if($info->emails)
  <div class="clean-white mb-2">
    <p style="border-bottom:2px solid green" class="p-2">Emails</p>
    <ul>


    @foreach($info->emails as $e)
      @if($me->canViewInfo($e['privacy'],$info->user_id))
      <li>{{$e['name']}}</li>
      @endif
    @endforeach
  </ul>
  </div>
@endif

@if($info->websites)
  <div class="clean-white mb-2">
    <p style="border-bottom:2px solid green" class="p-2">Websites</p>
    <ul>


    @foreach($info->websites as $w)
      @if($me->canViewInfo($w['privacy'],$info->user_id))
      <li>{{$w['name']}}</li>
      @endif
    @endforeach
  </ul>
</div>
@endif

@if($info->ph_numbers)
  <div class="clean-white mb-2">
    <p style="border-bottom:2px solid green" class="p-2">Phone</p>
    <ul>


    @foreach($info->ph_numbers as $p)
      @if($me->canViewInfo($p['privacy'],$info->user_id))
      <li>{{$p['name']}}</li>
      @endif
    @endforeach
  </ul>
  </div>
@endif

@if($info->bio)
  <div class="clean-white mb-2">
    <p style="border-bottom:2px solid green" class="p-2">Bio</p>

    @if($me->canViewInfo($info->bio['privacy'],$info->user_id))
    <p>{{$info->bio['name']}}</p>
    @endif

  </ul>
  </div>
@endif
</div>

@endisset

@include('post.posts')

  <div class="feeds">

  </div>
</div>
  <script>




      async function unfriend()
      {
        event.preventDefault();

        let response=await fetch('{{url("/unfriend/to/$user->id")}}',{
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }

        });
        if(response.ok)
        {
          document.querySelector("#friendship").innerHTML=`@include('user.friendshipstatus',['status'=>'Add friend'])`;
        }
      }

      async function cancelRequest()
      {
        event.preventDefault();

        let response=await fetch('{{url("/cancel/request/to/$user->id")}}',{
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }

        });
        if(response.ok)
        {
          document.querySelector("#friendship").innerHTML=`@include('user.friendshipstatus',['status'=>'Add friend'])`;
        }

      }

      async function addFriend()
      {
        let response=await fetch('{{url("/request/to/$user->id")}}',{
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }

        });
        if(response.ok)
        { let result=await response.json();
          if(result=='ok')
            document.querySelector("#friendship").innerHTML=`@include('user.friendshipstatus',['status'=>'Requested'])`;
        }
      }

      async function acceptFriend()
      {
        let response=await fetch("{{url('')}}/accept/to/{{$user->id}}",{
          method: 'POST',
          headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        if(response.ok)
        {
          document.querySelector("#friend-request-in-profile").remove();
          document.querySelector("#friendship").innerHTML=`@include('user.friendshipstatus',['status'=>'Friend'])`;
        }
      }

      async function rejectFriend()
      {
        let response=await fetch("{{url('')}}/reject/to/{{$user->id}}",{
          method: 'DELETE',
          headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        if(response.ok)
        {
          document.querySelector("#friend-request-in-profile").remove();
          document.querySelector("#friendship").innerHTML=`@include('user.friendshipstatus',['status'=>'Add friend'])`;
        }
      }

      async function follow()
      {
        let response=await fetch('{{ url('/follow/to/'.$user->id) }}',{
          method: 'POST',
          headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }

        });

        if(response.ok)
        {
          document.querySelector("#follow").innerHTML=`@include('user.followstatus',['status'=>'Following'])`;
        }
      }

      async function cancelFollow()
      {
        let response=await fetch('{{ url('/cancel/follow/to/'.$user->id) }}',{
          method: 'DELETE',
          headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }

        });

        if(response.ok)
        {
          document.querySelector("#follow").innerHTML=`@include('user.followstatus',['status'=>'Follow'])`;
        }
      }


    async function block()
    {
      if(confirm('Are you sure?'))
        location.href='{{url('')}}/block/to/{{$user->id}}';
    }


    function showFeeds(page=0){

      if(page>0)
        document.querySelector("#load-more-posts").remove();

      let feeds_container=document.querySelector('.feeds');

      fetch('{{url("/get/$user->id/posts")}}/'+page).then(response=>{
        if(response.ok)
         return response.json();
        throw new Error();
      }).then(feeds=>{

        if(page<1 && feeds.length==0)
        {
          feeds_container.innerHTML=`
          <div class="d-flex vh-100">
          <p class="text-center text-muted align-self-center w-100" style="font-size:2em">No posts</p>
          </div>`;
          return false;
        }

        feeds.forEach(function(feed){

          let divfeed=document.createElement('div');
          divfeed.id=`feed_${feed.id}`;
          divfeed.append(renderFeed(feed));
          feeds_container.append(divfeed);

        });

        if(feeds.length==10)
          feeds_container.append(createLoadMore(page+1));
    });

    }

    function createLoadMore(page)
    {
      let wrap=document.createElement('p');
      wrap.className="text-center";
      wrap.id="load-more-posts";
      wrap.innerHTML=`<button class="btn btn-light" onclick="showFeeds(${page})">Load more</button>`;

      return wrap;
    }

    showFeeds();

    async function addInfo(f,title,wrap)
    { event.preventDefault();
      let response=await fetch('{{url('update')}}/'+title,{
        method:'POST',
        body: new FormData(f)

      });

      if(response.ok)
      {
        let info=await response.json();

        let w=document.querySelector(wrap);
        let elem=document.createElement('div');
        elem.innerHTML=`@include('user.infoitem')`;
        w.append(elem);

        if(title=='about' || title=='bio')
        {
          document.querySelector(`a[aria-controls="add ${title}"]`).classList.add('d-none');
          new bootstrap.Collapse(document.querySelector(`#add-${title}`)).hide();

        }

      }



    }

    async function updateInfo(f,title)
    { event.preventDefault();


      let url;
      if(title=='about' || title=='bio')
        url=`/update/${title}`;

      else
      {
        let id=f.dataset.id;
        url=`/update/${title}/${id}`;
      }

      let response=await fetch('{{url('')}}'+url,{
        method:'POST',
        body: new FormData(f)

      });

      if(response.ok)
      {
        let info=await response.json();

        let wrap=f.parentNode.parentNode;

        wrap.innerHTML=`<div>@include('user.infoitem')</div>`;


      }



    }

    async function deleteInfo(link)
    {
      event.preventDefault();
      let field=link.dataset.name;
      let id=link.dataset.id;

      let response=await fetch(`{{url('')}}/delete/${field}/${id}`,{
        method:'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }

      });

      if(response.ok)
      {

        link.parentNode.parentNode.remove();
        if(field=='about' || field=='bio')
        document.querySelector(`a[aria-controls="add ${field}"]`).classList.remove('d-none');
      }
    }
  </script>
@endsection
