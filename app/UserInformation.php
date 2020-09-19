<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
  protected $table='user_information';

  protected $casts = [
        'education' => 'array',
        'work' => 'array',
        'address' => 'array',
        'emails' => 'array',
        'ph_numbers' => 'array',
        'websites' => 'array',
        'about' => 'array',
        'bio' => 'array'
    ];

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
