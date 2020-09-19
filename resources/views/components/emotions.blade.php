<li>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😂️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😆️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😍️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    👏️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    👍️
  </a>

</li>
<li>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😮️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    🤤️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😲️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😟️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😢️
  </a>


</li>
<li>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😰️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😭️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😱️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😠️
  </a>
  <a role="button" class="btn btn-light clean-white border-0" onclick="addReaction(this)">
    😡️
  </a>

</li>

<li id="reaction-action_${feed.id}">
  <a href="#add-emoji_${feed.id}" class="p-2" data-toggle="collapse" aria-expanded="false">React with mine</a>
  ${isReactionPresent(feed.id,feed.reaction_status)}
</li>
