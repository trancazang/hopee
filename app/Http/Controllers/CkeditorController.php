<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CkeditorController extends Controller
{
    public function upload(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            // CKEditor gửi file field tên 'upload'
            $file = $request->file('upload');
            
            // Lưu vào disk 'public' thư mục 'uploads'
            $path = $file->store('uploads', 'public');
            
            // Tạo URL đầy đủ
            $url = asset('storage/' . $path);
            
            // Debug log
            Log::info('CKEditor Upload Success', [
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'url' => $url,
                'full_path' => storage_path('app/public/' . $path)
            ]);

            // Trả về JSON theo spec của SimpleUploadAdapter
            return response()->json([
                'url' => $url,
            ]);
            
        } catch (\Exception $e) {
            Log::error('CKEditor Upload Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => [
                    'message' => 'Upload failed: ' . $e->getMessage()
                ]
            ], 500);
        }
    }
}