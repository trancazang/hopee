<?php
// app/Http/Controllers/ChatbotController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{private $pythonApiUrl = 'http://localhost:5000';
    
    public function index()
    {
        return view('chatbot.index');
    }
    
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        try {
            $response = Http::timeout(30)->post($this->pythonApiUrl . '/chat', [
                'message' => $request->message
            ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể kết nối tới chatbot'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi gọi API: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function setup()
    {
        try {
            $response = Http::timeout(60)->post($this->pythonApiUrl . '/setup');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi thiết lập database: ' . $e->getMessage()
            ], 500);
        }
    }
}