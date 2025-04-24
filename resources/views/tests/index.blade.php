@extends('layouts.app')


@section('title', 'Danh sách bài Test')
@section('content')
<nav class="bg-blue-600 text-white shadow-md">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
        <!-- Logo / Brand -->
        <a href="{{ url('/') }}" class="text-3xl font-bold flex items-center">
            <span class="mr-2">🧠</span>
            <span>SheZen</span>
        </a>
        <!-- Navigation Links -->
        <div class="flex items-center space-x-6">
            <a href="{{ route('tests.index') }}" class="hover:text-blue-300 transition-colors duration-200">📝 Test</a>
            <a href="{{ route('chat.show') }}" class="hover:text-blue-300 transition-colors duration-200">📩 Tin nhắn</a>

            <a href="{{ route('forum.category.index') }}" class="hover:text-blue-300 transition-colors duration-200">💬 Forum</a>
            @auth
                @if(in_array(auth()->user()->role, ['admin']))
                <a href="{{ route('backpack.dashboard') }}" class="hover:text-blue-300 transition-colors duration-200">📊 Dashboard</a>
                @endif
                <a href="{{ route('profile') }}" class="hover:text-blue-300 transition-colors duration-200">👤 Hồ sơ</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-blue-300 transition-colors duration-200">Đăng xuất</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-blue-300 transition-colors duration-200">🔑 Đăng nhập</a>
                <a href="{{ route('register') }}" class="hover:text-blue-300 transition-colors duration-200">🆕 Đăng ký</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-center text-2xl font-semibold mb-6">Danh Sách Bài Test</h2>
    @if(in_array(auth()->user()->role, ['admin', 'moderator']))
    <div class="mb-4">
              <div class="max-w-7xl mx-auto py-8">
            <a href="{{ route('admin.tests.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block">
                ➕ Quản lý
            </a>
    </div>
    @endif
    
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