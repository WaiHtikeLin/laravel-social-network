<script type="text/javascript">

function formatDate(date) {
  // let hours = date.getHours();
  // let minutes = date.getMinutes();
  // let ampm = hours >= 12 ? 'pm' : 'am';
  // hours = hours % 12;
  // hours = hours ? hours : 12; // the hour '0' should be '12'
  // minutes = minutes < 10 ? '0'+minutes : minutes;
  return `${date.getDate()}.${date.getMonth()}.${date.getFullYear()}`;
}


function getErrorView(id,container,fn)
{
  let wrap=document.createElement('div');
  wrap.className="h-100 d-flex justify-content-center align-items-center";
  let wrapinner=document.createElement('div');
  wrapinner.className="text-center";
  wrapinner.innerHTML="<p>Something went wrong!</p>";
  let action=document.createElement('button');
  action.className="btn btn-light";
  action.textContent="Try again!";
  action.onclick=()=>fn(id,container);
  return wrap.append(wrapinner.append(action));
}


function renderShareButton(id,shareable){


      if(shareable)
      {
        return `<button type="button" class="btn btn-light share-button flex-fill"
          data-toggle="modal" data-target='#shareModal_${id}' onclick="event.stopPropagation()">Share</button>`;
      }
      return '';

    }

  function renderShareModal(id,shareable){
      if(shareable)
      {
        return `@include('post.sharebox')`;
      }

      return '';

    }

  function renderCopyButton(id,copyable){
      if(copyable)
      {
        return `<button type="button" name="button" class="btn btn-light clean-white border-0"
          data-feed-id='${id}' data-toggle="tooltip" data-placement="left" title="Copy post with credit" onclick="showCopyPostModal(this)">
          <img src="{{asset('img')}}/copy.png" alt="" class="copy-post" /></button>`;
      }
      return '';
    }

  function renderCopyModal(id,copyable){
      if(copyable)
      {
        return `@include('post.copybox')`;
      }

      return '';
    }

  function showCopyPostModal(btn)
  {
    event.stopPropagation();
    const id=btn.dataset.feedId;
    let modal=new bootstrap.Modal(document.querySelector("#copyModal_"+id));
    modal.show();
  }

  function renderFeed(feed)
  {

    let divfeed=document.createElement('div');
    divfeed.className="clean-white p-2 mb-3";

      
    feed.created_at=getTimeDiff(feed.created_at);
    if(feed.reaction_status)
      feed.reaction_status=feed.reactions[0].pivot.emoji;
    else
      feed.reaction_status='React';

    if(feed.favorite_status)
      feed.favorite_status='reacted';

    if(feed.share_id)
    {
      fetch(`{{url('')}}/get/post/${feed.share_id}`).then(response=>{
        if(response.ok)
          return response.json();
        throw new Error();
      }).then(post=>{
        post.created_at=getTimeDiff(post.created_at);
        divfeed.innerHTML=`@include('post.post',['shared'=>true])`;
      }).catch(error=>{console.log(error)});
    } else if(feed.copy_id)
    {
      fetch(`{{url('')}}/get/post/${feed.copy_id}`).then(response=>{
        if(response.ok)
          return response.json();
        throw new Error();
      }).then(post=>{
        let date=new Date(Date.parse(post.created_at));
        post.created_at=formatDate(date);
        divfeed.innerHTML=`@include('post.post',['copied'=>true])`;
      }).catch(error=>{console.log(error)});
    }
    else
      divfeed.innerHTML=`@include('post.post')`;

      return divfeed;
  }

function isReactionPresent(id,status)
{
  if(status!='React')
    return `<a href="#" class="pr-3 remove-reaction" onclick="removeReaction(this)" data-feed-id="${id}">Remove reaction</a>`;
  return '';
}

function getPostToEdit(btn)
{
  const id=btn.dataset.feedId;
  const copy_id=btn.dataset.postId;
  fetch(`{{url("/get/post")}}/${id}`).then(response=>{
    if(response.ok)
      return response.json();
    throw new Error();
  }).then(feed=>{
    let editmodal="#edit-post";

    if(copy_id)
      editmodal="#edit-copy-post";

    let myModal=new bootstrap.Modal(document.querySelector(editmodal));
    myModal.show();
    let editBox=document.querySelector(editmodal);

    editBox.querySelector('form').dataset.feedId=`${id}`;
    editBox.querySelector('p').dataset.feedId=`${id}`;
    editBox.querySelector(`option[value='${feed.privacy}']`).selected=true;
    editBox.querySelector(`textarea`).value=feed.texts;

    if(feed.shareable)
      editBox.querySelector(`input[name='shareable']`).checked=true;

    if(feed.copyable)
      editBox.querySelector(`input[name='copyable']`).checked=true;




  });
}

function getCommentToEdit(link)
{
  event.preventDefault();
  const id=link.dataset.commentId;
  const post_id=link.dataset.feedId;
  fetch(`{{url("")}}/comments/get/${id}`).then(response=>{
    if(response.ok)
      return response.json();
    throw new Error();
  }).then(comment=>{

    // let comments=bootstrap.Modal.getInstance(document.querySelector(`.comments-box[data-feed-id='${feed_id}']`));
    // comments.hide();

    let myModal=new bootstrap.Modal(document.querySelector('#edit-comment'));
    myModal.show();

    let editBox=document.querySelector('#edit-comment');

    editBox.querySelector('form').dataset.commentId=id;
    editBox.querySelector('form').dataset.feedId=post_id;
    editBox.querySelector('input[type="text"]').value=comment.texts;
  });

}

function deletePost(btn)
{
  const id=btn.dataset.feedId;

  if(confirm('Are you sure?'))

    fetch(`{{url("")}}/posts/${id}`,{
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).then(response=>{
    if(response.ok)
    {
      document.querySelector(`#feed_${id}`).remove();

    }

    });
}

function deleteComment(link)
{
  event.preventDefault();
  const id=link.dataset.commentId;
  const feed_id=link.dataset.feedId;

  if(confirm('Are you sure?'))

    fetch(`{{url("")}}/comments/${id}`,{
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).then(async response=>{
    if(response.ok)
    {
      const comment_count=await response.json();
      document.querySelector(`#comment_${id}`).remove();
      document.querySelectorAll(`.comment-count[data-feed-id='${feed_id}']`).forEach(count=>{count.textContent=comment_count;});
    }

    });

}

function deleteReply(link)
{
  event.preventDefault();
  const id=link.dataset.replyId;
  const comment_id=link.dataset.commentId;

  if(confirm('Are you sure?'))

    fetch(`{{url("")}}/replies/${id}`,{
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).then(async response=>{
    if(response.ok)
    { const reply_count=await response.json();
      document.querySelector(`#reply_${id}`).remove();
      document.querySelectorAll(`.comment-reply-count[data-comment-id='${comment_id}']`).forEach(count=>{count.textContent=reply_count;});
    }
    });

}


function getReplyToEdit(link)
{
  event.preventDefault();
  const id=link.dataset.replyId;
  const post_id=link.dataset.feedId;
  fetch(`{{url("")}}/replies/get/${id}`).then(response=>{
    if(response.ok)
      return response.json();
    throw new Error();
  }).then(reply=>{



    let myModal=new bootstrap.Modal(document.querySelector('#edit-reply'));
    myModal.show();

    let editBox=document.querySelector('#edit-reply');

    editBox.querySelector('form').dataset.replyId=id;
    editBox.querySelector('form').dataset.feedId=post_id;

    editBox.querySelector('input[type="text"]').value=reply.texts;
  });

}


async function updateComment(f)
{
  event.preventDefault();
  let comment_id=f.dataset.commentId;
  let id=f.dataset.feedId;

  let response=await fetch(`{{url('')}}/comments/${comment_id}`,{
    method: 'POST',
    body: new FormData(f)

  });

  if(response.ok)
  { let comment=await response.json();

    let modal=bootstrap.Modal.getInstance(document.querySelector(`#edit-comment`));
    modal.hide();


    let divfeed=document.querySelector(`#comment_${comment.id}`);
    comment.created_at=getTimeDiff(comment.created_at);
    divfeed.innerHTML=`@include('post.comment')`;


  }
  else
    console.log('error');
}


function checkShare(f)
  { let id=f.dataset.feedId;
    if(!f.texts.value && (f.shareable.checked || f.copyable.checked))
    {
      f.querySelector(`.share-error[data-feed-id='${id}']`).innerHTML="Say something to allow sharing or copying your post";
      return false;
    }
  }

  async function updateReply(f)
  {
    event.preventDefault();
    let id=f.dataset.replyId;
    let post_id=f.dataset.feedId;

    let response=await fetch(`{{url('')}}/replies/${id}`,{
      method: 'POST',
      body: new FormData(f)

    });

    if(response.ok)
    { let reply=await response.json();

      let modal=bootstrap.Modal.getInstance(document.querySelector(`#edit-reply`));
      modal.hide();

      reply.created_at=getTimeDiff(reply.created_at);
      document.querySelectorAll(`#reply_${reply.id}`).forEach(divreply=>{
         divreply.innerHTML=`@include('post.comment.reply')`;
       });


    }
    else
      console.log('error');
  }

  async function copyWithCredit(f)
  {
    event.preventDefault();
    let id=f.dataset.feedId;

    if(!f.texts.value && f.shareable.checked)
    {
      document.querySelector(`.copy-error[data-feed-id='${id}']`).innerHTML="Say something to allow sharing or copying your post";
      return false;
    }

    let response=await fetch(`{{url('')}}/store/copy/of/${id}`,{
      method: 'POST',
      body: new FormData(f)

    });

    if(response.ok)
    { let result=await response.json();
      if(result=='ok')
      {
      // let copy_modal=bootstrap.Modal.getInstance(document.querySelector(`#copyModal_${id}`));
      // copy_modal.hide();
      location.href="{{url('/myprofile')}}";
    }
    else
      console.log('error');
    }
  }

  async function checkEdit(f)
  {
    event.preventDefault();
    let id=f.dataset.feedId;

    if(!f.texts.value && (f.shareable.checked || f.copyable.checked))
    {
      f.querySelector(`.edit-error[data-feed-id='${id}']`).innerHTML="Say something to allow sharing or copying your post";
      return false;
    }

    let response=await fetch(`{{url('')}}/update/post/${id}`,{
      method: 'POST',
      body: new FormData(f)

    });

    if(response.ok)
    { let feed=await response.json();

        let edit_modal=bootstrap.Modal.getInstance(document.querySelector('#edit-post'));
        edit_modal.hide();

      let divfeed=document.querySelector(`#feed_${feed.id}`);
      divfeed.innerHTML='';
      divfeed.append(renderFeed(feed));


    }
    else
      console.log('error');

  }

  async function checkCopyEdit(f)
  {
    event.preventDefault();
    let id=f.dataset.feedId;

    if(!f.texts.value && f.shareable.checked)
    {
      f.querySelector(`.edit-error[data-feed-id='${id}']`).innerHTML="Say something to allow sharing or copying your post";
      return false;
    }

    let response=await fetch(`{{url('')}}/update/post/${id}`,{
      method: 'POST',
      body: new FormData(f)

    });

    if(response.ok)
    { let feed=await response.json();

        let edit_modal=bootstrap.Modal.getInstance(document.querySelector('#edit-copy-post'));
        edit_modal.hide();

      let divfeed=document.querySelector(`#feed_${feed.id}`);
      divfeed.innerHTML='';
      divfeed.append(renderFeed(feed));


    }
    else
      console.log('error');
  }



  async function addReaction(btn)
  { let wrap=btn.parentNode.parentNode;
    let id=wrap.dataset.feedId;
    let reaction=btn.textContent;
    let remove_reaction=document.querySelector(`.remove-reaction[data-feed-id='${id}']`);

    if(!remove_reaction)
      document.querySelector("#reaction-action_"+id).innerHTML+=`<a href="#" class="pr-3 remove-reaction" onclick="removeReaction(this)" data-feed-id="${id}">Remove reaction</a>`;
    let response= await fetch(`{{url('')}}/add/${reaction}/to/${id}`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });

    if(response.ok)
    {
      let result = await response.json();


      wrap.previousElementSibling.textContent=result.reaction;
      document.querySelector(`.reaction-count1[data-feed-id='${id}']`).textContent=result.count;
      document.querySelector(`.reaction-count2[data-feed-id='${id}']`).textContent=result.count;
    }



  }

  async function addReactionFromForm(f)
  {
    event.preventDefault();

    let id=f.dataset.feedId;
    let wrap=document.querySelector(`.reaction-button[data-feed-id='${id}']`);
    let text=f.querySelector('input[type="text"]');
    let reaction=text.value;
    let response= await fetch(`{{url('')}}/add/${reaction}/to/${id}/from/form`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });

    if(response.ok)
    {

      let result = await response.json();
      if(result=='error_length')
      {
        f.querySelector('p').textContent="Enter only one emoji";
        return false;
      }

      if(result=='error_emoji')
      {
        f.querySelector('p').textContent="Enter only supported emoji";
        return false;
      }


      bootstrap.Collapse.getInstance(f.parentNode).hide();
      f.querySelector('p').textContent='';
      text.value='';
      wrap.textContent=result.reaction;
      document.querySelector(`.reaction-count1[data-feed-id='${id}']`).textContent=result.count;
      document.querySelector(`.reaction-count2[data-feed-id='${id}']`).textContent=result.count;
    }



  }

  async function removeReaction(link)
  {
    event.preventDefault();
    const id=link.dataset.feedId;

    let response=await fetch('{{url('/remove/reaction/from')}}/'+id,{
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    if(response.ok)
    {
      link.parentNode.parentNode.previousElementSibling.textContent='React';
      link.remove();

      const count=await response.json();

      document.querySelector(`.reaction-count1[data-feed-id='${id}']`).textContent=count;
      document.querySelector(`.reaction-count2[data-feed-id='${id}']`).textContent=count;
    }

  }

  async function toggleFavorite(btn)
  { let id=btn.dataset.feedId;
    let response= await fetch(`{{url('')}}/toggle/favorite/to/${id}`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });

    if(response.ok)
    {
      btn.classList.toggle('reacted');
  }
}

  async function toggleLikeComment(link)
  {
    event.preventDefault();
    let id=link.dataset.commentId;
    let response= await fetch(`{{url('')}}/toggle/like/comment/${id}`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });

    if(response.ok)
    {
      document.querySelectorAll(`#comment_like_${id}`).forEach(like=>{like.classList.toggle('reacted')});

      let result = await response.json();
      document.querySelectorAll(`.comment-like-count[data-comment-id='${id}']`).forEach(count=>{count.textContent=result.count;});

    }
  }

  async function toggleLikeReply(link)
  {
    event.preventDefault();
    let id=link.dataset.replyId;
    let comment_id=link.dataset.commentId;
    let post_id=link.dataset.feedId;
    let response= await fetch(`{{url('')}}/posts/${post_id}/comments/${comment_id}/replies/${id}/toggle/like`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });

    if(response.ok)
    {
      document.querySelectorAll(`#reply_like_${id}`).forEach(like=>{like.classList.toggle('reacted')});

      let result = await response.json();
      document.querySelectorAll(`.reply-like-count[data-reply-id='${id}']`).forEach(count=>{count.textContent=result.count});

    }
  }

  async function addComment(f,container)
  {
    event.preventDefault();
    let id=f.dataset.feedId;



    let response= await fetch('{{url('')}}/comment/to/'+id, {
      method: 'POST',
      body: new FormData(f)
    });

    if(response.ok)
    {
      let comment= await response.json();
      comment.created_at=getTimeDiff(comment.created_at);
      let wrap = document.createElement('div');
      wrap.className='mb-2';
      wrap.id=`comment_${comment.id}`;

      wrap.innerHTML=`@include('post.comment')`;

      if(container=='collapse')
      {
        document.querySelector(`.comment-collapse[data-feed-id='${id}']`).prepend(wrap);
      }
      else
      {
        let box=document.querySelector(`.comments-body[data-feed-id='${id}']`);
        if(document.querySelector(`.comment-count[data-feed-id='${id}']`).textContent==0)
          box.innerHTML='';
        box.prepend(wrap);
      }


      document.querySelectorAll(`.comment-count[data-feed-id='${id}']`).forEach(count=>{count.textContent=comment.count;});
    }



  }

  async function addReply(f)
  {
    event.preventDefault();
    let id=f.dataset.commentId;
    let post_id=f.dataset.feedId;
    let response= await fetch(`{{url('')}}/posts/${post_id}/comments/${id}/replies`, {
      method: 'POST',
      body: new FormData(f)
    });

    if(response.ok)
    {
      let reply= await response.json();
      reply.created_at=getTimeDiff(reply.created_at);
      let wrap = document.createElement('div');
      wrap.className='mb-2';
      wrap.id=`reply_${reply.id}`;
      wrap.innerHTML=`@include('post.comment.reply')`;

      document.querySelectorAll(`.replies-collapse[data-comment-id='${id}']`).forEach(collapse=>{collapse.prepend(wrap)});

      document.querySelectorAll(`.comment-reply-count[data-comment-id='${id}']`).forEach(count=>{count.textContent=reply.count});
    }



  }




  function showComments(btn)
  {
    let id=btn.dataset.feedId;
    let myModal = new bootstrap.Modal(document.querySelector(`.comments-box[data-feed-id='${id}']`));

    myModal.show();

    displayComments(id,0);



  }

  function showReplies(btn)
  {
    let id=btn.dataset.commentId;
    let post_id=btn.dataset.feedId;

    // let myCollapse = new bootstrap.Collapse(document.querySelector(`.replies-collapse[data-comment-id='${id}']`));
    //
    // myCollapse.show();

    displayReplies(id,post_id);

  }



  function getReactions(btn)
  {
    let id=btn.dataset.feedId;

    let likeModal = new bootstrap.Modal(document.querySelector(`.likes-box[data-feed-id='${id}']`));
    likeModal.show();

    let container=document.querySelector(`.likes-body[data-feed-id='${id}']`);
    displayReactions(id,container,0);

  }



  async function showShares(btn)
  {
    let id=btn.dataset.feedId;

    let likeModal = new bootstrap.Modal(document.querySelector(`.shares-box[data-feed-id='${id}']`));
    likeModal.show();


    displayShares(id,0);


  }

  async function displayShares(id,page)
  {

    let container=document.querySelector(`.shares-body[data-feed-id='${id}']`);

    if(page>0)
      document.querySelector("#load-more-shares").remove();
    else
      container.innerHTML='';

    fetch('{{url('')}}/shares/from/'+id+'/'+page).then(response=>{

      if(response.ok)
        return response.json();
      throw new Error();

    }).then(results => {

      if(page<1 && results.length==0)
      {
        container.innerHTML=`
        <div class="d-flex h-100">
        <p class="text-center text-muted align-self-center w-100" style="font-size:2em">No shares</p>
        </div>`;
        return false;
      }

      let divshares=document.createElement('div');

      results.forEach(function(share){

        let divshare=document.createElement('div');
        divshare.className='mb-2';
        divshare.innerHTML=`@include('post.share')`;
        divshares.append(divshare);

        //alert(comment.texts+comment.owner.name);

      });

      container.append(divshares);

      if(results.length>=10)
        container.append(createLoadMoreShares(id,page+1));

    }).catch(error => {
      alert('error');
    });

  }

  function createLoadMoreShares(id,page)
  {
    let wrap=document.createElement('p');
    wrap.className="text-center";
    wrap.id="load-more-shares";
    wrap.innerHTML=`<button class="btn btn-light" onclick="displayShares(${id},${page})">Load more</button>`;

    return wrap;
  }

  async function displayReactions(id,wrap,page)
  {
    if(page>0)
      document.querySelector("#load-more-reactions").remove();
    else
      wrap.innerHTML='';

    let response=await fetch('{{url('')}}/reactions/from/'+id+'/'+page);


    if(response.ok)
    {

        let results=await response.json();
        let divreactions=document.createElement('div');

        results.forEach(function(reaction){

          if(page<1 && results.length==0)
          {
            wrap.innerHTML=`
            <div class="d-flex h-100">
            <p class="text-center text-muted align-self-center w-100" style="font-size:2em">No reactions</p>
            </div>`;
            return false;
          }

          let divreaction=document.createElement('div');
          divreaction.className='mb-2';

          divreaction.innerHTML=`@include('post.like')`;
          divreactions.append(divreaction);

          //alert(comment.texts+comment.owner.name);
        });

        wrap.append(divreactions);

        if(results.length>=10)
          wrap.append(createLoadMoreReactions(id,wrap,page+1));
    }




  }

  function createLoadMoreReactions(id,wrap,page)
  {
    let w=document.createElement('p');
    w.className="text-center";
    w.id="load-more-reactions";
    w.innerHTML=`<button class="btn btn-light" onclick="displayReactions(${id},${wrap},${page})">Load more</button>`;

    return wrap;
  }


  async function displayComments(id,page)
  {

    let wrap=document.querySelector(`.comments-body[data-feed-id='${id}']`);

    if(page>0)
      document.querySelector("#load-more-comments").remove();
    else
      wrap.innerHTML='';

    let divcomments=document.createElement('div');
    fetch('{{url('')}}/comments/from/'+id+'/'+page).then(async(response)=>{
    if(response.ok)
        {
        let results=await response.json();
        if(page<1 && results.length==0)
        {
          wrap.innerHTML=`
          <div class="d-flex h-100">
          <p class="text-center text-muted align-self-center w-100" style="font-size:2em">No comments</p>
          </div>`;
          return false;
        }

        results.forEach(function(comment){
          comment.created_at=getTimeDiff(comment.created_at);
          if(comment.like_status)
            comment.like_status='reacted';
          let divcomment=document.createElement('div');
          divcomment.className='mb-2';
          divcomment.id=`comment_${comment.id}`;
          divcomment.innerHTML=`@include('post.comment')`;
          divcomments.append(divcomment);

          //alert(comment.texts+comment.owner.name);

        });

        wrap.append(divcomments);
        if(results.length>=10)
          wrap.append(createLoadMoreComments(id,page+1));
        }

    });

    function createLoadMoreComments(id,page)
    {
      let wrap=document.createElement('p');
      wrap.className="text-center";
      wrap.id="load-more-comments";
      wrap.innerHTML=`<button class="btn btn-light" onclick="displayComments(${id},${page})">Load more</button>`;

      return wrap;
    }


    // else
    //   {
    //     wrap.innerHTML
    //     return false;
    //   }


  }

  async function displayReplies(id,post_id)
  {


    let wraps=document.querySelectorAll(`.replies-collapse[data-comment-id='${id}']`);
    wraps.forEach(wrap=>{wrap.innerHTML=`@include('components.loading')`;});



    let divreplies=document.createElement('div');
    fetch(`{{url('')}}/posts/${post_id}/comments/${id}/replies`).then(async(response)=>{
    if(response.ok)
        {
        let results=await response.json();
        wraps.forEach(wrap=>{wrap.innerHTML='';});
        results.forEach(function(reply){
          reply.created_at=getTimeDiff(reply.created_at);
          if(reply.like_status)
            reply.like_status='reacted';
          let divreply=document.createElement('div');
          divreply.className='mb-2';
          divreply.id=`reply_${reply.id}`;
          divreply.innerHTML=`@include('post.comment.reply')`;
          divreplies.append(divreply);

          //alert(comment.texts+comment.owner.name);

        });

        wraps.forEach(wrap=>{wrap.append(divreplies);});

        }
      else {
        wraps.forEach(wrap=>{wrap.innerHTML=`@include('errors.network')`;});
        wraps.forEach(wrap=>{wrap.querySelector('#network-error').onclick=()=>displayReplies(id,post_id);});
      }
    },(error)=>{
      wraps.forEach(wrap=>{wrap.innerHTML=`@include('errors.network')`;});
      wraps.forEach(wrap=>{wrap.querySelector('#network-error').onclick=()=>displayReplies(id,post_id);});
    });


    // else
    //   {
    //     wrap.innerHTML
    //     return false;
    //   }


  }

function canUpdateComment(id,feed_id,user_id)
{
  if(user_id=={{Auth::id()}})
    return `<a href="#" class="text-decoration-none mr-2" aria-controls="boxToEditComment" data-feed-id="${feed_id}" data-comment-id="${id}" onclick="getCommentToEdit(this)">Edit</a>
    <a href="#" class="text-decoration-none mr-2" aria-controls="boxToDeleteComment" data-feed-id="${feed_id}" data-comment-id="${id}" onclick="deleteComment(this)">Delete</a>`;
  return '';
}

function canUpdateReply(id,comment_id,user_id,post_id)
{
  if(user_id=={{Auth::id()}})
    return `<a href="#" class="text-decoration-none mr-2" aria-controls="boxToEditComment" data-feed-id="${post_id}" data-comment-id="${comment_id}" data-reply-id="${id}" onclick="getReplyToEdit(this)">Edit</a>
    <a href="#" class="text-decoration-none mr-2" aria-controls="boxToDeleteReply" data-comment-id="${comment_id}" data-reply-id="${id}" onclick="deleteReply(this)">Delete</a>`;
  return '';
}

</script>
