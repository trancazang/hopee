{{-- @props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr> --}}
 
{{-- @props(['url' => url('/')])

<nav class="bg-blue-600 text-white py-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center px-6">
        <!-- Logo -->
        <a href="{{ $url }}" class="text-2xl font-bold hover:text-gray-300">🧠 Mental Health</a>

        <!-- Navigation Links -->
        <div class="space-x-6">
            <a href="{{ route('tests.index') }}" class="hover:underline">📝 Test</a>
            <a href="{{ route('forum.index') }}" class="hover:underline">💬 Forum</a>

            @auth
                <a href="{{ route('profile') }}" class="hover:underline">👤 Hồ sơ</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:underline">🚪 Đăng xuất</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:underline">🔑 Đăng nhập</a>
                <a href="{{ route('register') }}" class="hover:underline">🆕 Đăng ký</a>
            @endauth
        </div>
    </div>
</nav> --}}
