<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RasaService;

class RasaController extends Controller
{
    protected $rasaService;

    public function __construct(RasaService $rasaService)
    {
        $this->rasaService = $rasaService;
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $senderId = session()->getId(); // hoặc user email
        $response = $this->rasaService->sendMessage($senderId, $request->message);

        
        $text = collect($response)->pluck('text')->implode("\n");

        return response()->json([
            'choices' => [
                [
                    'message' => [ 'content' => $text ]
                ]
            ]
        ]);
    }
}
