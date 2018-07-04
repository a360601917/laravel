<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller {

  public function __construct() {
    $this->middleware('auth', [
        'except' => ['show', 'create', 'store', 'index', 'confirmEmail'] //排除
    ]);
    $this->middleware('guest', [
        'only' => ['create']  //只有
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $users = User::paginate(5);
    return view('users.index', compact('users'));
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
//    Auth::login($user);
//    session()->flash('success', '欢迎');
//    return redirect()->route('users.show', [$user]);
    $this->sendEmailConfirmationTo($user);
    session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
    return redirect('/');
  }

  /*
   * 发送箱相
   */

  function sendEmailConfirmationTo($user) {
    $view = 'emails.confirm';
    $data = compact('user');
    $from = 'aufree@yousails.com';
    $name = 'Aufree';
    $to = $user->email;
    $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

    Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
      $message->from($from, $name)->to($to)->subject($subject);
    });
  }

  /*
   * 确认箱相
   */

  public function confirmEmail($token) {
    $user = User::where('activation_token', $token)->firstOrFail();

    $user->activated = true;
    $user->activation_token = null;
    $user->save();

    Auth::login($user);
    session()->flash('success', '恭喜你，激活成功！');
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
  public function destroy(User $user) {
    $this->authorize('destroy', $user);
    $user->delete();
    session()->flash('success', '成功删除用户！');
    return back();
  }

  function test() {


    return 11111;
  }

}
