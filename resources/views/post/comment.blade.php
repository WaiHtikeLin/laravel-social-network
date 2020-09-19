<div class="d-flex">
  <div class="mr-2">
    <a href="{{url('')}}/user/profile/${comment.owner.id}">
      <img src="{{ asset('storage/profile_pics/${comment.owner.pics[0].name}') }}" alt=""
      style="height:3em;width:3em" class="rounded-circle">
    </a>

  </div>

  <div class="flex-fill">
    <p><a href="{{url('')}}/user/profile/${comment.owner.id}"
      class="text-decoration-none react-link text-dark"><strong>${comment.owner.name}</strong></a></p>
      <p class="mt-0 text-break" style="white-space:pre-wrap">${comment.texts}</p>
      <p class="d-flex">
        <div class="mr-auto">
          <span class="mr-2">${comment.created_at}</span>
          <a href="#" class="text-decoration-none mr-2 ${comment.like_status}" id="comment_like_${comment.id}" onclick="toggleLikeComment(this)" data-comment-id='${comment.id}'>
          Like</a>
          <a href="#add-reply_${comment.id}" class="text-decoration-none mr-2"
          data-toggle='collapse' aria-expanded="false" aria-controls="textBoxToReply">Reply</a>

          ${canUpdateComment(comment.id,comment.post_id,comment.user_id)}
        </div>



        <button type="button" name="button" class="btn btn-light clean-white border-0"
        data-comment-id='${comment.id}'>
          <span data-comment-id='${comment.id}' class="comment-like-count">${comment.likers_count}</span>
          <img src="{{url('')}}/img/like.png" alt="" class="react-count">
        </button>

        <button type="button" name="button" class="btn btn-light clean-white border-0"
        data-feed-id='${id}' data-comment-id='${comment.id}' data-toggle='collapse' data-target="#add-reply_${comment.id}" aria-expanded="false" aria-controls="replies"
        onclick="showReplies(this)" >
          <span data-comment-id='${comment.id}' class="comment-reply-count">${comment.replies_count}</span>
          <img src="{{url('')}}/img/comment.png" alt="" class="react-count">
        </button>
      </p>

      <div class="collapse reply-collapse" data-comment-id="${comment.id}" id="add-reply_${comment.id}">
        <div class="replies-collapse" data-comment-id="${comment.id}">

        </div>

        <form class="reply-form" action="{{url('')}}/posts/${id}/comments/${comment.id}/replies" method="post"
        data-feed-id="${id}" data-comment-id='${comment.id}' onsubmit="addReply(this)">
          @csrf
          @method('post')
          <div class="input-group">
            <input class="form-control rounded-pill mr-2"
            type="text" name="texts" value="" placeholder="Enter reply..."
            aria-label="Text box to reply" aria-describedby="send-reply">
            <input class="btn btn-light rounded-pill" type="submit" name="" value="Send">
          </div>

        </form>
      </div>

  </div>
</div>
