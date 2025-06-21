<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-200 dark:bg-slate-800 text-gray-800">
    {{-- Navbar --}}
    <div x-data="navbar">
        <div class="bg-white shadow-md border-b border-slate-100 dark:bg-slate-800 dark:border-slate-700 dark:shadow-none" @click.outside="closeMenu">
            <div class="container mx-auto p-4">
                <div class="flex flex-col md:flex-row flex-wrap items-center justify-between">
                    <div class="flex w-full md:w-auto items-center justify-between">
                        <div class="grow">
                            <a href="/" class="text-lg font-medium">{{ config('app.name') }}</a>
                        </div>
                        <button type="button" class="md:hidden inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-slate-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600" @click="toggleMenu">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 17 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                            </svg>
                        </button>
                    </div>
                    <div class="w-full md:w-auto md:block font-medium flex flex-col items-center justify-between mt-4 md:mt-0 md:flex-row rtl:space-x-reverse text-center" :class="{ hidden: isMenuCollapsed }">
                        <span class="w-full md:w-auto">
                            <a href="{{ route('forum.category.index') }}" class="block hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md px-4 py-2 md:hover:bg-transparent md:dark:hover:bg-transparent md:inline dark:md:inline">{{ trans('forum::general.home_title') }}</a>
                        </span>
                        <span class="w-full md:w-auto">
                            <a href="{{ route('forum.recent') }}" class="block hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md px-4 py-2 md:hover:bg-transparent md:dark:hover:bg-transparent md:inline">{{ trans('forum::threads.recent') }}</a>
                        </span>
                        @auth
                            <span class="w-full md:w-auto">
                                <a href="{{ route('forum.unread') }}" class="block hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md px-4 py-2 md:hover:bg-transparent md:dark:hover:bg-transparent md:inline">{{ trans('forum::threads.unread_updated') }}</a>
                            </span>
                        @endauth
                        @can ('moveCategories')
                            <span class="w-full md:w-auto">
                                <a href="{{ route('forum.category.order') }}" class="block hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md px-4 py-2 md:hover:bg-transparent md:dark:hover:bg-transparent md:inline">{{ trans('forum::general.manage') }}</a>
                            </span> 
                        @endcan
                        @if (Auth::check())
                        <span class="relative w-full md:w-auto" @click.outside="closeUserDropdown">
                            <a class="block flex flex-row items-center place-content-center gap-1 hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md px-4 py-2 md:hover:bg-transparent md:dark:hover:bg-transparent w-full md:w-auto md:inline" href="#" id="navbarDropdownMenuLink" @click="toggleUserDropdown">
                                {{ auth()->user()->name }}                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </a>
                            <div class="absolute right-0 left-0 md:left-auto w-auto md:w-44 divide-y border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 rounded-md" :class="{ hidden: isUserDropdownCollapsed }" aria-labelledby="navbarDropdownMenuLink">
                                <a class="block px-4 py-2" href="#" @click.prevent="logout">
                                    {{ __('Log out') }}
                                </a>
                            </div>
                        </span>
                    @else
                        <span class="w-full md:w-auto">
                            <a href="{{ url('/login') }}" class="block hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md px-4 py-2 md:hover:bg-transparent md:dark:hover:bg-transparent md:inline">
                                {{ __('Đăng nhập') }}
                            </a>
                        </span>
                        <span class="w-full md:w-auto">
                            <a href="{{ url('/register') }}" class="block hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md px-4 py-2 md:hover:bg-transparent md:dark:hover:bg-transparent md:inline">
                                {{ __('Đăng kí') }}
                            </a>
                        </span>
                    @endif
                    </div>
                    <form method="GET" action="{{ route('forum.search') }}" class="flex items-center gap-2 mt-4 md:mt-0">
                        <input type="text" name="q" placeholder="Tìm kiếm..." class="border rounded px-3 py-1">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Tìm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Nội dung chính --}}
    <div class="max-w-5xl mx-auto mt-10 px-6 py-8 bg-white dark:bg-slate-800 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">
            🔍 Kết quả cho: 
            <span class="text-blue-600 dark:text-blue-400">"{{ $term }}"</span>
        </h1>
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-3">🧵 Chủ đề</h3>
            <ul class="list-disc list-inside space-y-2">
                @forelse ($threads as $thread)
                    <li>
                        <a href="{{ url('/forum/t/' . $thread->id . '-' . \Str::slug($thread->title)) }}" class="text-blue-500 hover:underline font-medium">
                            {{ $thread->title }}
                        </a>
                    </li>
                @empty
                    <li class="text-gray-500">Không có chủ đề phù hợp.</li>
                @endforelse
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">📁 Danh mục</h3>
            <ul class="list-disc list-inside space-y-2">
                @forelse ($categories as $category)
                    <li>
                        <a href="{{ url('/forum/c/' . $category->id . '-' . \Str::slug($category->title)) }}" class="text-green-600 hover:underline font-medium">
                            {{ $category->title }}
                        </a>
                    </li>
                @empty
                    <li class="text-gray-500">Không có danh mục phù hợp.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Alpine navbar script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navbar', () => ({
                isMenuCollapsed: true,
                isUserDropdownCollapsed: true,
                toggleMenu() {
                    this.isMenuCollapsed = !this.isMenuCollapsed;
                },
                closeMenu() {
                    this.isMenuCollapsed = true;
                },
                toggleUserDropdown() {
                    this.isUserDropdownCollapsed = !this.isUserDropdownCollapsed;
                },
                closeUserDropdown() {
                    this.isUserDropdownCollapsed = true;
                },
                logout() {
                    fetch('/logout', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(() => window.location.reload());
                }
            }))
        });
    </script>
</body>
</html>
