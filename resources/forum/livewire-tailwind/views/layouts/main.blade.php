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
            <div class="bg-white/80 backdrop-blur-md shadow-md border-b border-slate-300 dark:bg-slate-800/90 dark:border-slate-700 dark:shadow-none " @click.outside="closeMenu">
                <div class="container mx-auto p-3">
                    <div class="flex flex-col md:flex-row flex-wrap items-center justify-between">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <!-- Logo -->
                            <div class="flex-shrink-0">
                                <a href="/" class="text-lg font-semibold text-slate-700 dark:text-white">
                                    SheZen
                                </a>
                            </div>
                        
                            <!-- Menu items -->
                            <div class="flex-1 flex justify-center space-x-4 text-sm font-medium text-slate-700 dark:text-slate-200">
                                <a href="{{ route('forum.category.index') }}" class="hover:text-blue-600">Diễn đàn</a>
                                <a href="{{ route('forum.recent') }}" class="hover:text-blue-600">Chủ đề gần đây</a>
                                @auth
                                <a href="{{ route('forum.unread') }}" class="hover:text-blue-600">Chủ đề chưa đọc & mới cập nhật</a>
                                @endauth
                                @can('moveCategories')
                                <a href="{{ route('forum.category.order') }}" class="hover:text-blue-600">Quản lý</a>
                                @endcan
                                @if(Auth::check())
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open" class="hover:text-blue-600 flex items-center gap-1">
                                        người hỗ trợ
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-700 shadow-lg rounded-md py-2 z-10">
                                        <a href="#" class="block px-4 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-600">Đăng xuất</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        
                            <!-- Search form -->
                            <form method="GET" action="{{ route('forum.search') }}" class="relative w-full md:w-80">
                                <input
                                    type="text"
                                    name="q"
                                    placeholder="Tìm kiếm bài viết, chủ đề..."
                                    class="w-full pl-10 pr-24 py-2 rounded-full bg-white dark:bg-slate-800 text-gray-900 dark:text-white border border-gray-300 dark:border-slate-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                                >
                                <div class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                </div>
                                <button type="submit" class="absolute right-1 top-1 bottom-1 px-5 rounded-full bg-blue-500 text-white text-sm">
                                    Tìm
                                </button>
                            </form>
                        </div>
                        
          
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

