<div class="p-4 border-b border-gray-300">
    <h3 class="text-lg font-semibold mb-2">Lịch sử tin nhắn</h3>
    <div id="messages" class="space-y-2">
        @if(count($messages) > 0)
            @foreach($messages as $message)
            <div class="flex {{ $message->sender->id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="w-2/3 p-3 rounded-lg shadow
                    {{ $message->sender->id == auth()->id() ? 'bg-green-100' : 'bg-blue-100' }}">
                    <strong>{{ $message->sender->getParticipantDetails()['name'] ?? 'Unknown' }}</strong>:
                    {{ $message->body }}
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $message->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
            
            @endforeach
        @else
            <p>Chưa có tin nhắn nào.</p>
        @endif
    </div>
</div>

