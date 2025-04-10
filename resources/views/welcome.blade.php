

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SheZen Harmony</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
       <!-- Navbar -->
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
                    @if(in_array(auth()->user()->role, ['admin', 'moderator']))
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition-colors duration-200">📊 Dashboard</a>
                    @endif
                    <a href="{{ route('profile') }}" class="hover:text-blue-300 transition-colors duration-200">👤 Hồ sơ</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-blue-300 transition-colors duration-200">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-300 transition-colors duration-200">🔑 Đăng nhập</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-300 transition-colors duration-200">🆕 Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-500 text-white py-20 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold">🌿 Chăm sóc sức khỏe tinh thần của bạn</h1>
            <p class="mt-4 text-lg">Khám phá các bài test tâm lý, diễn đàn chia sẻ và tài liệu hữu ích để cải thiện sức khỏe tinh thần.</p>
            <a href="{{ route('tests.index') }}" class="mt-6 inline-block bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-200 transition">
                📝 Bắt đầu bài Test
            </a>
            <a href="{{ route('forum.category.index') }}" class="mt-6 inline-block bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-200 transition">
                💬 Tham gia diễn đàn
            </a>
        </div>
    </section>

    <!-- Lợi ích -->
    <section class="container mx-auto my-12 px-6">
        <h2 class="text-3xl font-bold text-center text-blue-600">Lợi ích khi tham gia</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mt-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">📊 Đánh giá tinh thần</h3>
                <p class="text-gray-600 mt-2">Thực hiện các bài test trầm cảm, lo âu và căng thẳng.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">💬 Cộng đồng hỗ trợ</h3>
                <p class="text-gray-600 mt-2">Kết nối với những người có cùng hoàn cảnh và chia sẻ kinh nghiệm.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">📚 Kiến thức bổ ích</h3>
                <p class="text-gray-600 mt-2">Cập nhật thông tin khoa học về sức khỏe tinh thần.</p>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-gray-100 py-16 text-center">
        <h2 class="text-3xl font-bold text-blue-600">Bắt đầu hành trình chăm sóc tinh thần ngay hôm nay!</h2>
        <a href="{{ route('register') }}" class="mt-6 inline-block bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
            🆕 Đăng ký miễn phí
        </a>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white text-center py-6 mt-12">
        <p>&copy; 2024 Mental Health. Mọi quyền được bảo lưu.</p>
    </footer>
    <a href="{{ route('chat.show') }}"
   class="fixed bottom-6 right-6 bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-full shadow-lg">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.883L3 20l1.117-4.487A7.96 7.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
  </svg>
</a>

</body>
</html>
