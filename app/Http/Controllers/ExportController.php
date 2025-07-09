<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FineTuning;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;

class ExportController extends Controller
{

    public function showExportForm()
{
    $topics = Topic::all();
    return view('Admin.export-rasa', compact('topics'));
}


    public function export(Request $request)
    {
        // Lấy dữ liệu từ form
        $slug = $request->input('topic_slug');               
        $exportTypes = $request->input('export', []);        
        $action = $request->input('action_type');            

        // Xác định hành động
        if ($action === 'write') {
            return $this->writeToFile($slug, $exportTypes);
        }

        if ($action === 'download') {
            return $this->downloadFile($slug, $exportTypes);
        }

        return redirect()->back()->with('msg', '❌ Không xác định hành động!');
    }

    public function writeToFile(?string $slug, array $exportTypes)
{
    // Nếu người dùng chọn "Tất cả"
    if ($slug === 'tatca') {
        // Lấy toàn bộ topic có dữ liệu
        $topics = FineTuning::distinct('messages.topic.content')->pluck('messages.topic.content');

        foreach ($topics as $topicSlug) {
            if (in_array('nlu', $exportTypes)) {
                $this->generateNluFile($topicSlug);
            }
            if (in_array('domain', $exportTypes)) {
                $this->generateDomainFile($topicSlug);
            }
        }

        return redirect()->back()->with('msg', '✅ Đã ghi YAML cho tất cả chủ đề.');
    }
    else{
        // Nếu là 1 chủ đề cụ thể
        $fineTuning = FineTuning::where('topic', $slug)->pluck('messages')->all();
        $datayml = $this -> getMessagesFromYmlFiles($slug);
        if (in_array('nlu', $exportTypes)) {
            $this->generateNluFile($slug);
        }
        if (in_array('domain', $exportTypes)) {
            $this->generateDomainFile($slug);
        }
    }
    return redirect()->back()->with('msg', "✅ Đã ghi YAML cho chủ đề '$slug'");
}




        private function getMessagesFromYmlFiles(string $slug): array
    {
        $folderPath = base_path('datarasa');
        $messagesList = [];

        if (!File::isDirectory($folderPath)) {
            return [['role' => 'error', 'content' => '❌ Thư mục datarasa không tồn tại']];
        }

        $files = File::allFiles($folderPath);

        $nluData = [];
        $utterData = [];

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $slug = str_replace(['nlu_', 'domain_', '.yml'], '', $filename); // dùng làm 'topic'

            $content = Yaml::parseFile($file->getRealPath());

            if (str_starts_with($filename, 'nlu_') && isset($content['nlu'])) {
                foreach ($content['nlu'] as $item) {
                    $intent = $item['intent'] ?? null;
                    if (!$intent) continue;

                    $examples = [];
                    if (isset($item['examples'])) {
                        $lines = array_filter(array_map('trim', explode("\n", $item['examples'])));
                        foreach ($lines as $line) {
                            $examples[] = ltrim($line, '- ');
                        }
                    }

                    $nluData[$intent] = [
                        'topic' => $slug,
                        'examples' => $examples,
                    ];
                }
            }

            if (str_starts_with($filename, 'domain_') && isset($content['responses'])) {
                foreach ($content['responses'] as $utterKey => $utterVal) {
                    if (isset($utterVal[0]['text'])) {
                        $utterData[$utterKey] = $utterVal[0]['text'];
                    }
                }
            }
        }

        // Gộp thành danh sách $messages
        foreach ($nluData as $intent => $nluItem) {
            $utterKey = 'utter_' . $intent;
            $utter = $utterData[$utterKey] ?? '';

            $messagesList[] = [
                ['role' => 'topic',    'content' => $nluItem['topic']],
                ['role' => 'intent',   'content' => $intent],
                ['role' => 'examples', 'content' => $nluItem['examples']],
                ['role' => 'utter',    'content' => $utter],
            ];
        }

        return $messagesList;
    }

}