<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SheZen - Chăm sóc tinh thần</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#0d1b2a] text-white">
    <!-- Navbar -->
    <nav class="bg-transparent text-white">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold flex items-center gap-2">
                <img src="/storage/images/moon-logo.png" alt="Logo" class="w-8 h-8"> <!-- Custom logo -->
                <span>SheZen</span>
            </a>
            <div class="flex space-x-6 text-sm">
                <a href="{{ route('tests.index') }}" class="hover:underline">📝 Test</a>
                <a href="{{ route('chat.show') }}" class="hover:underline">📩 Tin nhắn</a>
                <a href="{{ route('forum.category.index') }}" class="hover:underline">💬 Forum</a>
                <a href="{{ route('advice.request') }}" class="hover:underline">💬 Tư vấn</a>
                @auth
                    @if(in_array(auth()->user()->role, ['admin']))
                    <a href="{{ route('backpack.dashboard') }}" class="hover:underline">📊 Dashboard</a>
                    @endif
                    <a href="{{ route('profile') }}" class="hover:underline">👤 Hồ sơ</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">🔑 Đăng nhập</a>
                    <a href="{{ route('register') }}" class="hover:underline">🆕 Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-cover bg-center bg-no-repeat py-32 text-center px-4" style="background-image: url('/storage/images/zen-background.png');">
        <div class="max-w-4xl mx-auto" data-aos="fade-up">
            <h1 class="text-3xl md:text-4xl font-semibold leading-relaxed text-white">
                Sẽ luôn có một đời sống tinh thần đẹp đẽ bên trên một cuộc sống vật chất tầm thường
            </h1>
            <p class="mt-6 text-xl italic text-gray-300">
                Nó tựa như ánh trăng lắp lửng treo trên bầu trời đêm, không chói loà mà chỉ toả ra vằng sáng thanh bình và tĩnh lặng.
            </p>
        </div>
    </section>

    <!-- Services CTA -->
    <section class="bg-[#112d4e] py-20 text-center" data-aos="fade-up">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-white">🌿 Chăm sóc sức khỏe tinh thần của bạn</h2>
            <p class="mt-4 text-gray-300">Khám phá các bài test, chia sẻ cộng đồng và hướng dẫn cải thiện tinh thần.</p>
            <div class="mt-6 space-x-4">
                <a href="{{ route('tests.index') }}" class="inline-block bg-[#fef6e4] text-[#001858] font-semibold py-3 px-6 rounded shadow hover:bg-[#f3eed9] transition">📝 Bắt đầu bài Test</a>
                <a href="{{ route('forum.category.index') }}" class="inline-block bg-[#fef6e4] text-[#001858] font-semibold py-3 px-6 rounded shadow hover:bg-[#f3eed9] transition">💬 Tham gia diễn đàn</a>
                <a href="{{ route('advice.request')}}" class="inline-block bg-[#fef6e4] text-[#001858] font-semibold py-3 px-6 rounded shadow hover:bg-[#f3eed9] transition">💬 Đặt lịch tư vấn</a>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="py-16 px-6 bg-[#0f1f33]">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-3xl font-bold text-center text-white mb-12" data-aos="fade-up">Lợi ích khi tham gia</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-[#fef6e4] text-[#001858] p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-xl font-semibold mb-2">📊 Đánh giá tinh thần</h4>
                    <p>Tự test trầm cảm, lo âu, căng thẳng miễn phí.</p>
                </div>
                <div class="bg-[#fef6e4] text-[#001858] p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-xl font-semibold mb-2">💬 Cộng đồng hỗ trợ</h4>
                    <p>Kết nối, chia sẻ cùng những người đồng cảm.</p>
                </div>
                <div class="bg-[#fef6e4] text-[#001858] p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-xl font-semibold mb-2">📚 Tư vấn trực tuyến</h4>
                    <p>Đặt lịch tư vấn trực tuyến cùng các tình nguyện viên chuyên gia.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-[#112d4e] text-white text-center py-16" data-aos="zoom-in">
        <h3 class="text-3xl font-bold mb-4">Bắt đầu hành trình chữa lành của bạn hôm nay</h3>
        <a href="#" class="inline-block bg-[#fef6e4] text-[#001858] font-semibold py-3 px-6 rounded-lg hover:bg-[#f3eed9] transition">🆕 Đăng ký miễn phí</a>
    </section>

    <!-- Footer -->
    <footer class="bg-[#00111c] text-white text-center py-6 text-sm">
        &copy; Huỳnh Huyền Trân 2024 SheZen. Mọi quyền được bảo lưu.
    </footer>

    <script>
        AOS.init({ once: true });
    </script>
</body>
</html>
