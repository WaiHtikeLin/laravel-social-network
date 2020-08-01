@isset($work)

<form class="" action="{{ url("update/work/$id") }}" method="post">
  Name<input type="text" name="name" value="{{ $work['name'] }}">
  Title<input type="text" name="title" value="{{ $work['title'] }}">
  From <input type="date" name="from" value="{{ $work['from'] }}">
  To <input type="date" name="to" value="{{ $work['to'] }}">
  @csrf
  <input type="submit" name="" value="Save">

</form>

@else
<form class="" action="{{ url('update/work') }}" method="post">
  Name<input type="text" name="name" value="">
  Title<input type="text" name="title" value="">
  From <input type="date" name="from" value="">
  To <input type="date" name="to" value="">
  @csrf
  <input type="submit" name="" value="Save">

</form>

@endisset
