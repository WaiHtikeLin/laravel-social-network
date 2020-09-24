<div class="modal fade copy-box" tabindex="-1" aria-labelledby="copyTextBox"
aria-hidden="true" data-feed-id='${id}' data-backdrop="static"
data-keyboard="false" id='copyModal_${id}'  onclick="event.stopPropagation()">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header p-0">
      <button type="button" class="btn btn-light border-0 clean-white w-100 text-left py-2" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&lt;</span>
        <span class="ml-2">Copy post</span>
      </button>

    </div>
    <div class="modal-body copy-body" data-feed-id='${id}'>
      <form class="" action="" method="post" onsubmit='return copyWithCredit(this)' data-feed-id='${id}'>
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

        <textarea name="texts" class="form-control mb-3" placeholder="Say something with the post..." style="min-height:200px"></textarea>

        <div class="form-check form-switch mb-3">
          <label class="form-check-label">Shareable</label>
          <input type="checkbox" class="form-check-input" value="1" name="shareable"/>
        </div>



        <p class="text copy-error" data-feed-id='${id}'></p>
        <div class="float-right">
          <button type="button" class="btn btn-dark mr-2" data-dismiss="modal" aria-label="Close">Cancel</button>
          <input type="submit" name="" value="Copy" class="btn btn-light">
        </div>
      </form>
    </div>

  </div>
</div>
</div>
