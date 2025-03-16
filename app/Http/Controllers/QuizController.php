<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserResponse;
use App\Models\Result;
class QuizController extends Controller
{
    // Hiển thị danh sách bài test
    public function index()
    {
        $tests = Test::all();
        return view('tests.index', compact('tests'));
    }

    // Hiển thị bài test chi tiết
    public function show(Test $test)
    {
        $questions = $test->questions()->with('answers')->get();
        return view('tests.show', compact('test', 'questions'));
    }

    // Xử lý câu trả lời và tính điểm
    public function submit(Request $request, Test $test)
    {   
            // Kiểm tra người dùng đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để làm bài test.');
        }

        $totalScore = 0;
        foreach ($request->input('answers') as $question_id => $answer_id) {
            $answer = Answer::find($answer_id);
            $totalScore += $answer->score;
        }

        // Lưu kết quả bài test
        $response = UserResponse::create([
            'test_id' => $test->id,
            'user_id' => auth()->id(),
            'score' => $totalScore,
        ]);

        // Xác định mức độ trầm cảm & gợi ý tư vấn
        $result = Result::where('test_id', $test->id)
                        ->where('min_score', '<=', $totalScore)
                        ->where('max_score', '>=', $totalScore)
                        ->first();

        return view('tests.result', compact('result', 'totalScore'));
    }
}
