<div class="d-flex">
  <a href="{{url('')}}/user/profile/${reaction.id}"
  class="text-decoration-none text-dark mr-auto">
    <img src="{{ asset('storage/profile_pics/${reaction.pics[0].name}') }}" alt=""
        style="height:3em;width:3em" class="rounded-circle mr-1">
    <span><strong>${reaction.name}</strong></span></a>
  <p>${reaction.pivot.emoji}</p>
</div>
