@isset($address)

<form class="" action="{{ url("update/address/$id") }}" method="post">
  Country<input type="text" name="country" value="{{ $address['country'] }}">
  City<input type="text" name="city" value="{{ $address['city'] }}">
  Detail<input type="text" name="detail" value="{{ $address['detail'] }}">
  From <input type="date" name="from" value="{{ $address['from'] }}">
  To <input type="date" name="to" value="{{ $address['to'] }}">
  @csrf
  <input type="submit" name="" value="Save">

</form>

@else

<form class="" action="{{ url("update/address") }}" method="post">
  Country<input type="text" name="country" value="">
  City<input type="text" name="city" value="">
  Detail<input type="text" name="detail" value="">
  From <input type="date" name="from" value="">
  To <input type="date" name="to" value="">
  @csrf
  <input type="submit" name="" value="Save">

</form>
