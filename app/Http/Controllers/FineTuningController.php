<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FineTuning;
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
        return view('Admin.FineTuning.add');
    }

    // Tạo mới FineTuning
    public function create(Request $request)
    {
        $data = $request->all();
        $messages = [
            ['role' => 'system', 'content' => $data['system_content']],
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
        return view('Admin.FineTuning.edit', compact('ds'));
    }

    // Cập nhật FineTuning
    public function update(Request $request)
    {
        $data = $request->all();
        $fineTuning = FineTuning::findOrFail($data['id']);
        $messages = [
            ['role' => 'system', 'content' => $data['system_content']],
            ['role' => 'user', 'content' => $data['user_content']],
            ['role' => 'assistant', 'content' => $data['assistant_content']]
        ];
        $fineTuning->messages = $messages;
        $fineTuning->save();

        // Có thể thêm logic update intent/utter nếu cần

        return redirect(env('APP_URL') . 'admin/fine-tuning');
    }

    // Xóa FineTuning
    public function delete($id)
    {
        FineTuning::destroy($id);
        return redirect(route('admin.fine-tuning.list'));
    }

    // Upload files (chưa triển khai)
    public function upload_files()
    {
        // TODO: Implement file upload logic
    }

    // ===== Helper functions =====

    private function slugify($text)
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $text));
        return trim($text, '_');
    }

    private function getFilePaths($fieldName)
    {
        $slug = $this->slugify($fieldName);
        $base = "D:\\Demo\\RasaTest\\";
        return [
            'nlu' => $base . "data\\nlu_$slug.yml",
            'domain' => $base . "domain\\domain_$slug.yml"
        ];
    }

    // Thêm intent và utter
    private function addIntentAndUtter($field, $userText, $assistantText)
    {
        $intentSlug = $this->slugify($userText);
        $intent = "hoi_$intentSlug";
        $utter = "utter_$intentSlug";

        $paths = $this->getFilePaths($field);

        if (!file_exists($paths['nlu']) || !file_exists($paths['domain'])) {
            // Có thể log lỗi hoặc throw exception
            return;
        }

        // Ghi intent
        $intentBlock = "- intent: $intent\n  examples: |\n    - $userText\n";
        file_put_contents($paths['nlu'], "\n" . $intentBlock, FILE_APPEND);

        // Ghi utter
        $responseBlock = "  $utter:\n    - text: |\n       $assistantText\n\n";
        $content = file_get_contents($paths['domain']);
        if (strpos($content, "responses:") === false) {
            $content .= "\nresponses:\n";
        }
        $content .= $responseBlock;
        file_put_contents($paths['domain'], $content);
    }

    // Xóa intent và utter
    private function deleteIntentAndUtter($field, $userText)
    {
        $intentSlug = $this->slugify($userText);
        $intent = "hoi_$intentSlug";
        $utter = "utter_$intentSlug";

        $paths = $this->getFilePaths($field);

        if (!file_exists($paths['nlu']) || !file_exists($paths['domain'])) {
            return;
        }

        // Xoá intent
        $nluContent = file_get_contents($paths['nlu']);
        $nluContent = preg_replace("/- intent: $intent\n  examples: \|\n(?:    - .*\n)+/", "", $nluContent);
        file_put_contents($paths['nlu'], $nluContent);

        // Xoá utter
        $domainContent = file_get_contents($paths['domain']);
        $domainContent = preg_replace("/  $utter:\n(?:    - text: |\n\n      *\n\n)+/", "", $domainContent);
        file_put_contents($paths['domain'], $domainContent);
    }

    // Sửa intent và utter
    private function updateIntentAndUtter($field, $oldUserText, $newUserText, $newAssistantText)
    {
        $this->deleteIntentAndUtter($field, $oldUserText);
        $this->addIntentAndUtter($field, $newUserText, $newAssistantText);
    }
}
