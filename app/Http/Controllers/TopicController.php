<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class TopicController extends Controller
{
    // Hiển thị danh sách
         function list(Request $request)
    {
        
        $danhsach = Topic::orderBy('updated_at', 'desc')->paginate(30);
        return view('Admin.Topic.list', compact('danhsach'));
    }
    // Hiển thị chi tiết topic
    public function detail($id)
    {
        $ds = Topic::findOrFail($id);
        return view('Admin.Topic.detail', compact('ds'));
    }

    // Xoá topic
    public function delete($id)
    {
        Topic::destroy($id);
        Session::flash('msg', 'Xoá thành công');
        return redirect()->to(route('admin.topic.list'));
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

        $ten_topic = $request->input('ten_topic');
        $ten_khong_dau = $request->input('ten_khong_dau');

        $exists = Topic::where('ten_topic', $ten_topic)
            ->orWhere('ten_khong_dau', $ten_khong_dau)
            ->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('msg', 'Tên Topic hoặc Tên không dấu đã tồn tại!');
        }

        Topic::create([
            'ten_topic' => $ten_topic,
            'ten_khong_dau' => $ten_khong_dau
        ]);

        Session::flash('msg', 'Thêm mới thành công');
        return redirect()->to(route('admin.topic.list'));
    }

    // Hiển thị form cập nhật
    public function editForm($id)
    {
        $ds = Topic::findOrFail($id);
        return view('Admin.Topic.edit', compact('ds'));
    }

    // Cập nhật topic
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_topic' => 'required|string',
            'ten_khong_dau' => 'required|string'
        ]);
        $ten_topic = $request->input('ten_topic');
        $ten_khong_dau = $request->input('ten_khong_dau');

        $exists = Topic::where('ten_topic', $ten_topic)
            ->orWhere('ten_khong_dau', $ten_khong_dau)
            ->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('msg', 'Tên Topic hoặc Tên không dấu đã tồn tại!');
        }
        $topic = Topic::findOrFail($id);
        $topic->update($request->only(['ten_topic', 'ten_khong_dau']));

        Session::flash('msg', 'Cập nhật thành công');
        return redirect()->to(route('admin.topic.list'));
    }
}
