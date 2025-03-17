<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;
use App\Policies\TestPolicy;

class AdminTestController extends Controller
{
    
    use AuthorizesRequests;
   
    public function index()
    {
        $tests = Test::all();
        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.tests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string|max:500',
            'questions.*.answers' => 'required|array|min:2', 
            'questions.*.answers.*.answer' => 'required|string|max:500',
            'questions.*.answers.*.score' => 'required|integer|min:0',
            'results' => 'required|array',
            'results.*.min_score' => 'required|integer|min:0',
            'results.*.max_score' => 'required|integer|min:0',
            'results.*.level' => 'required|string|max:255',
            'results.*.description' => 'required|string',
            'results.*.advice' => 'required|string',
        ]);

        // Tạo bài test
        $test = Test::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Thêm câu hỏi và đáp án
        foreach ($request->questions as $questionData) {
            $question = Question::create([
                'test_id' => $test->id,
                'question' => $questionData['question'],
            ]);

            foreach ($questionData['answers'] as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer' => $answerData['answer'],
                    'score' => $answerData['score'],
                ]);
            }
        }

        // Thêm mức độ & lời khuyên
        foreach ($request->results as $resultData) {
            Result::create([
                'test_id' => $test->id,
                'min_score' => $resultData['min_score'],
                'max_score' => $resultData['max_score'],
                'level' => $resultData['level'],
                'description' => $resultData['description'],
                'advice' => $resultData['advice'],
            ]);
        }

        return redirect()->route('admin.tests.index')->with('success', 'Bài test đã được thêm!');
    }

        public function edit(Test $test)
    {
        $questions = $test->questions()->with('answers')->get();
        $results = $test->results()->get();
        return view('admin.tests.edit', compact('test', 'questions', 'results'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|integer|exists:questions,id',
            'questions.*.question' => 'required|string|max:500',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.id' => 'nullable|integer|exists:answers,id',
            'questions.*.answers.*.answer' => 'required|string|max:500',
            'questions.*.answers.*.score' => 'required|integer|min:0',
            'results' => 'required|array|min:1',
            'results.*.id' => 'nullable|integer|exists:results,id',
            'results.*.min_score' => 'required|integer|min:0',
            'results.*.max_score' => 'required|integer|min:0',
            'results.*.level' => 'required|string|max:255',
            'results.*.description' => 'required|string',
            'results.*.advice' => 'required|string',
        ]);

        // Cập nhật bài test
        $test->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Cập nhật câu hỏi và đáp án
        foreach ($request->questions as $questionData) {
            if (!empty($questionData['id'])) {
                $question = Question::find($questionData['id']);
                $question->update(['question' => $questionData['question']]);
            } else {
                $question = Question::create([
                    'test_id' => $test->id,
                    'question' => $questionData['question'],
                ]);
            }

            foreach ($questionData['answers'] as $answerData) {
                if (!empty($answerData['id'])) {
                    $answer = Answer::find($answerData['id']);
                    $answer->update([
                        'answer' => $answerData['answer'],
                        'score' => $answerData['score'],
                    ]);
                } else {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer' => $answerData['answer'],
                        'score' => $answerData['score'],
                    ]);
                }
            }
        }

        // Cập nhật kết quả bài test
        foreach ($request->results as $resultData) {
            if (!empty($resultData['id'])) {
                $result = Result::find($resultData['id']);
                $result->update([
                    'min_score' => $resultData['min_score'],
                    'max_score' => $resultData['max_score'],
                    'level' => $resultData['level'],
                    'description' => $resultData['description'],
                    'advice' => $resultData['advice'],
                ]);
            } else {
                Result::create([
                    'test_id' => $test->id,
                    'min_score' => $resultData['min_score'],
                    'max_score' => $resultData['max_score'],
                    'level' => $resultData['level'],
                    'description' => $resultData['description'],
                    'advice' => $resultData['advice'],
                ]);
            }
        }

        return redirect()->route('admin.tests.index')->with('success', 'Bài test đã được cập nhật!');
    }
    public function destroy(Test $test)
    {
        // Xóa tất cả câu hỏi, đáp án, kết quả liên quan
        $test->questions()->each(function ($question) {
            $question->answers()->delete(); // Xóa đáp án của câu hỏi
            $question->delete(); // Xóa câu hỏi
        });

        $test->results()->delete(); // Xóa kết quả đánh giá
        $test->delete(); // Xóa bài test

        return redirect()->route('admin.tests.index')->with('success', 'Bài test đã được xóa thành công!');
    }
}
