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
            'ten_topic' => 'required|string'
        ]);

        $ten_topic = $request->input('ten_topic');
        $ten_khong_dau = $request->input('ten_khong_dau');
        // Kiểm tra xem tên topic và tên không dấu đã tồn tại chưa
        if (empty($ten_khong_dau)) {
            $ten_khong_dau = $this->slugify($ten_topic);
        }
        

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
    public function update(Request $request)
{
    $request->validate([
        'ten_topic' => 'required|string',
        'id' => 'required'
    ]);

    $ten_topic = $request->input('ten_topic');
    $ten_khong_dau = $request->input('ten_khong_dau');
    $id = $request->input('id');

    if (empty($ten_khong_dau)) {
        $ten_khong_dau = $this->slugify($ten_topic);
    }

    // Kiểm tra trùng tên nhưng loại trừ chính bản ghi đang sửa
    $exists = Topic::where('id', '<>', $id)
        ->where(function ($q) use ($ten_topic, $ten_khong_dau) {
            $q->where('ten_topic', $ten_topic)
              ->orWhere('ten_khong_dau', $ten_khong_dau);
        })
        ->first();

    if ($exists) {
        return redirect()->back()->withInput()->with('msg', 'Tên Topic hoặc Tên không dấu đã tồn tại!');
    }

    $topic = Topic::findOrFail($id);

    $topic->update([
        'ten_topic' => $ten_topic,
        'ten_khong_dau' => $ten_khong_dau
    ]);

    Session::flash('msg', 'Cập nhật thành công');
    return redirect()->to(route('admin.topic.list'));
}


    function slugify($text) {
    // Bảng thay thế ký tự tiếng Việt có dấu thành không dấu
    $trans = [
        'á'=>'a', 'à'=>'a', 'ả'=>'a', 'ã'=>'a', 'ạ'=>'a',
        'ă'=>'a', 'ắ'=>'a', 'ằ'=>'a', 'ẳ'=>'a', 'ẵ'=>'a', 'ặ'=>'a',
        'â'=>'a', 'ấ'=>'a', 'ầ'=>'a', 'ẩ'=>'a', 'ẫ'=>'a', 'ậ'=>'a',

        'đ'=>'d',

        'é'=>'e', 'è'=>'e', 'ẻ'=>'e', 'ẽ'=>'e', 'ẹ'=>'e',
        'ê'=>'e', 'ế'=>'e', 'ề'=>'e', 'ể'=>'e', 'ễ'=>'e', 'ệ'=>'e',

        'í'=>'i', 'ì'=>'i', 'ỉ'=>'i', 'ĩ'=>'i', 'ị'=>'i',

        'ó'=>'o', 'ò'=>'o', 'ỏ'=>'o', 'õ'=>'o', 'ọ'=>'o',
        'ô'=>'o', 'ố'=>'o', 'ồ'=>'o', 'ổ'=>'o', 'ỗ'=>'o', 'ộ'=>'o',
        'ơ'=>'o', 'ớ'=>'o', 'ờ'=>'o', 'ở'=>'o', 'ỡ'=>'o', 'ợ'=>'o',

        'ú'=>'u', 'ù'=>'u', 'ủ'=>'u', 'ũ'=>'u', 'ụ'=>'u',
        'ư'=>'u', 'ứ'=>'u', 'ừ'=>'u', 'ử'=>'u', 'ữ'=>'u', 'ự'=>'u',

        'ý'=>'y', 'ỳ'=>'y', 'ỷ'=>'y', 'ỹ'=>'y', 'ỵ'=>'y',

        'Á'=>'a', 'À'=>'a', 'Ả'=>'a', 'Ã'=>'a', 'Ạ'=>'a',
        'Ă'=>'a', 'Ắ'=>'a', 'Ằ'=>'a', 'Ẳ'=>'a', 'Ẵ'=>'a', 'Ặ'=>'a',
        'Â'=>'a', 'Ấ'=>'a', 'Ầ'=>'a', 'Ẩ'=>'a', 'Ẫ'=>'a', 'Ậ'=>'a',

        'Đ'=>'d',

        'É'=>'e', 'È'=>'e', 'Ẻ'=>'e', 'Ẽ'=>'e', 'Ẹ'=>'e',
        'Ê'=>'e', 'Ế'=>'e', 'Ề'=>'e', 'Ể'=>'e', 'Ễ'=>'e', 'Ệ'=>'e',

        'Í'=>'i', 'Ì'=>'i', 'Ỉ'=>'i', 'Ĩ'=>'i', 'Ị'=>'i',

        'Ó'=>'o', 'Ò'=>'o', 'Ỏ'=>'o', 'Õ'=>'o', 'Ọ'=>'o',
        'Ô'=>'o', 'Ố'=>'o', 'Ồ'=>'o', 'Ổ'=>'o', 'Ỗ'=>'o', 'Ộ'=>'o',
        'Ơ'=>'o', 'Ớ'=>'o', 'Ờ'=>'o', 'Ở'=>'o', 'Ỡ'=>'o', 'Ợ'=>'o',

        'Ú'=>'u', 'Ù'=>'u', 'Ủ'=>'u', 'Ũ'=>'u', 'Ụ'=>'u',
        'Ư'=>'u', 'Ứ'=>'u', 'Ừ'=>'u', 'Ử'=>'u', 'Ữ'=>'u', 'Ự'=>'u',

        'Ý'=>'y', 'Ỳ'=>'y', 'Ỷ'=>'y', 'Ỹ'=>'y', 'Ỵ'=>'y',
    ];

    // Thay thế các ký tự có dấu
    $text = strtr($text, $trans);

    // Loại bỏ các ký tự không phải chữ cái hoặc số
    $text = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($text));

    return $text;
}


}
