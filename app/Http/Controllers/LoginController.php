<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ObjectController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Messages;
use Session;
use Validator;

class LoginController extends Controller
{
    //
  function login_form(Request $request) {
      $email = $request->input('email');
      return view('login')->with(compact('email'));
  }

  function login_submit(Request $request) {
    $data = $request->all();
    $url = isset($data['url']) ? $data['url'] : '';
    $email = $data['email'];
    $password = $data['password'];
    if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1], $remember = true)) {
      $user = User::where('email', '=', $data['email'])->first();
      $request->session()->put('user', $user);
      if(isset($url) && $url){
        return redirect()->intended($url);
      } else {
        return redirect()->intended(env('APP_URL').'?url='.$url);
      }
    } else {
      return redirect(env('APP_URL').'auth/login?url='.$url);
    }
  }

  function register() {
    return view('register');
  }

  function register_submit(Request $request) {
    $data = $request->all();
    $validator = Validator::make($request->all(), [
      'email' => 'required|unique:users',
      'name' => 'required',
      'phone' => 'required',
      'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
      'password_confirmation' => 'min:6'
    ]);
    if ($validator->fails()) {
      return redirect(env('APP_URL').'auth/register')->withErrors($validator)->withInput();
    }
    $user = new User();
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->password = Hash::make($data['password']);
    $user->roles = array('Admin');
    $user->phone = $data['phone'];
    $user->active = 1;//isset($data['active']) ? intval($data['active']) : 0;
    $user->save();
    return redirect(env('APP_URL').'auth/register')->with('success', 1)->withInput();
      //return redirect(env('APP_URL').'auth/login');
  }

  function logout(Request $request){
    Auth::logout();Session::flush();
    return redirect(env('APP_URL').'auth/login');
  }
}
