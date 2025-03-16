@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8 bg-white shadow-lg rounded-lg">
    <h2 class="text-center text-3xl font-semibold text-blue-600 mb-6">{{ $test->title }}</h2>
    <p class="text-gray-700 text-center mb-6">{{ $test->description }}</p>

    <form method="POST" action="{{ route('tests.submit', $test) }}" onsubmit="return validateForm()">
        @csrf

        @foreach ($questions as $question)
            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                <p class="font-semibold text-lg mb-3">{{ $loop->iteration }}. {{ $question->question }}</p>
                @foreach ($question->answers as $answer)
                    <label class="flex items-center space-x-3 mb-2 cursor-pointer">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" class="form-radio text-blue-600 focus:ring-blue-500">
                        <span class="text-gray-800">{{ $answer->answer }}</span>
                    </label>
                @endforeach
            </div>
        @endforeach

        <!-- Nút submit -->
        <div class="text-center mt-6">
            <button type="submit" class="bg-blue-500 text-white py-3 px-8 rounded-lg text-lg font-semibold hover:bg-blue-600 transition">
                📝 Hoàn thành bài test
            </button>
        </div>
    </form>

    <!-- JavaScript kiểm tra -->
    <script>
        function validateForm() {
            let questions = document.querySelectorAll("input[type='radio']");
            let checked = {};
            
            // Kiểm tra từng câu hỏi xem có ít nhất 1 đáp án được chọn không
            questions.forEach(input => {
                if (input.checked) {
                    checked[input.name] = true;
                }
            });

            // Nếu chưa chọn đủ, hiển thị cảnh báo
            if (Object.keys(checked).length < {{ count($questions) }}) {
                alert("⚠️ Bạn phải chọn đáp án cho tất cả các câu hỏi trước khi gửi bài.");
                return false;
            }
            return true;
        }
    </script>
</div>
@endsection
