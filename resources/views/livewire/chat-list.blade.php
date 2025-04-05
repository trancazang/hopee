<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Danh sách cuộc trò chuyện</h2>

    @if(count($conversations))
        <ul>
            @foreach($conversations as $conv)
                <li class="mb-2">
                    <a href="{{ route('chat.show', ['id' => $conv['id']]) }}"
                       class="block p-2 hover:bg-gray-200 rounded">
                        {{ $conv['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>Chưa có cuộc trò chuyện nào.</p>
    @endif
</div>
