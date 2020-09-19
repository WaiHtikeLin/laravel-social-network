<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
  protected $table='ChatMessages';

  protected $guarded = [];

  protected $keyType = 'string';

  public $incrementing = false;

  public function owner()
  {
    return $this->belongsTo('App\User','user_id');
  }
}
