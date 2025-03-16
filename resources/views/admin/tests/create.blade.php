@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="bg-red-100 text-red-600 p-4 rounded-lg mb-4">
        <strong>Lỗi:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold">➕ Thêm bài test mới</h2>

    <form method="POST" action="{{ route('admin.tests.store') }}" class="mt-6 bg-white p-6 shadow rounded-lg">
        @csrf
        <div>
            <label class="block font-medium text-gray-700">Tên bài test</label>
            <input type="text" name="title" class="w-full p-2 border rounded-lg" required>
        </div>

        <div class="mt-4">
            <label class="block font-medium text-gray-700">Mô tả</label>
            <textarea name="description" class="w-full p-2 border rounded-lg"></textarea>
        </div>

        <!-- Nhập câu hỏi -->
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Câu hỏi</label>
            <div id="question-container">
                <div class="question-block border p-4 rounded-lg mb-4">
                    <input type="text" name="questions[0][question]" class="w-full p-2 border rounded-lg mb-2" placeholder="Nhập câu hỏi" required>
                    <div class="answers">
                        <input type="text" name="questions[0][answers][0][answer]" class="w-full p-2 border rounded-lg mb-2" placeholder="Đáp án 1" required>
                        <input type="number" name="questions[0][answers][0][score]" class="w-full p-2 border rounded-lg mb-2" placeholder="Điểm" required>
                    </div>
                    <button type="button" class="add-answer bg-green-500 text-white px-4 py-2 rounded-lg mt-2">➕ Thêm đáp án</button>
                </div>
            </div>
            <button type="button" id="add-question" class="mt-2 bg-gray-500 text-white px-4 py-2 rounded-lg">
                ➕ Thêm câu hỏi
            </button>
        </div>
        <!-- Nhập kết quả bài test -->
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Kết quả bài test</label>
            <div id="result-container">
                <div class="result-block border p-4 rounded-lg mb-4">
                    <input type="number" name="results[0][min_score]" class="w-full p-2 border rounded-lg mb-2" placeholder="Điểm thấp nhất" required>
                    <input type="number" name="results[0][max_score]" class="w-full p-2 border rounded-lg mb-2" placeholder="Điểm cao nhất" required>
                    <input type="text" name="results[0][level]" class="w-full p-2 border rounded-lg mb-2" placeholder="Mức độ" required>
                    <textarea name="results[0][description]" class="w-full p-2 border rounded-lg mb-2" placeholder="Mô tả"></textarea>
                    <textarea name="results[0][advice]" class="w-full p-2 border rounded-lg mb-2" placeholder="Lời khuyên"></textarea>
                </div>
            </div>
            <button type="button" onclick="addResult()" class="mt-2 bg-gray-500 text-white px-4 py-2 rounded-lg">
                ➕ Thêm mức đánh giá
            </button>
        </div>

        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg">
            ✅ Lưu bài test
        </button>
    </form>
</div>

<script>
    let questionIndex = 1;

    // Thêm câu hỏi mới
    document.getElementById('add-question').addEventListener('click', function() {
        let container = document.getElementById('question-container');
        let newQuestion = document.createElement('div');
        newQuestion.classList.add('question-block', 'border', 'p-4', 'rounded-lg', 'mb-4');
        newQuestion.innerHTML = `
            <input type="text" name="questions[${questionIndex}][question]" class="w-full p-2 border rounded-lg mb-2" placeholder="Nhập câu hỏi" required>
            <div class="answers">
                <input type="text" name="questions[${questionIndex}][answers][0][answer]" class="w-full p-2 border rounded-lg mb-2" placeholder="Đáp án 1" required>
                <input type="number" name="questions[${questionIndex}][answers][0][score]" class="w-full p-2 border rounded-lg mb-2" placeholder="Điểm" required>
            </div>
            <button type="button" class="add-answer bg-green-500 text-white px-4 py-2 rounded-lg mt-2">➕ Thêm đáp án</button>
        `;
        container.appendChild(newQuestion);
        questionIndex++;
    });

    // Sự kiện "click" để thêm đáp án trong câu hỏi
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-answer')) {
            let questionBlock = event.target.closest('.question-block');
            let answersContainer = questionBlock.querySelector('.answers');
            let questionId = questionBlock.querySelector('input[name^="questions"]').name.match(/\d+/)[0];
            let answerIndex = answersContainer.querySelectorAll('input[name^="questions"]').length / 2;

            let newAnswer = document.createElement('div');
            newAnswer.innerHTML = `
                <input type="text" name="questions[${questionId}][answers][${answerIndex}][answer]" class="w-full p-2 border rounded-lg mb-2" placeholder="Đáp án ${answerIndex + 1}" required>
                <input type="number" name="questions[${questionId}][answers][${answerIndex}][score]" class="w-full p-2 border rounded-lg mb-2" placeholder="Điểm" required>
            `;
            answersContainer.appendChild(newAnswer);
        }
    });

    let resultIndex = 1;

    function addResult() {
        let container = document.getElementById('result-container');
        let newResult = document.createElement('div');
        newResult.classList.add('result-block', 'border', 'p-4', 'rounded-lg', 'mb-4');
        newResult.innerHTML = `
            <input type="number" name="results[${resultIndex}][min_score]" class="w-full p-2 border rounded-lg mb-2" placeholder="Điểm thấp nhất" required>
            <input type="number" name="results[${resultIndex}][max_score]" class="w-full p-2 border rounded-lg mb-2" placeholder="Điểm cao nhất" required>
            <input type="text" name="results[${resultIndex}][level]" class="w-full p-2 border rounded-lg mb-2" placeholder="Mức độ" required>
            <textarea name="results[${resultIndex}][description]" class="w-full p-2 border rounded-lg mb-2" placeholder="Mô tả"></textarea>
            <textarea name="results[${resultIndex}][advice]" class="w-full p-2 border rounded-lg mb-2" placeholder="Lời khuyên"></textarea>
        `;
        container.appendChild(newResult);
        resultIndex++;
}
</script>
@endsection
