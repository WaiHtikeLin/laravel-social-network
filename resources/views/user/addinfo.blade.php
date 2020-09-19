@isset($edit)
<form method="post" onsubmit="updateInfo(this,'{{$title}}')" data-id="{{$id}}">
  @csrf
<div class="row mb-2">
  <div class="col-7">
    <input class="col-7 form-control rounded-pill mr-2"
    type="text" name="name" placeholder="edit {{$title}}" value="{{$name}}">
  </div>

  <div class="col-3">
    <select class="form-select" name="privacy" aria-label="Choose privacy">
      <option value="{{$privacy}}">Unchanged</option>
      <option value="onlyme">Only me</option>
      <option value="friend">Friends</option>
      <option value="public">Public</option>
    </select>
  </div>

  <input type="submit" name="button" class="col-2 btn btn-success rounded-pill" value="Update">
</div>
</form>

@else

<form class="" method="post" onsubmit="addInfo(this,'{{$title}}','{{$wrap}}')">
  @csrf
<div class="row mb-2">
  <div class="col-7">
    <input class="col-7 form-control rounded-pill mr-2"
    type="text" name="name" placeholder="add {{$title}}">
  </div>

  <div class="col-3">
    <select class="form-select" name="privacy" aria-label="Choose privacy">
      <option value="onlyme">Only me</option>
      <option value="friend">Friends</option>
      <option value="public">Public</option>
    </select>
  </div>

  <input type="submit" name="button" class="col-2 btn btn-success rounded-pill" value="Add">
</div>
</form>

@endisset
