<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable {

  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $table = 'users';
  protected $fillable = [
      'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

  /*
   * 获取头像
   */

  public function gravatar($size = '100') {
    $hash = md5(strtolower(trim($this->attributes['email'])));
    return "http://www.gravatar.com/avatar/$hash?s=$size";
  }

  /*
   * 事件监听
   */

  public static function boot() {
    parent::boot();

    static::creating(function ($user) {
      $user->activation_token = str_random(30);
    });
  }

  public function sendPasswordResetNotification($token) {
    $this->notify(new ResetPassword($token));
  }

  /*
   * 关联，一对多
   */

  public function statuses() {
    return $this->hasMany(Status::class);
  }

  /*
   * 关联，多对多
   */

  public function followers() {
    return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
  }

  public function followings() {
    return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
  }

  function feed() {
    $user_ids = Auth::user()->followings->pluck('id')->toArray();
    array_push($user_ids, Auth::user()->id);
    return Status::whereIn('user_id', $user_ids)
                    ->with('user')
                    ->orderBy('created_at', 'desc');
  }

  public function follow($user_ids) {
    if (!is_array($user_ids)) {
      $user_ids = compact('user_ids');
    }
    $this->followings()->sync($user_ids, false);
  }

  public function unfollow($user_ids) {
    if (!is_array($user_ids)) {
      $user_ids = compact('user_ids');
    }
    $this->followings()->detach($user_ids);
  }

  public function isFollowing($user_id) {
    return $this->followings->contains($user_id);
  }

}
