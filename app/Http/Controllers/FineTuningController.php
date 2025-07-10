<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FineTuning;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class FineTuningController extends Controller
{
    // Danh sách FineTuning
    public function list()
    {
        $total = FineTuning::count();
        $danhsach = FineTuning::orderBy('updated_at', 'desc')->paginate(30);
        return view('Admin.FineTuning.list', compact('danhsach', 'total'));
    }

    // Hiển thị form thêm mới
    public function add()
    {
        $topics = Topic::all();
        return view('Admin.FineTuning.add', compact('topics'));
    }

    // Tạo mới FineTuning
    public function create(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'system_content'     => 'required|string',
            'user_content'       => 'required|string',
            'assistant_content'  => 'required|string',
        ], [
            'system_content.required'    => 'Vui lòng chọn chủ đề.',
            'user_content.required'      => 'Vui lòng nhập danh sách câu hỏi.',
            'assistant_content.required' => 'Vui lòng nhập câu trả lời.',
        ]);

        // 2. Tách các câu hỏi từ textarea
        $examples = array_filter(array_map('trim', explode("\n", $request->input('user_content'))));

        if (count($examples) < 10) {
            return back()->withErrors(['user_content' => 'Phải có ít nhất 10 câu hỏi.'])->withInput();
        }

        // 3. Tạo tên intent (nếu chưa nhập)
        $ten_intent = $request->input('ten_intent');
        if (empty($ten_intent)) {
            $ten_intent = $this->slugify(reset($examples));
        }

        // 4. Gói dữ liệu vào messages
        $messages = [
            ['role' => 'topic',    'content' => $request->input('system_content')],
            ['role' => 'intent',   'content' => $ten_intent],
            ['role' => 'examples', 'content' => $examples],
            ['role' => 'utter',    'content' => $request->input('assistant_content')],
        ];

        // 5. Lưu DB
        $fineTuning = new FineTuning();
        $fineTuning->messages = $messages;
        $fineTuning->save();
        // 7. Chuyển hướng sau khi thành công
        return redirect(env('APP_URL') . 'admin/fine-tuning')->with('msg', 'Tạo mới thành công');
    }







    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $ds = FineTuning::findOrFail($id);
        $topics = Topic::all();
        return view('Admin.FineTuning.edit', compact('ds', 'topics'));
    }

    // Cập nhật FineTuning
    public function update(Request $request)
    {
        $data = $request->all();
        $fineTuning = FineTuning::findOrFail($data['id']);
        $messagesOld = $fineTuning->messages;

        $examples = array_filter(array_map('trim', explode("\n", $request->input('user_content'))));

        if (count($examples) < 10) {
            return back()->withErrors(['user_content' => 'Phải có ít nhất 10 câu hỏi.'])->withInput();
        }

        // 3. Tạo tên intent (nếu chưa nhập)
        $ten_intent = $request->input('ten_intent');
        if (empty($ten_intent)) {
            $ten_intent = $this->slugify(reset($examples));
        }

        // 4. Gói dữ liệu vào messages
        $messagesNew = [
            ['role' => 'topic',    'content' => $request->input('system_content')],
            ['role' => 'intent',   'content' => $ten_intent],
            ['role' => 'examples', 'content' => $examples],
            ['role' => 'utter',    'content' => $request->input('assistant_content')],
        ];
        $fineTuning->messages = $messagesNew;
        $fineTuning->save();

        // Có thể thêm logic update intent/utter nếu cần
    
        return redirect(env('APP_URL') . 'admin/fine-tuning');
    }

    // Xóa FineTuning
    public function delete($id)
    {

        FineTuning::destroy($id);
        Session::flash('msg', 'Xóa thành công');
        return redirect(env('APP_URL') . 'admin/fine-tuning');
    }

    
    
    private function slugify($text) 
{
    // Bảng thay thế tiếng Việt có dấu sang không dấu
    $trans = [
        'á'=>'a','à'=>'a','ả'=>'a','ã'=>'a','ạ'=>'a',
        'ă'=>'a','ắ'=>'a','ằ'=>'a','ẳ'=>'a','ẵ'=>'a','ặ'=>'a',
        'â'=>'a','ấ'=>'a','ầ'=>'a','ẩ'=>'a','ẫ'=>'a','ậ'=>'a',

        'đ'=>'d',

        'é'=>'e','è'=>'e','ẻ'=>'e','ẽ'=>'e','ẹ'=>'e',
        'ê'=>'e','ế'=>'e','ề'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e',

        'í'=>'i','ì'=>'i','ỉ'=>'i','ĩ'=>'i','ị'=>'i',

        'ó'=>'o','ò'=>'o','ỏ'=>'o','õ'=>'o','ọ'=>'o',
        'ô'=>'o','ố'=>'o','ồ'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o',
        'ơ'=>'o','ớ'=>'o','ờ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o',

        'ú'=>'u','ù'=>'u','ủ'=>'u','ũ'=>'u','ụ'=>'u',
        'ư'=>'u','ứ'=>'u','ừ'=>'u','ử'=>'u','ữ'=>'u','ự'=>'u',

        'ý'=>'y','ỳ'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',

        'Á'=>'A','À'=>'A','Ả'=>'A','Ã'=>'A','Ạ'=>'A',
        'Ă'=>'A','Ắ'=>'A','Ằ'=>'A','Ẳ'=>'A','Ẵ'=>'A','Ặ'=>'A',
        'Â'=>'A','Ấ'=>'A','Ầ'=>'A','Ẩ'=>'A','Ẫ'=>'A','Ậ'=>'A',

        'Đ'=>'D',

        'É'=>'E','È'=>'E','Ẻ'=>'E','Ẽ'=>'E','Ẹ'=>'E',
        'Ê'=>'E','Ế'=>'E','Ề'=>'E','Ể'=>'E','Ễ'=>'E','Ệ'=>'E',

        'Í'=>'I','Ì'=>'I','Ỉ'=>'I','Ĩ'=>'I','Ị'=>'I',

        'Ó'=>'O','Ò'=>'O','Ỏ'=>'O','Õ'=>'O','Ọ'=>'O',
        'Ô'=>'O','Ố'=>'O','Ồ'=>'O','Ổ'=>'O','Ỗ'=>'O','Ộ'=>'O',
        'Ơ'=>'O','Ớ'=>'O','Ờ'=>'O','Ở'=>'O','Ỡ'=>'O','Ợ'=>'O',

        'Ú'=>'U','Ù'=>'U','Ủ'=>'U','Ũ'=>'U','Ụ'=>'U',
        'Ư'=>'U','Ứ'=>'U','Ừ'=>'U','Ử'=>'U','Ữ'=>'U','Ự'=>'U',

        'Ý'=>'Y','Ỳ'=>'Y','Ỷ'=>'Y','Ỹ'=>'Y','Ỵ'=>'Y',
    ];

    // Thay chữ có dấu thành không dấu
    $text = strtr($text, $trans);

    // Thay khoảng trắng thành dấu gạch dưới _
    $text = preg_replace('/\s+/', '_', $text);

    // Loại bỏ tất cả ký tự không phải chữ cái, số hoặc dấu gạch dưới
    $text = preg_replace('/[^A-Za-z0-9_]/', '', $text);

    return $text;
}


    



}
