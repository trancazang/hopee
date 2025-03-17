<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SheZen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="text-3xl font-bold flex items-center">
                <span class="mr-2">🧠</span>
                <span>SheZen</span>
            </a>
            <div class="flex items-center space-x-6">
                <a href="/tests" class="hover:text-blue-300 transition-colors duration-200">📝 Test</a>
                <a href="/forum" class="hover:text-blue-300 transition-colors duration-200">💬 Forum</a>
                <a href="/dashboard" class="hover:text-blue-300 transition-colors duration-200">📊 Dashboard</a>
                <a href="/profile" class="hover:text-blue-300 transition-colors duration-200">👤 Hồ sơ</a>
                <a href="/logout" class="hover:text-blue-300 transition-colors duration-200">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-6 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">📊 Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-blue-500 text-white rounded-lg shadow">
                <h3 class="text-lg font-semibold">Người dùng</h3>
                <p class="text-2xl">1,245</p>
            </div>
            <div class="p-4 bg-green-500 text-white rounded-lg shadow">
                <h3 class="text-lg font-semibold">Bài Test</h3>
                <p class="text-2xl">342</p>
            </div>
            <div class="p-4 bg-yellow-500 text-white rounded-lg shadow">
                <h3 class="text-lg font-semibold">Bài viết trên Forum</h3>
                <p class="text-2xl">780</p>
            </div>
            <a href="{{ route('admin.tests.index') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow">
                ➕ Thêm bài test
            </a>
    
    </div>
    
    <!-- Yield Content -->
    <div class="container mx-auto mt-6">
        @yield('content')
    </div>
</body>
</html>
