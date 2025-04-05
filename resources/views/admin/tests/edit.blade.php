{{-- @extends('layouts.app')

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
    <h2 class="text-2xl font-bold mb-6">✏️ Chỉnh sửa bài test</h2>

    <form
        method="POST"
        action="{{ route('admin.tests.update', $test->id) }}"
        class="bg-white p-6 shadow rounded-lg space-y-6"
    >
        @csrf
        @method('PUT') {{-- hoặc dùng <input type="hidden" name="_method" value="PUT"> --}}

        {{-- Thông tin chung về bài test --}}
        <div>
            <label class="block font-semibold text-gray-700 mb-1">Tên bài test</label>
            <input
                type="text"
                name="title"
                class="w-full p-2 border rounded-lg"
                value="{{ old('title', $test->title) }}"
                required
            />
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-1">Mô tả</label>
            <textarea
                name="description"
                class="w-full p-2 border rounded-lg"
            >{{ old('description', $test->description) }}</textarea>
        </div>

        {{-- Danh sách câu hỏi & đáp án --}}
        <div>
            <label class="block font-semibold text-gray-700 mb-2">Câu hỏi</label>
            <div id="question-container" class="space-y-4">
                {{-- Hiển thị các câu hỏi cũ từ DB --}}
                @php
                    $questionIndex = 0;
                @endphp

                @foreach ($test->questions as $question)
                    <div class="question-block border p-4 rounded-lg relative bg-gray-50 mb-4">
                        <button
                            type="button"
                            class="remove-question absolute top-2 right-2 text-red-500 font-bold hover:text-red-700"
                            title="Xóa câu hỏi này"
                        >✕</button>

                        <input
                            type="text"
                            name="questions[{{ $questionIndex }}][question]"
                            class="w-full p-2 border rounded-lg mb-2"
                            placeholder="Nhập câu hỏi"
                            value="{{ old("questions.$questionIndex.question", $question->question) }}"
                            required
                        />

                        <div class="answers">
                            @php
                                $answerIndex = 0;
                            @endphp
                            @foreach($question->answers as $answer)
                                <div class="answer-item flex items-center gap-2 mb-2">
                                    <input
                                        type="text"
                                        name="questions[{{ $questionIndex }}][answers][{{ $answerIndex }}][answer]"
                                        class="flex-1 p-2 border rounded-lg"
                                        placeholder="Đáp án"
                                        value="{{ old("questions.$questionIndex.answers.$answerIndex.answer", $answer->answer) }}"
                                        required
                                    />
                                    <input
                                        type="number"
                                        name="questions[{{ $questionIndex }}][answers][{{ $answerIndex }}][score]"
                                        class="w-20 p-2 border rounded-lg"
                                        placeholder="Điểm"
                                        value="{{ old("questions.$questionIndex.answers.$answerIndex.score", $answer->score) }}"
                                        required
                                    />
                                    <button
                                        type="button"
                                        class="remove-answer text-red-500 hover:text-red-700"
                                        title="Xóa đáp án này"
                                    >✕</button>
                                </div>
                                @php $answerIndex++; @endphp
                            @endforeach
                        </div>
                        <button
                            type="button"
                            class="add-answer bg-green-500 text-white px-3 py-2 rounded-lg mt-2"
                        >➕ Thêm đáp án</button>
                    </div>

                    @php $questionIndex++; @endphp
                @endforeach
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
                @php
                    $resultIndex = 0;
                @endphp
                @foreach($test->results as $result)
                    <div class="result-block border p-4 rounded-lg relative bg-gray-50 mb-4">
                        <button
                            type="button"
                            class="remove-result absolute top-2 right-2 text-red-500 font-bold hover:text-red-700"
                            title="Xóa mức đánh giá"
                        >✕</button>

                        <div class="flex gap-2">
                            <input
                                type="number"
                                name="results[{{ $resultIndex }}][min_score]"
                                class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                                placeholder="Điểm thấp nhất"
                                value="{{ old("results.$resultIndex.min_score", $result->min_score) }}"
                                required
                            />
                            <input
                                type="number"
                                name="results[{{ $resultIndex }}][max_score]"
                                class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                                placeholder="Điểm cao nhất"
                                value="{{ old("results.$resultIndex.max_score", $result->max_score) }}"
                                required
                            />
                            <input
                                type="text"
                                name="results[{{ $resultIndex }}][level]"
                                class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                                placeholder="Mức độ"
                                value="{{ old("results.$resultIndex.level", $result->level) }}"
                                required
                            />
                        </div>

                        <textarea
                            name="results[{{ $resultIndex }}][description]"
                            class="w-full p-2 border rounded-lg mb-2"
                            placeholder="Mô tả chi tiết"
                        >{{ old("results.$resultIndex.description", $result->description) }}</textarea>

                        <textarea
                            name="results[{{ $resultIndex }}][advice]"
                            class="w-full p-2 border rounded-lg mb-2"
                            placeholder="Lời khuyên"
                        >{{ old("results.$resultIndex.advice", $result->advice) }}</textarea>
                    </div>
                    @php $resultIndex++; @endphp
                @endforeach
            </div>

            <button
                type="button"
                onclick="addResult()"
                class="mt-3 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700"
            >
                ➕ Thêm mức đánh giá
            </button>
        </div>

        <button
            type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
        >
            ✅ Cập nhật bài test
        </button>
    </form>
</div>

<script>
    // Lấy sẵn chỉ số questionIndex, resultIndex = (số câu hỏi / số kết quả) hiện có, 
    // để khi thêm mới không ghi đè
    let questionIndex = {{ $questionIndex ?? 0 }};
    let resultIndex = {{ $resultIndex ?? 0 }};

    // 1) Thêm câu hỏi mới (giống create)
    const addQuestionBtn = document.getElementById('add-question');
    addQuestionBtn.addEventListener('click', function() {
        const container = document.getElementById('question-container');
        const qBlock = document.createElement('div');
        qBlock.classList.add(
            'question-block',
            'border',
            'p-4',
            'rounded-lg',
            'relative',
            'bg-gray-50',
            'mb-4'
        );
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

    // 2) Xử lý thêm đáp án, xóa đáp án, xóa câu hỏi (tương tự create)
    document.addEventListener('click', function(e) {
        // Thêm đáp án
        if (e.target.classList.contains('add-answer')) {
            const questionBlock = e.target.closest('.question-block');
            const answersContainer = questionBlock.querySelector('.answers');
            // Tìm question index
            const questionNameInput = questionBlock.querySelector('input[name^="questions"]');
            const qIndex = questionNameInput.name.match(/\d+/)[0];

            // Đếm số answer-item
            const existingAnswers = answersContainer.querySelectorAll('.answer-item').length;
            const newAnswerIndex = existingAnswers;

            const answerHTML = document.createElement('div');
            answerHTML.classList.add('answer-item', 'flex', 'items-center', 'gap-2', 'mb-2');
            answerHTML.innerHTML = `
                <input
                    type="text"
                    name="questions[${qIndex}][answers][${newAnswerIndex}][answer]"
                    class="flex-1 p-2 border rounded-lg"
                    placeholder="Đáp án ${newAnswerIndex + 1}"
                    required
                />
                <input
                    type="number"
                    name="questions[${qIndex}][answers][${newAnswerIndex}][score]"
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

        // Xóa đáp án
        if (e.target.classList.contains('remove-answer')) {
            const answerItem = e.target.closest('.answer-item');
            if (answerItem) answerItem.remove();
        }

        // Xóa câu hỏi
        if (e.target.classList.contains('remove-question')) {
            const qBlock = e.target.closest('.question-block');
            if (qBlock) qBlock.remove();
        }
    });

    // 3) Thêm mức đánh giá
    function addResult() {
        const container = document.getElementById('result-container');
        const rBlock = document.createElement('div');
        rBlock.classList.add(
            'result-block',
            'border',
            'p-4',
            'rounded-lg',
            'relative',
            'bg-gray-50',
            'mb-4'
        );
        rBlock.innerHTML = `
            <button
                type="button"
                class="remove-result absolute top-2 right-2 text-red-500 font-bold hover:text-red-700"
                title="Xóa mức đánh giá"
            >✕</button>

            <div class="flex gap-2">
                <input
                    type="number"
                    name="results[${resultIndex}][min_score]"
                    class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                    placeholder="Điểm thấp nhất"
                    required
                />
                <input
                    type="number"
                    name="results[${resultIndex}][max_score]"
                    class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                    placeholder="Điểm cao nhất"
                    required
                />
                <input
                    type="text"
                    name="results[${resultIndex}][level]"
                    class="w-full md:w-1/3 p-2 border rounded-lg mb-2"
                    placeholder="Mức độ"
                    required
                />
            </div>

            <textarea
                name="results[${resultIndex}][description]"
                class="w-full p-2 border rounded-lg mb-2"
                placeholder="Mô tả chi tiết"
            ></textarea>

            <textarea
                name="results[${resultIndex}][advice]"
                class="w-full p-2 border rounded-lg mb-2"
                placeholder="Lời khuyên"
            ></textarea>
        `;

        container.appendChild(rBlock);
        resultIndex++;
    }

    // 4) Xóa mức đánh giá
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-result')) {
            const block = e.target.closest('.result-block');
            if (block) block.remove();
        }
    });
</script>
@endsection
