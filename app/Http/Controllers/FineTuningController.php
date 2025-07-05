<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FineTuning;
use App\Models\Topic;
use Illuminate\Support\Str;

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
        $data = $request->all();
        $messages = [
            ['role' => 'topic', 'content' => $data['system_content']],
            ['role' => 'user', 'content' => $data['user_content']],
            ['role' => 'assistant', 'content' => $data['assistant_content']]
        ];

        $fineTuning = new FineTuning();
        $fineTuning->messages = $messages;
        $fineTuning->save();

        $this->addIntentAndUtter($data['system_content'], $data['user_content'], $data['assistant_content']);

        return redirect(env('APP_URL') . 'admin/fine-tuning');
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
        $user= $fineTuning->user;
        $messages = [
            ['role' => 'topic', 'content' => $data['system_content']],
            ['role' => 'user', 'content' => $data['user_content']],
            ['role' => 'assistant', 'content' => $data['assistant_content']]
        ];
        $fineTuning->messages = $messages;
        $fineTuning->save();

        // Có thể thêm logic update intent/utter nếu cần
        $this->updateIntentAndUtter($data['system_content'], $user, $data['user_content'], $data['assistant_content']);
        return redirect(env('APP_URL') . 'admin/fine-tuning');
    }

    // Xóa FineTuning
    public function delete($id)
    {
        $fineTuning = FineTuning::findOrFail($id);
        $user= $fineTuning->user;
        $topic = $fineTuning->topic;

        $this->deleteIntentAndUtter($topic, $user);
        FineTuning::destroy($id);
        return redirect(env('APP_URL') . 'admin/fine-tuning');
    }

    

    private function getFilePaths($fieldName)
    {
        $slug = $this->slugify($fieldName);
        

        return [
            'nlu' => env('CHATBOT_URL') . "\\data\\nlu_$slug.yml",
            'domain' => env('CHATBOT_URL') . "\\domain\\domain_$slug.yml"
        ];
        }


    // Thêm intent và utter
    private function addIntentAndUtter($field, $userText, $assistantText)
{
    $intentSlug = $this->slugify($userText);
    $intent = "$field/$intentSlug";
    $utter = "utter_$field/$intentSlug";
    $paths = $this->getFilePaths($field);

    if (!file_exists($paths['nlu']) || !file_exists($paths['domain'])) {
        return;
    }

    // Ghi intent vào NLU
    $intentBlock = "- intent: $intent\n  examples: |\n    - " . str_replace("\n", "\n    - ", trim($userText)) . "\n";
    file_put_contents($paths['nlu'], "\n" . $intentBlock, FILE_APPEND);

    // Ghi utter vào domain
    $utterBlock = "  $utter:\n    - text: |\n        " . str_replace("\n", "\n        ", trim($assistantText)) . "\n";
    $domainContent = file_get_contents($paths['domain']);

    if (strpos($domainContent, "responses:") === false) {
        $domainContent = "responses:\n" . $utterBlock . "\n" . $domainContent;
    } else {
        // Thêm trước "session_config:"
        $lines = explode("\n", $domainContent);
        $newLines = [];
        $inserted = false;
        foreach ($lines as $line) {
            if (strpos($line, 'session_config:') !== false && !$inserted) {
                $newLines[] = $utterBlock;
                $inserted = true;
            }
            $newLines[] = $line;
        }
        if (!$inserted) {
            $newLines[] = $utterBlock;
        }
        $domainContent = implode("\n", $newLines);
    }

    file_put_contents($paths['domain'], $domainContent);
}


    // Xóa intent và utter
    private function deleteIntentAndUtter($field, $userText)
    {
        //$intentSlug = $this->slugify($userText);
        //$intent = "$field/$intentSlug";
        //$utter = "utter_$field/$intentSlug";
        $utter = "- intent: lamnghiep/co_nen_trong_rung_ko";
        $paths = $this->getFilePaths($field);

        if (!file_exists($paths['nlu']) || !file_exists($paths['domain'])) {
            return;
        }

        // --- Xoá intent trong file NLU ---
        $nluContent = file_get_contents($paths['nlu']);
        // Regex để xoá block intent
        $pattern = '/- intent:\s*' . preg_quote($intent, '/') . '\s*examples:\s*\|(?:\n\s*-\s.*)+/u';
        $nluContent = preg_replace($pattern, '', $nluContent);
        file_put_contents($paths['nlu'], trim($nluContent) . "\n");

        // --- Xoá utter trong file domain ---
        $domainContent = file_get_contents($paths['domain']);
        // Regex để xoá block utter
        $patternUtter = '/^\s*' . preg_quote($utter, '/') . ':\n(?:\s*-\s*text:\s*\|\n(?:\s{8,}.*\n?)*)+/m';
        $domainContent = preg_replace($patternUtter, '', $domainContent);
        file_put_contents($paths['domain'], trim($domainContent) . "\n");
    }
    // Sửa intent và utter
    private function updateIntentAndUtter($field, $oldUserText, $newUserText, $newAssistantText)
    {
        $this->deleteIntentAndUtter($field, $oldUserText);
        $this->addIntentAndUtter($field, $newUserText, $newAssistantText);
    }

    function slugify($text) {
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

    return $text;
}

}
