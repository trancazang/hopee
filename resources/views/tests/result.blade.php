@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8 text-center">
                
                <h2 class="text-3xl font-bold text-blue-600 mb-4">📊 Kết Quả Của Bạn</h2>

                <!-- Điểm số -->
                <div class="mt-6">
                    <p class="text-xl font-semibold text-gray-800">🔢 Điểm số của bạn:</p>
                    <p class="text-4xl font-bold text-blue-500 mt-2">{{ $totalScore }}</p>
                </div>

                <!-- Mức độ trầm cảm -->
                <div class="mt-6">
                    <p class="text-xl font-semibold text-gray-800">📉 Mức độ :</p>
                    <span class="inline-block px-4 py-2 mt-2 rounded-full text-lg font-bold
                        {{ $result->level == '' ? 'bg-yellow-100 text-yellow-600' : 
                           ($result->level == 'Trầm cảm trung bình' ? 'bg-orange-100 text-orange-600' : 'bg-red-100 text-red-600') }}">
                        {{ $result->level }}
                    </span>
                </div>

                <!-- Mô tả kết quả -->
                <div class="mt-6">
                    <p class="text-gray-700 text-lg">{{ $result->description }}</p>
                </div>

                <!-- Gợi ý tư vấn -->
                @if ($result->advice)
                    <div class="mt-8 p-6 bg-green-100 rounded-lg shadow-md border-l-4 border-green-500">
                        <h3 class="text-xl font-bold text-green-700">🧑‍⚕️ Gợi ý tư vấn</h3>
                        <p class="text-gray-800 mt-2">{{ $result->advice }}</p>
                    </div>
                @endif

                <!-- Nút hành động -->
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('tests.index') }}" 
                        class="bg-blue-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-blue-600 transition">
                        🔄 Làm lại bài test
                    </a>
                    <a href="{{ route('forum.category.index') }}" 
                        class="bg-green-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-green-600 transition">
                        💬 Tham gia diễn đàn
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
