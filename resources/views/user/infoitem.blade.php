<li id="${info.type}_${info.id}">${info.name}

  <img src="{{asset('img')}}/${info.privacy}.png" alt="" class="ml-2 info-privacy">
  <a href="#edit_${info.type}_${info.id}" class="mx-2" data-toggle="collapse" aria-expanded="false">Edit</a>
  <a href="#" class="ml-2" onclick="deleteInfo(this)" data-name="${info.field}" data-id=${info.id}>Delete</a>
</li>

<div class="collapse" id="edit_${info.type}_${info.id}">
  @include('user.addinfo',['edit'=>true,'name'=>'${info.name}','privacy'=>'${info.privacy}','title'=>'${info.type}','id'=>'${info.id}'])
</div>
