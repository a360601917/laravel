<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class UsersTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    $users = factory(User::class)->times(50)->make();
    User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

    $user = User::find(6);
    $user->name = 'Aufree';
    $user->email = 'aufree@yousails.com';
    $user->password = bcrypt('123456');
    $user->save();
  }

}
