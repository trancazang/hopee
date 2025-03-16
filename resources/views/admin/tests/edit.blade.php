@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold">✏️ Chỉnh sửa bài test</h2>

    <form method="POST" action="{{ route('admin.tests.update', $test) }}" class="mt-6 bg-white p-6 shadow rounded-lg">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium text-gray-700">Tên bài test</label>
            <input type="text" name="title" class="w-full p-2 border rounded-lg" value="{{ $test->title }}" required>
        </div>

        <div class="mt-4">
            <label class="block font-medium text-gray-700">Mô tả</label>
            <textarea name="description" class="w-full p-2 border rounded-lg">{{ $test->description }}</textarea>
        </div>

        <!-- Chỉnh sửa câu hỏi -->
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Câu hỏi</label>
            <div id="question-container">
                @foreach ($questions as $question)
                    <div class="question-block border p-4 rounded-lg mb-4">
                        <input type="hidden" name="questions[{{ $loop->index }}][id]" value="{{ $question->id }}">
                        <input type="text" name="questions[{{ $loop->index }}][question]" class="w-full p-2 border rounded-lg mb-2" value="{{ $question->question }}" required>

                        <div class="answers">
                            @foreach ($question->answers as $answer)
                                <input type="hidden" name="questions[{{ $loop->parent->index }}][answers][{{ $loop->index }}][id]" value="{{ $answer->id }}">
                                <input type="text" name="questions[{{ $loop->parent->index }}][answers][{{ $loop->index }}][answer]" class="w-full p-2 border rounded-lg mb-2" value="{{ $answer->answer }}" required>
                                <input type="number" name="questions[{{ $loop->parent->index }}][answers][{{ $loop->index }}][score]" class="w-full p-2 border rounded-lg mb-2" value="{{ $answer->score }}" required>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Chỉnh sửa kết quả bài test -->
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Kết quả bài test</label>
            <div id="result-container">
                @foreach ($results as $result)
                    <input type="hidden" name="results[{{ $loop->index }}][id]" value="{{ $result->id }}">
                    <input type="number" name="results[{{ $loop->index }}][min_score]" class="w-full p-2 border rounded-lg mb-2" value="{{ $result->min_score }}" required>
                    <input type="number" name="results[{{ $loop->index }}][max_score]" class="w-full p-2 border rounded-lg mb-2" value="{{ $result->max_score }}" required>
                    <input type="text" name="results[{{ $loop->index }}][level]" class="w-full p-2 border rounded-lg mb-2" value="{{ $result->level }}" required>
                    <textarea name="results[{{ $loop->index }}][description]" class="w-full p-2 border rounded-lg mb-2">{{ $result->description }}</textarea>
                    <textarea name="results[{{ $loop->index }}][advice]" class="w-full p-2 border rounded-lg mb-2">{{ $result->advice }}</textarea>
                @endforeach
            </div>
        </div>

        <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-lg">
            💾 Cập nhật
        </button>
    </form>
</div>
@endsection
