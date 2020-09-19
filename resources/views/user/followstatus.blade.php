@if($status=="Follow")
<button type="button" name="button" class="btn btn-info mr-1 rounded-pill" onclick="follow()">{{ $status }}</button>
@else

<div class="dropup">
  <button type="button" name="button" class="btn btn-info mr-1 rounded-pill"
  data-toggle="dropdown" aria-expanded="false">{{ $status }}</button>
  <ul class="dropdown-menu">
    <li class="dropdown-item"><a href="#" onclick="cancelFollow()">Unfollow</a></li>
  </ul>
</div>
@endif
