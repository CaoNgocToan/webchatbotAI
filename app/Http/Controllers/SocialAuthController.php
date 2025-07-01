<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ObjectController;
use App\Models\User;
use Socialite; use Auth; use Session;

class SocialAuthController extends Controller
{
    //
    public function redirect($service = '') {
      return Socialite::driver($service)->redirect();
    }

    public function callback(Request $request, $service = '') {
        $locale = app()->getLocale();
        $user = Socialite::with($service)->stateless()->user();
        $u = User::where('username', '=', $user->email)->first();
        $e = explode('@', $user->email);
        $domain = end($e);
        if($domain == 'agu.edu.vn') {
        	if($u) {
	            //$u->name = $user->name;
	            //$u->email = $user->email;
	            //$u->password = null;
	            if(!isset($u['roles']) && !$u['roles']){
	              $u->roles = array('Expert');
	            }
	            $u->avatar = $user->avatar;
	            $u->active = 1;
	            //$u->provider = strtoupper($service);
	            //$u->id_provider = strtoupper($user->id);
	            $u->user_info = $user->user;
	            $u->save();
	        } else {
	            $id = ObjectController::Id();
	            $u = new User();
	            $u->_id = $id;
	            $u->fullname = $user->name;
	            $u->username = $user->email;
	            $u->password = null;
	            $u->token = $user->token;
	            $u->roles = array('Expert');
	            $u->phone = '';
	            $u->address = array('','','','');
	            $u->photos = array();
	            $u->active = 1;
	            $u->avatar = $user->avatar;
	            $u->provider = strtoupper($service);
	            $u->id_provider = strtoupper($user->id);
	            $u->user_info = $user->user;
	            $u->save();
	        }
            $request->session()->put('user', $u);
            $logQuery = array (
                'action' => 'Đăng nhập hệ thống',
                'id_collection' => $u['_id'],
                'collection' => 'users',
                'data' => $u
            );
            LogController::addLog($logQuery);
        	Auth::login($u, true);
          	return redirect()->intended(env('APP_URL').$locale);
      	} else {
      		Session::flash('msg', __('Domain Email đăng nhập phải là "@agu.edu.vn"'));
      	}
    }
}
