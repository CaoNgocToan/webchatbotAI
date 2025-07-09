<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FineTuning;
use App\Models\Topic;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

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

    // Nếu là 1 chủ đề cụ thể
    if (in_array('nlu', $exportTypes)) {
        $this->generateNluFile($slug);
    }
    if (in_array('domain', $exportTypes)) {
        $this->generateDomainFile($slug);
    }

    return redirect()->back()->with('msg', "✅ Đã ghi YAML cho chủ đề '$slug'");
}

private function generateNluFile(string $slug)
{
    $examples = FineTuning::where('messages.topic.content', $slug)->get();

    $yaml = "version: \"3.1\"\n\nnlu:\n";

    foreach ($examples as $item) {
        $msg = collect($item->messages)->keyBy('role');
        $intent = $msg['intent']['content'];
        $examplesList = $msg['examples']['content'] ?? [];

        $yaml .= "- intent: $slug/$intent\n  examples: |\n";
        foreach ($examplesList as $ex) {
            $yaml .= "    - " . trim($ex) . "\n";
        }
    }

    $path = base_path("data/nlu/nlu_$slug.yml");
    file_put_contents($path, $yaml);
}


private function generateDomainFile(string $slug)
{
    $fineTunings = FineTuning::where('topic_slug', $slug)->get();
    $yaml = "version: \"3.1\"\n\ndomain:\n";
    $yaml .= "  intents:\n";
    foreach ($fineTunings as $fineTuning) {
        $yaml .= "  - " . $fineTuning->name . "\n";
    }
    $yaml .= "  entities:\n";
    foreach ($fineTunings as $fineTuning) {
        $yaml .= "  - " . $fineTuning->topic_slug . "\n";
    }
    $yaml .= "  responses:\n";
    foreach ($fineTunings as $fineTuning) {
        $yaml .= "  utter_" . Str::slug($fineTuning->name) . ":\n";
        $yaml .= "    - text: \"" . $fineTuning->description . "\"\n";
    }

    $path = base_path("domain/domain_$slug.yml");
    file_put_contents($path, $yaml);
    Session::flash('msg', "✅ Đã ghi file domain cho chủ đề '$slug'");
}

    private function downloadFile($slug, $exportTypes)
    {
        // Tạo file tạm thời để tải xuống
        $filePath = storage_path("app/public/{$slug}_export.yaml");
        $data = $this->prepareData($slug, $exportTypes);
        
        file_put_contents($filePath, $data);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    private function prepareData($slug, $exportTypes)
    {
        // Lấy dữ liệu từ cơ sở dữ liệu
        $fineTunings = FineTuning::where('topic_slug', $slug)->get();
        $data = []; 
        foreach ($fineTunings as $fineTuning) {
            $item = [
                'name' => $fineTuning->name,
                'description' => $fineTuning->description,
                'topic_slug' => $fineTuning->topic_slug,
            ];

            if (in_array('intents', $exportTypes)) {
                $item['intents'] = $fineTuning->intents;
            }
            if (in_array('responses', $exportTypes)) {
                $item['responses'] = $fineTuning->responses;
            }

            $data[] = $item;
        }
        // Chuyển đổi dữ liệu sang định dạng YAML
        $yamlData = yaml_emit($data);
        // Trả về dữ liệu YAML
        return $yamlData;
        }

}