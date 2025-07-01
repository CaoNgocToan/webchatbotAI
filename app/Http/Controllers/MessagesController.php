<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messages;
use Session;

class MessagesController extends Controller
{
    //
    function list(Request $request) {
        $danhsach = Messages::orderBy('created_at', 'desc')->paginate(30);
        return view('Admin.Messages.list')->with(compact('danhsach'));
    }

    function detail(Request $request, $id='') {
        $ds = Messages::find($id);
        return view('Admin.Messages.detail')->with(compact('ds'));
    }

    function delete(Request $request, $id = '') {
        Messages::destroy($id);
        Session::flash('msg', 'Xóa thành công');
        return redirect(env('APP_URL').'admin/messages');
    }
}
