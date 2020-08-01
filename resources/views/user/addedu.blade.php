@isset($edu)
<form class="" action="{{ url("update/education/$id") }}" method="post">
  Name<input type="text" name="name" value="{{ $edu['name']  }}">
  From <input type="date" name="from" value="{{ $edu['from'] }}">
  To <input type="date" name="to" value="{{ $edu['to'] }}">
  @csrf
  <input type="submit" name="" value="Save">

</form>
@else

<form class="" action="{{ url("update/education") }}" method="post">
  Name<input type="text" name="name" value="{{ old('name')  }}">
  From <input type="date" name="from" value="{{ old('from') }}">
  To <input type="date" name="to" value="{{ old('to') }}">
  @csrf
  <input type="submit" name="" value="Save">

</form>
@endisset
