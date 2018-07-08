<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Status extends Model {

  protected $table = 'statuses';
  protected $fillable = [
      'content', 'user_id', 'created_at',
  ];

  public function user() {
    return $this->belongsTo(User::class);
  }

}
