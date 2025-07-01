<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RasaService extends Controller
{
    protected $rasaUrl;

    public function __construct()
    {
        $this->rasaUrl = config('rasa.url'); // ví dụ: http://localhost:5005
    }

    public function sendMessage($senderId, $message)
    {
        $response = Http::post($this->rasaUrl, [
            'sender' => $senderId,
            'message' => $message,
        ]);

        return $response->json();
    }
}
