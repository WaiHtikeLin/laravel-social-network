
<div class="card-body">
    <div class="clearfix">

    <a href="{{url('')}}/user/profile/${post.owner.id}">
      <img src="{{asset('storage/profile_pics/')}}/${post.owner.pics[0].name}" alt="" class="float-left rounded-circle" style="height:3em;width:3em">
    </a>

    <div class="float-left ml-2">
      <p><a href="{{url('')}}/user/profile/${post.owner.id}" class="text-decoration-none react-link text-dark stretched-link">${post.owner.name}</a></p>
      <p><span class="mr-2">Just now</span><img src="${post.privacyImg}" alt="" style="height:1em;width:1em"></p>
    </div>
    </div>
    <div class="post-body" data-feed-id='${post.id}'>
      <p>${post.texts}</p>
    </div>
</div>
