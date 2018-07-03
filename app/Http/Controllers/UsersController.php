<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller {

  public function __construct() {
    $this->middleware('auth', [
        'except' => ['show', 'create', 'store']
    ]);
    $this->middleware('guest', [
        'only' => ['create']
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    return view('users.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $this->validate($request, [
        'name' => 'required|max:50',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|confirmed|min:6'
    ]);
    $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
    ]);
    Auth::login($user);
    session()->flash('success', '欢迎');
    return redirect()->route('users.show', [$user]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(User $user) {
    //$user = user::find($id);
    return view('users.show', compact('user'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(User $user) {
    $this->authorize('update', $user);
    return view('users.edit', compact('user'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(User $user, Request $request) {
    $this->validate($request, [
        'name' => 'required|max:50',
        'password' => 'nullable|confirmed|min:6'
    ]);
    $this->authorize('update', $user);
    $data = [];
    $data['name'] = $request->name;
    if ($request->password) {
      $data['password'] = bcrypt($request->password);
    }
    $user->update($data);

    session()->flash('success', '个人资料更新成功！');

    return redirect()->route('users.show', $user->id);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    //
  }

}
