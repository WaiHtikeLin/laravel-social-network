<div class="d-flex">
  <div class="mr-2">
    <a href="{{url('')}}/user/profile/${reply.owner.id}">
      <img src="{{ asset('storage/profile_pics/${reply.owner.pics[0].name}') }}" alt=""
      style="height:2em;width:2em" class="rounded-circle">
    </a>

  </div>

  <div class="flex-fill">
    <p><a href="{{url('')}}/user/profile/${reply.owner.id}"
      class="text-decoration-none react-link text-dark"><strong>${reply.owner.name}</strong></a></p>
      <p class="d-flex"><span class="mr-2">${reply.created_at}</span>
      <p class="mt-0 text-break" class="white-space:pre-wrap">${reply.texts}</p>
        <a href="#" class="text-decoration-none mr-auto ${reply.like_status}" id="reply_like_${reply.id}" onclick="toggleLikeReply(this)" data-feed-id="${post_id}"
        data-comment-id="${id}" data-reply-id='${reply.id}'>
        Like</a>
      ${canUpdateReply(reply.id,reply.comment_id,reply.user_id,post_id)}

        <a href="#" class="text-decoration-none react-link text-dark">
          <span data-reply-id='${reply.id}' class="reply-like-count">${reply.likes_count}</span>
          <img src="{{url('')}}/img/like.png" alt="" class="react-count">
        </a>
      </p>

  </div>
</div>
