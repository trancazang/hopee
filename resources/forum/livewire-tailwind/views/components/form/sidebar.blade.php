<!-- Sidebar Wrapper -->
<div x-data="{ openSidebar: true }" class="relative">
    <!-- Toggle Button -->
    <button @click="openSidebar = !openSidebar"
        class="lg:hidden fixed top-4 left-4 z-50 bg-blue-600 text-white p-2 rounded-md shadow-lg hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Sidebar -->
    <aside
        class="lg:block fixed lg:static top-0 left-0 h-full lg:h-auto z-40 transform lg:transform-none transition-transform duration-300 ease-in-out"
        :class="{ '-translate-x-full': !openSidebar, 'translate-x-0': openSidebar }">

        <div class="w-64 px-4 py-6 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700 h-full lg:h-auto lg:sticky lg:top-20 rounded-r-lg shadow-md">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-6">
                <img src="/storage/images/moon-logo.png" alt="Logo" class="w-9 h-9 rounded-full">
                <span class="text-2xl font-bold text-gray-800 dark:text-white">SheZen</span>
            </div>

            <!-- Menu -->
            <nav class="space-y-1 text-slate-700 dark:text-slate-200 text-[15px] font-medium">
                <a href="{{ route('tests.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 dark:hover:bg-slate-700">
                    📝 Test
                </a>
                <a href="{{ route('chat.show') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 dark:hover:bg-slate-700">
                    📩 Tin nhắn
                </a>
                <a href="{{ route('forum.category.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 dark:hover:bg-slate-700">
                    💬 Forum
                </a>
            </nav>

            @auth
            <!-- Dropdown: Tư vấn -->
            <div x-data="{ open: false }" class="mt-5 space-y-1">
                <button @click="open = !open"
                    class="flex w-full justify-between items-center px-3 py-2 rounded-md hover:bg-blue-50 dark:hover:bg-slate-700">
                    <span class="flex items-center gap-2">🧠 Tư vấn</span>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-transition x-cloak class="pl-6 mt-2 space-y-1 text-sm">
                    <a href="{{ route('advice.request') }}" class="block hover:underline">Đăng ký</a>
                    <a href="{{ route('advice.history') }}" class="block hover:underline">Lịch sử</a>
                    @if(in_array(auth()->user()->role, ['moderator', 'admin']))
                        <a href="{{ route('advice.manage') }}" class="block hover:underline">Quản lý</a>
                        <a href="{{ route('advice.schedule') }}" class="block hover:underline">Tiếp nhận</a>
                        <a href="{{ route('advice.calendar') }}" class="block hover:underline">Lịch</a>
                    @endif
                </div>
            </div>
            @endauth

            <!-- Tài khoản -->
            <div class="pt-6 mt-6 border-t border-slate-300 dark:border-slate-600 space-y-2 text-sm">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('backpack.dashboard') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 dark:hover:bg-slate-700">
                            Dashboard
                        </a>
                    @endif
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 dark:hover:bg-slate-700">
                        Hồ sơ
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="w-full text-left flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 dark:hover:bg-slate-700">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 dark:hover:bg-slate-700">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 dark:hover:bg-slate-700">
                        Đăng ký
                    </a>
                @endauth
            </div>
        </div>
    </aside>
</div>
