<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @if (isset($thread))
                {{ $thread->title }} —
            @endif
            @if (isset($category))
                {{ $category->title }} —
            @endif
            {{ trans('forum::general.home_title') }}
        </title>

        @vite([
            'resources/forum/livewire-tailwind/css/forum.css',
            'resources/forum/livewire-tailwind/js/forum.js'
        ])
    </head>
    <body class="forum bg-gradient-to-b from-blue-50 to-blue-100 dark:from-slate-800 dark:to-slate-900 text-gray-800 dark:text-gray-100">
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
   
        
        <div class="bg-blue shadow-md border-b border-slate-300 dark:bg-slate-800 dark:border-slate-700 dark:shadow-none" @click.outside="closeMenu">
            <div x-data="{ sidebarOpen: true }" class="max-w-7xl mx-auto px-4 py-6">
                <!-- Nút toggle sidebar -->
                <div class="mb-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                        <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <!-- Icon mở -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <!-- Icon đóng -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span x-text="sidebarOpen ? 'Ẩn menu' : 'Hiện menu'"></span>
                    </button>
                </div>
            
                <!-- Bố cục -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Sidebar -->
                    <aside
                        x-show="sidebarOpen"
                        x-transition
                        class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm"
                        x-bind:class="{ 'hidden': !sidebarOpen && window.innerWidth >= 1024 }">
                        @include ('forum::components.form.sidebar')
                    </aside>
            
                    <!-- Nội dung chính -->
                    <main :class="sidebarOpen ? 'lg:col-span-9' : 'lg:col-span-12'" class="transition-all duration-300">
                        {{ $slot }}
                    </main>
                </div>
            </div>                     
        </div>
        <x-mini-chat />

    {{-- Các stack để mini‑chat tự đổ CSS/JS (nếu trong component có @push) --}}
    @stack('styles')
    @stack('scripts')
 {{-- Nút “Tin nhắn riêng” lệch 14 px sang trái --}}
    <a href="{{ route('chat.show') }}"
    class="fixed bottom-6 right-20   {{-- 👈 đổi right‑6 → right‑20 --}}
        bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-full shadow-lg">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.883L3 20l1.117-4.487A7.96 7.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
    </a>
        <livewire:forum::components.alerts />
        <script type="module">
            document.addEventListener('alpine:init', () => {
                Alpine.store('time', {
                    now: new Date(),
                    init() {
                        setInterval(() => {
                            this.now = new Date();
                        }, 1000);
                    }
                })
            });

            Alpine.data('navbar', () => {
                return {
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
                        const csrfToken = document.head.querySelector("[name=csrf-token]").content;

                        fetch('/logout', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            }
                        }).then(() => { window.location.reload(); });
                    }
                }
            });

            document.addEventListener('livewire:init', () => {
            Livewire.on('notify', ({ message }) => {
                alert(message); //report notificationp
            });
        });
        </script>

    </body>
</html>

