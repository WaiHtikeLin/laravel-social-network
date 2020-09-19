@if($status=='Friend')
<div class="dropup">
  <button type="button" name="button" class="btn btn-success mr-1 rounded-pill"
  data-toggle="dropdown" aria-expanded="false">{{ $status }}</button>

  <ul class="dropdown-menu pl-2">
    <li class="dropdown-item"><a href="#" onclick="unfriend()">Unfriend</a></li>
  </ul>
</div>

@elseif($status=='Requested')
<div class="dropup">
  <button type="button" name="button" class="btn btn-success mr-1 rounded-pill"
  data-toggle="dropdown" aria-expanded="false">{{ $status }}</button>

  <ul class="dropdown-menu pl-2">
    <li class="dropdown-item"><a href="#" onclick="cancelRequest()">Cancel</a></li>
  </ul>
</div>

@elseif($status=='Add friend')
  <button type="button" name="button" class="btn btn-success mr-1 rounded-pill"
  onclick="addFriend()">{{ $status }}</button>
@endif
