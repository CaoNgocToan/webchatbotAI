<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ObjectController;
use App\Models\Messages;
use Illuminate\Support\Str;
use App\Http\Controllers\RasaService;
use App\Models\FineTuning;
use Symfony\Component\Yaml\Yaml;


class ChatController extends Controller
{
    //
    // app/Http/Controllers/ChatController.php
    protected $rasaService;
    public function __construct(RasaService $rasaService)
    {
        $this->rasaService = $rasaService;
    }
    function index() {
        Session::pull('messages');
        //Session::pull('id_chat');
        $title = ''; $content = '';
        
        return view('chat', compact('title', 'content'));
        //$fine_tuning = FineTuning::where('messages.1.content','regexp', "/Lịch thi Chứng chỉ Ứng dụng Công nghệ Thông tin của Trung tâm Tin học Trường Đại học An Giang./i")->orderBy('updated_at', 'desc')->take(value: 3)->get()->toArray();
        //dd($fine_tuning);
    }

    function get_completion(Request $request)
    {
    Session::pull('messages'); // reset cuộc hội thoại
    $title = ''; $content = '';
    return view('chat-aba', compact('title', 'content'));
    }


    public function chat_submit(Request $request)
{
    $request->validate([
        'title' => 'required|string',
    ]);

    $senderId = $request->session()->getId();
    $message = $request->title;

    // Gửi message đến Rasa
    $responses = $this->rasaService->sendMessage($senderId, $message);
    
    // Lấy text trả lời đầu tiên (hoặc gộp nhiều nếu có)
    $text = collect($responses)->pluck('text')->implode("\n");
    
    // 👉 Tìm chủ đề theo nội dung trả lời
    $topicResult = $this->findTopicByBotResponse($text);
    
    $source = null;
    if ($topicResult) {
        $pdfPath = public_path("pdf/{$topicResult}.pdf");
        if (file_exists($pdfPath)) {
            $source = url("pdf/{$topicResult}.pdf");
        }
    }
    
    // Ghi vào session (nếu cần giữ lịch sử)
    $messages = Session::get('messages', []);
    $messages[] = ['role' => 'user', 'content' => $message];
    $messages[] = ['role' => 'assistant', 'content' => $text, 'source' => $source];
    Session::put('messages', $messages);

    // Ghi log
    $name = session('user.name');
    $msg = [
        ['role' => 'username', 'content' => $name],
        ['role' => 'user', 'content' => $message],
        ['role' => 'assistant', 'content' => $text, 'source' => $source],
    ];
    $ms = new Messages();
    $ms->messages = $msg;
    $ms->save();
    
    // 👉 Trả về cả text và link PDF (nếu có)
    return response()->json([
        'text' => $text,
        'source' => $source
    ]);
}

private function findTopicByBotResponse($botText)
{
    $domainPath = base_path('datarasa/domain');
    

    foreach (glob($domainPath . '/domain_*.yml') as $file) {
        
        $filename = basename($file); // domain_giaoduc.yml
        preg_match('/domain_(.+)\.yml$/', $filename, $matches);
        $topic = $matches[1] ?? null;

        if (!$topic) continue;

        $yaml = Yaml::parseFile($file);
        $responses = $yaml['responses'] ?? [];

        foreach ($responses as $utter => $variants) {
            foreach ($variants as $item) {
                if (isset($item['text']) && trim($item['text']) === trim($botText)) {
                    
                    return $topic;
                }
            }
        }
    }

    return null;
}





    /*function chat_submit(Request $input){
        if ($input->title == null) {
            return;
        }
        $title = $input->title;
        $title_array = array();
        $title_array = Session::get('title_array');
        $name = Session::get('user.name');
        $name = Str::upper(Str::limit(Str::slug($name, ''), 3,':'));

        if($title_array) {
            $title_array[] = "${name} " . $title;
            $promt = implode("\n", $title_array) . "\n${name} ${title}? \ChatbotAI: ";
        } else {
            $promt = "${name} ${title}? \ChatbotAI: ";
            $title_array[] = "${name} ${title}?";
        }

        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            "temperature" => 0.5,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 2024,
            'prompt' => $promt,
        ]);

        $content = trim($result['choices'][0]['text']);
        $title_array[] = 'ChatbotAI: ' . $content;
        Session::put('title_array', $title_array);
        $id_chat = Session::get('id_chat');
        if(!$id_chat) {
            $id_chat = ObjectController::Id();
            Session::put('id_chat', $id_chat);
            $messages = new Messages();
            $messages->_id = $id_chat;
            $messages->name = Session::get('user.name');
            $messages->email = Session::get('user.email');
        } else {
            $messages = Messages::find($id_chat);
        }
        $messages->messages = $title_array;
        $messages->save();
        echo $content;
    }*/

}
