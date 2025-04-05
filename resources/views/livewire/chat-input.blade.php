<div class="p-4 border-t border-gray-300">
    @if (session()->has('error'))
        <div class="mb-2 text-red-500">
            {{ session('error') }}
        </div>
    @endif
    <form wire:submit.prevent="sendMessage" class="flex">
        <input type="text" wire:model="messageText" placeholder="Nhập tin nhắn..."
               class="flex-1 border rounded-l px-4 py-2 focus:outline-none">
        <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600">
            Gửi
        </button>
    </form>
</div>
