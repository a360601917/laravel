<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller {
  function __construct() {
    $this->middleware('guest', [
        'only'=>['create']
    ]);
  }

  public function create() {
    return view('sessions.create');
  }

  public function store(Request $request) {
    $credentials = $this->validate($request, [
        'email' => 'required|email|max:255',
        'password' => 'required'
    ]);
    
    //第二个参数记住我
    if (Auth::attempt($credentials,$request->has('remember'))) {
      // 登录成功后的相关操作
      session()->flash('success', '欢迎回来');
      //跳转到上次请求页,参数为当上一次请求记录为空时，跳转到默认地址上
      return redirect()->intended(route('users.show', [Auth::user()]));
    } else {
      // 登录失败后的相关操作
      session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
      return redirect()->back();
    }
  }

  public function destroy() {
    Auth::logout();
    session()->flash('success', '退出成功');
    return redirect('login');
  }

}
