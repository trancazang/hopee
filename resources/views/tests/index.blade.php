@extends('layouts.app')


@section('title', 'Danh sách bài Test')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-center text-2xl font-semibold mb-6">Danh Sách Bài Test</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($tests as $test)
        <div class="bg-white shadow-lg rounded-lg p-5 hover:shadow-2xl transition">
            <h3 class="text-xl font-semibold mb-3">{{ $test->title }}</h3>
            <p class="text-gray-600 mb-4">{{ Str::limit($test->description, 100) }}</p>
            <a href="{{ route('tests.show', $test) }}" class="block w-full text-center bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">
                Bắt đầu bài test
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
