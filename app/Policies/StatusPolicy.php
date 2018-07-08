<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Status;

class StatusPolicy {

  use HandlesAuthorization;

  /**
   * Create a new policy instance.
   *
   * @return void
   */
  public function  destroy(User $user, Status $status) {
    $res=$user->id === $status->user_id;
    return $res;
  }

}
