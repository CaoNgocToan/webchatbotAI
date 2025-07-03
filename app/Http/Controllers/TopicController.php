<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Session;

class TopicController extends Controller
{
    // Hiển thị danh sách
    
    public function list(Request $request)
    {
        $perPage = 30;
        $page = $request->get('page', 1);

        // Lấy toàn bộ dữ liệu
        $all = Topic::orderBy('created_at', 'desc')->get();

        // Tách trang
        $items = $all->forPage($page, $perPage);

        // Tạo đối tượng phân trang
        $danhsach = new LengthAwarePaginator(
            $items,
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('Admin.Topic.list')->with(compact('danhsach'));
    }

    // Hiển thị chi tiết topic
    public function detail(Request $request, $id = '')
    {
        $ds = Topic::find($id);
        return view('Admin.Topic.detail')->with(compact('ds'));
    }

    // Xoá topic
    public function delete(Request $request, $id = '')
    {
        Topic::destroy($id);
        Session::flash('msg', 'Xoá thành công');
        return redirect(env('APP_URL') . 'admin/topic');
    }

    // Hiển thị form thêm mới
    public function createForm()
    {
        return view('Admin.Topic.create');
    }

    // Lưu topic mới
    public function create(Request $request)
    {
        $request->validate([
            'ten_topic' => 'required|string',
            'ten_khong_dau' => 'required|string'
        ]);

        Topic::create([
            'ten_topic' => $request->ten_topic,
            'ten_khong_dau' => $request->ten_khong_dau
        ]);

        Session::flash('msg', 'Thêm mới thành công');
        return redirect(env('APP_URL') . 'admin/topic');
    }

    // Hiển thị form cập nhật
    public function editForm($id)
    {
        $ds = Topic::find($id);
        return view('Admin.Topic.edit')->with(compact('ds'));
    }

    // Cập nhật topic
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_topic' => 'required|string',
            'ten_khong_dau' => 'required|string'
        ]);

        $topic = Topic::find($id);
        if ($topic) {
            $topic->ten_topic = $request->ten_topic;
            $topic->ten_khong_dau = $request->ten_khong_dau;
            $topic->save();

            Session::flash('msg', 'Cập nhật thành công');
        }

        return redirect(env('APP_URL') . 'admin/topic');
    }
}
