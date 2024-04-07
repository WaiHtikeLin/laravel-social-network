@isset($profile)
  <div class="d-flex">

    <a href="{{url('')}}/user/profile/{{$user->id}}">
      <img src="{{asset("storage/profile_pics/$profile_pic")}}" alt="" class="rounded-circle" style="height:3em;width:3em">
    </a>

    <div class="ml-2 mr-auto">
      <p><a href="{{url('')}}/user/profile/{{$user->id}}" class="text-decoration-none react-link text-dark"><strong>{{$user->name}}</strong></a>
      <br><small>${feed.created_at}&nbsp;&nbsp;<img src="{{asset('img')}}/${feed.privacy}.png" alt="" style="height:1em;width:1em" class="privacy"></small></p>
    </div>



@else
  <div class="d-flex">
    <a href="{{url('')}}/user/profile/${feed.owner.id}">
      <img src="{{asset('storage/profile_pics/')}}/${feed.owner.pics[0].name}" alt="" class="rounded-circle" style="height:3em;width:3em">
    </a>

    <div class="ml-2 mr-auto">
      <p><a href="{{url('')}}/user/profile/${feed.owner.id}"
        class="text-decoration-none react-link text-dark"><strong>${feed.owner.name}</strong></a><br>
        <small>${feed.created_at}&nbsp;&nbsp;<img src="{{asset('img')}}/${feed.privacy}.png" alt="" style="height:1em;width:1em" class="privacy"></small></p>
    </div>
@endisset

<div>
  @isset($copied)
  ${renderCopyButton(post.id,feed.copyable)}
  @else
  ${renderCopyButton(feed.id,feed.copyable)}
  @endisset
  <button type="button" name="button" class="btn btn-light clean-white border-0 ${feed.favorite_status}"
  data-feed-id='${feed.id}' data-toggle="tooltip" data-placement="left" title="Favorite post" onclick="toggleFavorite(this)">
  <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
  </svg>
</button>

@if(isset($mine) || isset($mypost))
  <button type="button" name="button" class="btn btn-light clean-white border-0 post-edit"
  data-feed-id='${feed.id}' data-post-id="@isset($copied) ${post.id} @endisset" onclick="getPostToEdit(this)">
    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
    </svg>
  </button>

  <button type="button" name="button" class="btn btn-light clean-white border-0 post-delete"
  data-feed-id='${feed.id}' onclick="deletePost(this)">
      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
    </svg>
  </button>

@endif



</div>

</div>

@isset($copied)
${renderCopyModal(post.id,feed.copyable)}
@else
${renderCopyModal(feed.id,feed.copyable)}
@endisset

<div class="post-body">
  <p style="white-space:pre-wrap" class="text-break">${feed.texts}</p>
</div>


@isset($shared)

<div class="card" onclick="location.href='{{url('')}}/show/post/${post.id}'" style="cursor:pointer">
  <div class="card-body">

      <div class="d-flex">
        <a href="{{url('')}}/user/profile/${post.owner.id}">
          <img src="{{asset('storage/profile_pics/')}}/${post.owner.pics[0].name}" alt="" class="float-left rounded-circle" style="height:3em;width:3em">
        </a>

        <div class="ml-2 mr-auto">
          <p><a href="{{url('')}}/user/profile/${post.owner.id}"
            class="text-decoration-none react-link text-dark"><strong>${post.owner.name}</strong></a><br>
          <small>${post.created_at}&nbsp;&nbsp;<img src="{{asset('img')}}/${post.privacy}.png" alt="" style="height:1em;width:1em"></small></p>
        </div>

        <div>
          ${renderCopyButton(post.id,post.copyable)}
        </div>


      </div>

      ${renderCopyModal(post.id,post.copyable)}



      <div class="post-body" data-feed-id='${post.id}'>
        <p style="white-space:pre-wrap" class="text-break">${post.texts}</p>
      </div>

      <div class="clearfix">
        <div class="float-right">
          ${renderShareButton(post.id,post.shareable)}
        </div>
      </div>

  </div>

  ${renderShareModal(post.id,post.shareable)}
</div>
@endisset

@isset($copied)
<div>
  <p style="white-space:pre-wrap" class="text-break">${post.texts}</p>
  <p>
    <strong><a href="{{url('')}}/user/profile/${post.owner.id}"
    class="text-decoration-none react-link text-dark">${post.owner.name}</a></strong>
    <br>
    <span>${post.created_at}</span>
    <br>
    <a href="{{url('')}}/show/post/${post.id}">See original post</a>
  </p>
</div>
@endisset




<p class="mb-2">
    <button type="button" name="button" class="btn btn-light clean-white border-0"
    data-feed-id='${feed.id}' onclick="getReactions(this)">
      <span data-feed-id='${feed.id}' class="reaction-count1">${feed.reactions_count}</span>
      <img src="{{url('')}}/img/emotions.png" alt="" style="height:1.5em;width:1.5em">
    </button>

    <button type="button" name="button" class="btn btn-light clean-white border-0"
    data-feed-id='${feed.id}' onclick="showComments(this)">
      <span class="comment-count" data-feed-id='${feed.id}'>${feed.comments_count}</span>
      <img src="{{url('')}}/img/comment.png" alt="" class="react-count">
    </button>

    <button type="button" name="button" class="btn btn-light clean-white border-0"
    data-feed-id='${feed.id}' onclick="showShares(this)">
      <span class="share-count" data-feed-id='${feed.id}'>${feed.shares_count}</span>
      <img src="{{url('')}}/img/share.png" alt="" class="react-count">
    </button>

</p>

  <div class="modal fade likes-box" id="like_modal_${feed.id}" tabindex="-1"
  aria-labelledby="shares" aria-hidden="true" data-feed-id='${feed.id}'>
      <div class="modal-dialog modal-fullscreen-md-down">
      <div class="modal-content">
        <div class="modal-header p-0">
          <button class="btn btn-light border-0 clean-white w-100 text-left py-2"
          data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&lt;</span>
            <span data-feed-id='${feed.id}' class="reaction-count2 ml-2">${feed.reactions_count}</span>
            <img src="{{url('')}}/img/emotions.png" alt="" style="height:1.5em;width:1.5em">
          </button>

          <!-- <div class="w-100" data-dismiss="modal">

          </div> -->
        </div>
        <div class="modal-body likes-body" data-feed-id='${feed.id}' style="height:500px;overflow-y:auto">
        </div>



      </div>
    </div>
  </div>

  <div class="modal fade shares-box" tabindex="-1"
  aria-labelledby="shares" aria-hidden="true" data-feed-id='${feed.id}'>
      <div class="modal-dialog modal-fullscreen-md-down">
      <div class="modal-content">
        <div class="modal-header p-0">
          <button class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&lt;</span>
            <span data-feed-id='${feed.id}' class="share-count ml-2">${feed.shares_count}</span>
            <img src="{{url('')}}/img/share.png" alt="" class="react-count">
          </button>

          <!-- <div class="w-100" data-dismiss="modal">

          </div> -->
        </div>
        <div class="modal-body shares-body" data-feed-id='${feed.id}' style="height:500px;overflow-y:auto">
        </div>



      </div>
    </div>
  </div>
  <div class="d-flex mb-2 w-50" role="group" aria-label="Reactions" id="reactions_${feed.id}">

      <div class="dropup flex-fill">
        <button type="button" class="btn btn-light reaction-button w-100" data-feed-id='${feed.id}'
        data-toggle="dropdown" aria-expanded="false">
          ${feed.reaction_status}</button>
        <ul class="dropdown-menu reactions" data-feed-id="${feed.id}" style="width:250px">
          @include('components.emotions')
        </ul>
      </div>


    <button type="button" class="btn btn-light comment-button flex-fill"
    data-feed-id='${feed.id}' aria-controls="textBoxToComment" onclick="showComments(this)">Comment</button>

    ${renderShareButton(feed.id,feed.shareable)}
  </div>

  <div class="collapse w-50" id="add-emoji_${feed.id}">
    <form method="post" data-feed-id='${feed.id}' onsubmit="addReactionFromForm(this)">
      @csrf
      @method('patch')
      <div class="input-group">
        <input class="form-control rounded-pill mr-2"
        type="text" name="emoji" value="" placeholder="Enter emoji...">
        <input class="btn btn-light rounded-pill" type="submit" name="" value="Ok">
      </div>
      <p class="text-danger"></p>
    </form>
  </div>


  <div class="collapse comment-collapse" data-feed-id="${feed.id}" id="add-comment_${feed.id}">
    <form class="comment-form" action="{{url('')}}/send/comment" method="post"
    data-feed-id='${feed.id}' onsubmit="addComment(this,'collapse')">
      @csrf
      @method('post')
      <div class="input-group">
        <input class="form-control rounded-pill mr-2 comment"
        type="text" name="comment" value="" placeholder="Enter comment..."
        aria-label="Text box to comment" aria-describedby="send-comment">
        <input class="btn btn-light rounded-pill" type="submit" name="" value="Send">
      </div>

    </form>
  </div>
<div class="modal fade comments-box" tabindex="-1"
aria-labelledby="comments" aria-hidden="true" data-feed-id='${feed.id}'>
    <div class="modal-dialog modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header p-0">
        <button class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&lt;</span>
          <span class="comment-count ml-2" data-feed-id='${feed.id}'>${feed.comments_count}</span>
          <img src="{{url('')}}/img/comment.png" alt="" class="react-count">
        </button>

        <!-- <div class="w-100" data-dismiss="modal">

        </div> -->
      </div>
      <div class="modal-body comments-body" data-feed-id='${feed.id}' style="height:500px;overflow-y:auto">
      </div>

      <div class="modal-footer">
          <form class="comment-form w-100" action="{{url('')}}/send/comment" method="post"
          data-feed-id='${feed.id}' onsubmit="addComment(this,'modal')">
            @csrf
            @method('post')
            <div class="input-group">
              <input class="form-control rounded-pill mr-2 comment"
              type="text" name="comment" value="" placeholder="Enter comment..."
              aria-label="Text box to comment" aria-describedby="send-comment">
              <input class="btn btn-light rounded-pill" type="submit" name="" value="Send">
            </div>

          </form>
      </div>

    </div>
  </div>
</div>

<div class="collapse comments-collapse" data-feed-id='${feed.id}'>
</div>


${renderShareModal(feed.id,feed.shareable)}
