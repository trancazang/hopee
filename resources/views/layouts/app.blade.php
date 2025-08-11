<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <meta name="conv-id" content="{{ $id }}">
    <meta name="user-map" content='@json($allUsers->pluck("participantDetails.name", "id"))'>  --}}
    <title>{{ config('app.name', 'Shezen') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="bg-slate-200 dark:bg-slate-800">
      <nav class="bg-transparent text-black">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center bg-[#0d1b2a] text-white">
            <!-- Logo -->
            <a href="{{ url('/welcome') }}" class="text-2xl font-bold flex items-center gap-2">
                <img src="/storage/images/moon-logo.png" alt="Logo" class="w-8 h-8">
                <span>SheZen</span>
            </a>
    
            <!-- Menu items -->
            <div class="flex items-center space-x-6 text-sm relative">
                <a href="{{ route('tests.index') }}" class="hover:underline">Test</a>
                <a href="{{ route('chat.show') }}" class="hover:underline">Tin nhắn</a>
                <a href="{{ route('forum.category.index') }}" class="hover:underline">Forum</a>
    
                @auth
                    <!-- Trigger + Dropdown -->
                    <div class="relative" x-data="{ openAdvice: false }" @click.outside="openAdvice = false" wire:ignore>
                        <a href="#" @click.prevent="openAdvice = !openAdvice"
                           class="inline-flex items-center gap-1 hover:underline px-2 py-1">
                            Tư vấn
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
    
                        <div x-show="openAdvice" x-transition x-cloak
                             class="absolute left-0 mt-2 w-56 bg-white dark:bg-slate-700 text-slate-700 dark:text-white border border-slate-200 dark:border-slate-600 rounded shadow-md z-50">
                            <a href="{{ route('advice.request') }}" class="block px-4 py-2 text-sm hover:bg-pink-50 dark:hover:bg-slate-600">Đăng kí tư vấn</a>
                            <a href="{{ route('advice.history') }}" class="block px-4 py-2 text-sm hover:bg-pink-50 dark:hover:bg-slate-600">Lịch sử tư vấn</a>
                            @if(in_array(auth()->user()->role, ['moderator', 'admin']))
                                <a href="{{ route('advice.manage') }}" class="block px-4 py-2 text-sm hover:bg-pink-50 dark:hover:bg-slate-600"> Quản lý yêu cầu</a>
                                <a href="{{ route('advice.schedule') }}" class="block px-4 py-2 text-sm hover:bg-pink-50 dark:hover:bg-slate-600">Tiếp nhận yêu cầu</a>
                                <a href="{{ route('advice.calendar') }}" class="block px-4 py-2 text-sm hover:bg-pink-50 dark:hover:bg-slate-600">Đăng ký lịch</a>
                            @endif
                        </div>
                    </div>
                    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

                @endauth
    
                <!-- Auth buttons -->
                @auth
                @if(in_array(auth()->user()->role, ['admin','moderator']))
                    <a href="{{ route('backpack.dashboard') }}" class="hover:underline">Dashboard</a>
                @endif
                <a href="{{ route('profile') }}" class="hover:underline"> Hồ sơ</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:underline">Đăng xuất</button>
                </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="hover:underline">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="py-10 px-4">
            {{-- Nếu dùng @extends --}}
            @yield('content')
    
            {{-- Nếu dùng component slot --}}
            {{ $slot ?? '' }}
        </main>
        <x-mini-chat />

    </div>
    @stack('scripts')
    @stack('styles')
</body>
</html>
