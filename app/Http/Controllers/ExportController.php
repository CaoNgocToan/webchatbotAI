<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FineTuning;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
    if ($slug === 'tatca') {
        $topics = $topics = Topic::all();
        foreach ($topics as $topicSlug) {
            if (in_array('nlu', $exportTypes)) {
                $this->writenlu($topicSlug['ten_khong_dau']);
            }
            if (in_array('domain', $exportTypes)) {
                $this->writedomain($topicSlug['ten_khong_dau']);
            }
        }

        return redirect()->back()->with('msg', '✅ Đã ghi YAML cho tất cả chủ đề.');
    }else{
        if (in_array('nlu', $exportTypes)) {
            $this->writenlu($slug);
        }
        if (in_array('domain', $exportTypes)) {
            $this->writedomain($slug);
        }
    }
    return redirect()->back()->with('msg', "✅ Đã ghi YAML cho chủ đề '$slug'");
}

    public function writenlu(string $slug)
    {
        $filePath = env('CHATBOT_URL') . "//data//nlu//nlu_$slug.yml";
        // Lấy intent cũ trong file YAML nếu có
        $intentYML = $this->readnlu($slug); 
        foreach ($intentYML as $item) {
            $intent = $item['intent'];
            $examples = $item['examples'];

            if (!isset($nluItems[$intent])) {
                $nluItems[$intent] = [
                    'intent' => $intent,
                    'examples' => implode("\n", array_map(fn($ex) => "- $ex", $examples)),
                ];
            }
        }


        $fineTunings = FineTuning::all(); // hoặc chỉ lấy cột messages: FineTuning::pluck('messages')

        $messagesList = [];

        foreach ($fineTunings as $item) {
            $messages = $item->messages;

            foreach ($messages as $msg) {
                if ($msg['role'] === 'topic' && $msg['content'] === $slug) {
                    $messagesList[] = $messages;
                    break; // chỉ cần 1 lần match là đủ
                }
            }
        }

        
        foreach ($messagesList as $messages) {
            $intent = null;
            $examples = [];

            foreach ($messages as $msg) {
                if ($msg['role'] === 'intent') {
                    $intent = $msg['content'];
                    


                } elseif ($msg['role'] === 'examples') {
                    $examples = is_array($msg['content']) ? $msg['content'] : explode("\n", $msg['content']);
                    $examples = array_filter(array_map('trim', $examples));
                }
            }

            if ($intent && count($examples)) {
                $nluItems[$intent] = [
                    'intent' => $intent,
                    'examples' => implode("\n", array_map(fn($ex) => "- $ex", $examples)),
                ];
            }
        }

        

        // Chuyển về định dạng cho YAML
        $yamlData = [
            'version' => '3.1',
            'nlu' => array_map(function ($item) {
                return [
                    'intent' => $item['intent'],
                    'examples' => Yaml::dump($item['examples'], 2, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK),
                ];
            }, array_values($nluItems)),
        ];

        // YAML::dump thủ công vì nested dump ở trên đã xử lý literal block
        $yamlString = "version: \"3.1\"\nnlu:\n";
        foreach ($nluItems as $item) {
            $yamlString .= "  - intent: " . $item['intent'] . "\n";
            $yamlString .= "    examples: |\n";
            foreach (explode("\n", $item['examples']) as $line) {
                $yamlString .= "      " . $line . "\n";
            }
            $yamlString .= "\n";
        }

        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, $yamlString);
       
        
        
    }


    public function writedomain(string $slug)
    {
        $filePath = env('CHATBOT_URL') . "//domain//domain_$slug.yml";
        $responses = [];
        $intents = [];
        $utterYML=$this -> readdomain($slug);
        foreach($utterYML as $item){
            $utterKey = "utter_" . $item['utter'];
            if (!isset($responses[$utterKey])) {
                $responses[$utterKey] = $item['text'];
            }
        }


        $fineTunings = FineTuning::all(); 

        $messagesList = [];

        foreach ($fineTunings as $item) {
            $messages = $item->messages;

            foreach ($messages as $msg) {
                if ($msg['role'] === 'topic' && $msg['content'] === $slug) {
                    $messagesList[] = $messages;
                    break; // chỉ cần 1 lần match là đủ
                }
            }
        }

       

        foreach ($messagesList as $messages) {
            $intent = null;
            $utter = null;

            foreach ($messages as $msg) {
                if ($msg['role'] === 'intent') {
                    $intent = $msg['content'];
                } elseif ($msg['role'] === 'utter') {
                    $utter = $msg['content'];
                }
            }

            if ($intent && $utter) {
                $utterKey = "utter_" . $intent;

                if (!isset($responses[$utterKey])) {
                    $responses[$utterKey] = $utter;
                }

                // Thêm intent (theo chude)
                $intentSlug = explode('/', $intent)[0]; // "lamnghiep/abc" => "lamnghiep"
                if (!in_array($intentSlug, $intents)) {
                    $intents[] = $intentSlug;
                }
            }
        }

        
        
        
        $yaml = "version: \"3.1\"\n\n";

        // Intents
        $yaml .= "intents:\n";
        
        $yaml .= "  - $slug\n\n";
        

        // Responses
        $yaml .= "\nresponses:\n";
        foreach ($responses as $utterKey => $text) {
            $yaml .= "  $utterKey:\n";
            $yaml .= "  - text: |\n";
            foreach (explode("\n", (string) $text) as $line) {
                $yaml .= "      $line\n";
            }
            
        }

        // Session config
        $yaml .= "\nsession_config:\n";
        $yaml .= "  session_expiration_time: 60\n";
        $yaml .= "  carry_over_slots_to_new_session: true\n";

        File::ensureDirectoryExists(dirname($filePath));
        File::put($filePath, $yaml);

        
    }


    public function downloadFile(?string $slug, array $exportTypes)
    {
        if ($slug === 'tatca') {
            $topics = Topic::all();
            $tempDir = storage_path('app/public/temp_yaml');
            $nluDir = $tempDir . '/nlu';
            $domainDir = $tempDir . '/domain';
            $zipPath = storage_path('app/public/tatca_yaml.zip');

            // Dọn dẹp cũ
            \File::deleteDirectory($tempDir);
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }

            // Tạo thư mục
            mkdir($nluDir, 0755, true);
            mkdir($domainDir, 0755, true);

            foreach ($topics as $topic) {
                $topicSlug = $topic->ten_khong_dau;

                if (in_array('nlu', $exportTypes)) {
                    $this->generateNluYaml($topicSlug, $nluDir);
                }

                if (in_array('domain', $exportTypes)) {
                    $this->generateDomainYaml($topicSlug, $domainDir);
                }
            }

            // Nén ZIP với cấu trúc thư mục
            $zip = new \ZipArchive;
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $files = \File::allFiles($tempDir);
                foreach ($files as $file) {
                    // Giữ nguyên thư mục con (nlu/, domain/)
                    $relativePath = str_replace($tempDir . '/', '', $file->getPathname());
                    $zip->addFile($file->getRealPath(), $relativePath);
                }
                $zip->close();
            }

            return response()->download($zipPath, 'tatca_yaml.zip')->deleteFileAfterSend(true);
        }

        // Nếu chỉ 1 chủ đề
        if (in_array('nlu', $exportTypes) && in_array('domain', $exportTypes)) {
            $tempDir = storage_path("app/public/temp_yaml/$slug");
            \File::deleteDirectory($tempDir);
            mkdir($tempDir, 0755, true);

            $this->generateNluYaml($slug, $tempDir);
            $this->generateDomainYaml($slug, $tempDir);

            $zipPath = storage_path("app/public/{$slug}_yaml.zip");

            $zip = new \ZipArchive;
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                foreach (glob("$tempDir/*.yml") as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
            }

            return response()->download($zipPath, "{$slug}_yaml.zip")->deleteFileAfterSend(true);
        }

        if (in_array('nlu', $exportTypes)) {
            return $this->generateNluYaml($slug);
        }

        if (in_array('domain', $exportTypes)) {
            return $this->generateDomainYaml($slug);
        }

        return response("Không có kiểu xuất nào được chọn", 400);
    }


    public function generateNluYaml(string $slug, ?string $outputDir = null)
{
    $intentYML = $this->readnlu($slug); 
        foreach ($intentYML as $item) {
            $intent = $item['intent'];
            $examples = $item['examples'];

            if (!isset($nluItems[$intent])) {
                $nluItems[$intent] = [
                    'intent' => $intent,
                    'examples' => implode("\n", array_map(fn($ex) => "- $ex", $examples)),
                ];
            }
        }


        $fineTunings = FineTuning::all(); // hoặc chỉ lấy cột messages: FineTuning::pluck('messages')

        $messagesList = [];

        foreach ($fineTunings as $item) {
            $messages = $item->messages;

            foreach ($messages as $msg) {
                if ($msg['role'] === 'topic' && $msg['content'] === $slug) {
                    $messagesList[] = $messages;
                    break; // chỉ cần 1 lần match là đủ
                }
            }
        }

        
        foreach ($messagesList as $messages) {
            $intent = null;
            $examples = [];

            foreach ($messages as $msg) {
                if ($msg['role'] === 'intent') {
                    $intent = $msg['content'];
                    


                } elseif ($msg['role'] === 'examples') {
                    $examples = is_array($msg['content']) ? $msg['content'] : explode("\n", $msg['content']);
                    $examples = array_filter(array_map('trim', $examples));
                }
            }

            if ($intent && count($examples)) {
                $nluItems[$intent] = [
                    'intent' => $intent,
                    'examples' => implode("\n", array_map(fn($ex) => "- $ex", $examples)),
                ];
            }
        }

        

        // Chuyển về định dạng cho YAML
        $yamlData = [
            'version' => '3.1',
            'nlu' => array_map(function ($item) {
                return [
                    'intent' => $item['intent'],
                    'examples' => Yaml::dump($item['examples'], 2, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK),
                ];
            }, array_values($nluItems)),
        ];

        // YAML::dump thủ công vì nested dump ở trên đã xử lý literal block
        $yamlString = "version: \"3.1\"\nnlu:\n";
        foreach ($nluItems as $item) {
            $yamlString .= "  - intent: " . $item['intent'] . "\n";
            $yamlString .= "    examples: |\n";
            foreach (explode("\n", $item['examples']) as $line) {
                $yamlString .= "      " . $line . "\n";
            }
            $yamlString .= "\n";
        }

    $filename = "nlu_{$slug}.yml";

    if ($outputDir) {
        file_put_contents($outputDir . "/$filename", $yamlString);
        return;
    }

    // Lưu và trả về file tải
    $filePath = "temp/nlu/$filename";
    Storage::disk('public')->put($filePath, $yaml);

    return response()->download(storage_path("app/public/$filePath"), $filename, [
        'Content-Type' => 'application/x-yaml',
    ])->deleteFileAfterSend(true);
}

public function generateDomainYaml(string $slug, ?string $outputDir = null)
{
    $responses = [];
    $intents = [];
    $utterYML=$this -> readdomain($slug);
    foreach($utterYML as $item){
        $utterKey = "utter_" . $item['utter'];
        if (!isset($responses[$utterKey])) {
            $responses[$utterKey] = $item['text'];
        }
    }


    $fineTunings = FineTuning::all(); 

    $messagesList = [];

    foreach ($fineTunings as $item) {
        $messages = $item->messages;

        foreach ($messages as $msg) {
            if ($msg['role'] === 'topic' && $msg['content'] === $slug) {
                $messagesList[] = $messages;
                break; // chỉ cần 1 lần match là đủ
            }
        }
    }

    

    foreach ($messagesList as $messages) {
        $intent = null;
        $utter = null;

        foreach ($messages as $msg) {
            if ($msg['role'] === 'intent') {
                $intent = $msg['content'];
            } elseif ($msg['role'] === 'utter') {
                $utter = $msg['content'];
            }
        }

        if ($intent && $utter) {
            $utterKey = "utter_" . $intent;

            if (!isset($responses[$utterKey])) {
                $responses[$utterKey] = $utter;
            }

            // Thêm intent (theo chude)
            $intentSlug = explode('/', $intent)[0]; // "lamnghiep/abc" => "lamnghiep"
            if (!in_array($intentSlug, $intents)) {
                $intents[] = $intentSlug;
            }
        }
    }

    
    
    
    $yaml = "version: \"3.1\"\n\n";

    // Intents
    $yaml .= "intents:\n";
    
    $yaml .= "  - $slug\n\n";
    

    // Responses
    $yaml .= "\nresponses:\n";
    foreach ($responses as $utterKey => $text) {
        $yaml .= "  $utterKey:\n";
        $yaml .= "  - text: |\n";
        foreach (explode("\n", (string) $text) as $line) {
            $yaml .= "      $line\n";
        }
        
    }

    // Session config
    $yaml .= "\nsession_config:\n";
    $yaml .= "  session_expiration_time: 60\n";
    $yaml .= "  carry_over_slots_to_new_session: true\n";

    $filename = "domain_{$slug}.yml";

    if ($outputDir) {
        file_put_contents($outputDir . "/$filename", $yaml);
        return;
    }

    // Lưu và trả về file tải
    $filePath = "temp/domain/$filename";
    Storage::disk('public')->put($filePath, $yaml);

    return response()->download(storage_path("app/public/$filePath"), $filename, [
        'Content-Type' => 'application/x-yaml',
    ])->deleteFileAfterSend(true);
}


    public function readnlu(string $slug)
    {
        $filePath = base_path("datarasa/nlu/nlu_$slug.yml");

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $content = Yaml::parseFile($filePath);
        $intents = [];

        foreach ($content['nlu'] ?? [] as $item) {
            if (isset($item['intent']) && isset($item['examples'])) {
                // Tách examples YAML về mảng
                $examplesArray = array_filter(array_map(
                    fn($x) => ltrim(trim($x), "- "),
                    explode("\n", $item['examples'])
                ));

                $intents[] = [
                    'intent' => $item['intent'],
                    'examples' => $examplesArray,
                ];
            }
        }

        return $intents;
    }

    public function readdomain(string $slug)
    {
        $filePath = base_path("datarasa/domain/domain_$slug.yml");

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $content = Yaml::parseFile($filePath);
        $responses = [];

        foreach ($content['responses'] ?? [] as $key => $response) {
            if (isset($response[0]['text'])) {
                $responses[] = [
                    'utter' => str_replace('utter_', '', $key),
                    'text' => $response[0]['text'],
                ];
            }
        }

        return $responses;
    }

    

}