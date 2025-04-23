<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 text-gray-800">
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
                <a href="{{ route('forum.category.index') }}" class="hover:text-blue-300 transition-colors duration-200">💬 Forum</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition-colors duration-200">📊 Dashboard</a>
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
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">🔍 Kết quả tìm kiếm cho: "<span class="text-blue-600">{{ $term }}</span>"</h2>

    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-3">🧵 Chủ đề</h3>
        <ul class="list-disc list-inside space-y-2">
            @forelse ($threads as $thread)
                <li>
                    <a href="{{ url('/forum/t/' . $thread->id . '-' . \Str::slug($thread->title)) }}"
                       class="text-blue-500 hover:underline font-medium">
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
                    <a href="{{ url('/forum/c/' . $category->id . '-' . \Str::slug($category->title)) }}"
                       class="text-green-600 hover:underline font-medium">
                        {{ $category->title }}
                    </a>
                </li>
            @empty
                <li class="text-gray-500">Không có danh mục phù hợp.</li>
            @endforelse
        </ul>
    </div>
</div>

</body>
</html>
