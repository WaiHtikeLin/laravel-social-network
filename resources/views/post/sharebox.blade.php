<div class="modal fade share-box" tabindex="-1" aria-labelledby="shareTextBox"
aria-hidden="true" data-feed-id='${id}' data-backdrop="static"
data-keyboard="false" id='shareModal_${id}'  onclick="event.stopPropagation()">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header p-0">
      <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&lt;</span>
        <span class="ml-2">Share post</span>
      </button>

    </div>
    <div class="modal-body share-body" data-feed-id='${id}'>
      <form action="{{url('/store/share/of')}}/${id}" method="post" onsubmit='return checkShare(this)' data-feed-id='${id}'>
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

        <textarea name="texts" class="form-control mb-3" placeholder="Say something about the post..."></textarea>

        <div class="form-check form-switch mb-3">
          <label class="form-check-label">Shareable</label>
          <input type="checkbox" class="form-check-input" value="1" name="shareable" />
        </div>

        <div class="form-check form-switch mb-3">
          <label class="form-check-label">Copyable with credit</label>
          <input type="checkbox" class="form-check-input" value="1" name="copyable" />
        </div>

        <p class="text share-error" data-feed-id='${id}'></p>

        <div class="float-right">
          <button type="button" class="btn btn-dark mr-2" data-dismiss="modal" aria-label="Close">Cancel</button>
          <input type="submit" value="Share" class="btn btn-light" />
        </div>
      </form>
    </div>

  </div>
</div>


</div>
