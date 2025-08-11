{{-- @extends('layouts.app')

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
                    <div class="answers flex items-center gap-2">
                        <input
                            type="text"
                            name="questions[0][answers][0][answer]"
                            class="flex-1 p-2 border rounded-lg"
                            placeholder="Đáp án 1"
                            required
                        />
                        <input
                            type="number"
                            name="questions[0][answers][0][score]"
                            class="w-20 p-2 border rounded-lg"
                            placeholder="Điểm"
                            required
                        />
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
@endsection --}}


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

<div class="max-w-4xl mx-auto py-8 px-4">
    <h2 class="text-2xl font-bold mb-6">➕ Thêm bài test mới</h2>

    <form method="POST" action="{{ route('admin.tests.store') }}" class="bg-white p-6 shadow rounded-lg space-y-6">
        @csrf
        {{-- Thông tin chung về bài test --}}
        <div>
            <label class="block font-semibold text-gray-700 mb-1">Tên bài test</label>
            <input
                type="text"
                name="title"
                class="w-full p-2 border rounded-lg"
                placeholder="VD: Trắc nghiệm tính cách"
                required
            />
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1">Mô tả</label>
            <textarea
                name="description"
                class="w-full p-2 border rounded-lg"
                placeholder="Giới thiệu ngắn về bài test"
            ></textarea>
        </div>

        {{-- Danh sách câu hỏi & đáp án --}}
        <div>
            <label class="block font-semibold text-gray-700 mb-2">Câu hỏi</label>
            <div id="question-container" class="space-y-4">
                <!-- Khối câu hỏi mặc định -->
                <div class="question-block border p-4 rounded-lg relative bg-gray-50">
                    <button
                        type="button"
                        class="remove-question absolute top-2 right-2 text-red-500 font-bold hover:text-red-700"
                        title="Xóa câu hỏi này"
                    >
                        ✕
                    </button>
                    <input
                        type="text"
                        name="questions[0][question]"
                        class="w-full p-2 border rounded-lg mb-2"
                        placeholder="Nhập câu hỏi"
                        required
                    />
                    <div class="answers">
                        <div class="answer-item flex items-center gap-2 mb-2">
                            <input
                                type="text"
                                name="questions[0][answers][0][answer]"
                                class="flex-1 p-2 border rounded-lg"
                                placeholder="Đáp án 1"
                                required
                            />
                            <input
                                type="number"
                                name="questions[0][answers][0][score]"
                                class="w-20 p-2 border rounded-lg"
                                placeholder="Điểm"
                                required
                            />
                            <button
                                type="button"
                                class="remove-answer text-red-500 hover:text-red-700"
                                title="Xóa đáp án này"
                            >
                                ✕
                            </button>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="add-answer bg-green-500 text-white px-3 py-2 rounded-lg mt-2"
                    >
                        ➕ Thêm đáp án
                    </button>
                </div>
            </div>
            <button
                type="button"
                id="add-question"
                class="mt-3 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700"
            >
                ➕ Thêm câu hỏi
            </button>
        </div>

        {{-- Danh sách kết quả (mức đánh giá) --}}
        <div>
            <label class="block font-semibold text-gray-700 mb-2">Kết quả bài test (các mức điểm)</label>
            <div id="result-container" class="space-y-4">
                <!-- Khối kết quả mặc định -->
                <div class="result-block border p-4 rounded-lg relative bg-gray-50">
                    <button
                        type="button"
                        class="remove-result absolute top-2 right-2 text-red-500 font-bold hover:text-red-700"
                        title="Xóa mức đánh giá"
                    >
                        ✕
                    </button>
                    <div class="flex gap-2">
                        <input
                            type="number"
                            name="results[0][min_score]"
                            class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                            placeholder="Điểm thấp nhất"
                            required
                        />
                        <input
                            type="number"
                            name="results[0][max_score]"
                            class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                            placeholder="Điểm cao nhất"
                            required
                        />
                        <input
                            type="text"
                            name="results[0][level]"
                            class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                            placeholder="Mức độ"
                            required
                        />
                    </div>
                    <textarea
                        name="results[0][description]"
                        class="w-full p-2 border rounded-lg mb-2"
                        placeholder="Mô tả chi tiết (nếu có)"
                    ></textarea>
                    <textarea
                        name="results[0][advice]"
                        class="w-full p-2 border rounded-lg mb-2"
                        placeholder="Lời khuyên"
                    ></textarea>
                </div>
            </div>
            <button
                type="button"
                onclick="addResult()"
                class="mt-3 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700"
            >
                ➕ Thêm mức đánh giá
            </button>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Lưu bài test
        </button>
    </form>
</div>

<script>
    // 1) Biến đếm cho câu hỏi & kết quả
    let questionIndex = 1;
    let resultIndex = 1;

    // 2) Thêm 1 câu hỏi
    const addQuestionBtn = document.getElementById('add-question');
    addQuestionBtn.addEventListener('click', function() {
        const container = document.getElementById('question-container');
        const qBlock = document.createElement('div');
        qBlock.classList.add('question-block', 'border', 'p-4', 'rounded-lg', 'relative', 'bg-gray-50', 'mb-4');

        // Nội dung HTML cho 1 câu hỏi
        qBlock.innerHTML = `
            <button
                type="button"
                class="remove-question absolute top-2 right-2 text-red-500 font-bold hover:text-red-700"
                title="Xóa câu hỏi này"
            >✕</button>
            <input
                type="text"
                name="questions[${questionIndex}][question]"
                class="w-full p-2 border rounded-lg mb-2"
                placeholder="Nhập câu hỏi"
                required
            />
            <div class="answers">
                <div class="answer-item flex items-center gap-2 mb-2">
                    <input
                        type="text"
                        name="questions[${questionIndex}][answers][0][answer]"
                        class="flex-1 p-2 border rounded-lg"
                        placeholder="Đáp án 1"
                        required
                    />
                    <input
                        type="number"
                        name="questions[${questionIndex}][answers][0][score]"
                        class="w-20 p-2 border rounded-lg"
                        placeholder="Điểm"
                        required
                    />
                    <button
                        type="button"
                        class="remove-answer text-red-500 hover:text-red-700"
                        title="Xóa đáp án này"
                    >✕</button>
                </div>
            </div>
            <button
                type="button"
                class="add-answer bg-green-500 text-white px-3 py-2 rounded-lg mt-2"
            >➕ Thêm đáp án</button>
        `;

        container.appendChild(qBlock);
        questionIndex++;
    });

    // 3) Sự kiện "click" toàn trang để bắt thêm/xóa đáp án, xóa câu hỏi
    document.addEventListener('click', function(e) {
        // 3a) Thêm đáp án
        if (e.target.classList.contains('add-answer')) {
            const questionBlock = e.target.closest('.question-block');
            const answersContainer = questionBlock.querySelector('.answers');
            // Lấy "questionId" từ name="questions[<id>][question]"
            const questionNameInput = questionBlock.querySelector('input[name^="questions"]');
            const questionId = questionNameInput.name.match(/\d+/)[0];

            // Đếm số answer hiện có
            const existingAnswers = answersContainer.querySelectorAll('.answer-item').length;
            const newAnswerIndex = existingAnswers;

            const answerHTML = document.createElement('div');
            answerHTML.classList.add('answer-item', 'flex', 'items-center', 'gap-2', 'mb-2');
            answerHTML.innerHTML = `
                <input
                    type="text"
                    name="questions[${questionId}][answers][${newAnswerIndex}][answer]"
                    class="flex-1 p-2 border rounded-lg"
                    placeholder="Đáp án ${newAnswerIndex + 1}"
                    required
                />
                <input
                    type="number"
                    name="questions[${questionId}][answers][${newAnswerIndex}][score]"
                    class="w-20 p-2 border rounded-lg"
                    placeholder="Điểm"
                    required
                />
                <button
                    type="button"
                    class="remove-answer text-red-500 hover:text-red-700"
                    title="Xóa đáp án này"
                >✕</button>
            `;
            answersContainer.appendChild(answerHTML);
        }

        // 3b) Xóa đáp án
        if (e.target.classList.contains('remove-answer')) {
            const answerItem = e.target.closest('.answer-item');
            if (answerItem) {
                answerItem.remove();
            }
        }

        // 3c) Xóa câu hỏi
        if (e.target.classList.contains('remove-question')) {
            const questionBlock = e.target.closest('.question-block');
            if (questionBlock) {
                questionBlock.remove();
            }
        }
    });

    // 4) Thêm 1 kết quả (mức đánh giá)
    function addResult() {
        const container = document.getElementById('result-container');
        const rBlock = document.createElement('div');
        rBlock.classList.add('result-block', 'border', 'p-4', 'rounded-lg', 'relative', 'bg-gray-50', 'mb-4');

        rBlock.innerHTML = `
        <div id="result-container" class="space-y-4">
                <!-- Khối kết quả mặc định -->
                <div class="result-block border p-4 rounded-lg relative bg-gray-50">
                    <button
                        type="button"
                        class="remove-result absolute top-2 right-2 text-red-500 font-bold hover:text-red-700"
                        title="Xóa mức đánh giá"
                    >
                        ✕
                    </button>
                    <div class="flex gap-2">
                        <input
                            type="number"
                            name="results[0][min_score]"
                            class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                            placeholder="Điểm thấp nhất"
                            required
                        />
                        <input
                            type="number"
                            name="results[0][max_score]"
                            class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                            placeholder="Điểm cao nhất"
                            required
                        />
                        <input
                            type="text"
                            name="results[0][level]"
                            class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                            placeholder="Mức độ"
                            required
                        />
                    </div>
                    <textarea
                        name="results[0][description]"
                        class="w-full p-2 border rounded-lg mb-2"
                        placeholder="Mô tả chi tiết (nếu có)"
                    ></textarea>
                    <textarea
                        name="results[0][advice]"
                        class="w-full p-2 border rounded-lg mb-2"
                        placeholder="Lời khuyên"
                    ></textarea>
                </div>
            </div>
        `;
        container.appendChild(rBlock);
        resultIndex++;
    }

    // 5) Sự kiện "click" toàn trang để xóa result
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-result')) {
            const resultBlock = e.target.closest('.result-block');
            if (resultBlock) {
                resultBlock.remove();
            }
        }
    });
</script>
@endsection
