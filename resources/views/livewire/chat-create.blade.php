<div class="p-4 border rounded bg-white shadow">
    <h2 class="text-xl font-bold mb-4">Tạo Cuộc Trò Chuyện Mới</h2>
    @if (session()->has('message'))
        <div class="mb-4 p-2 bg-green-100 text-green-700">
            {{ session('message') }}
        </div>
    @endif
    <form wire:submit.prevent="createConversation">
        <div class="mb-4">
            <label for="users" class="block font-semibold mb-1">Chọn người tham gia:</label>
            <select wire:model="selectedUserIds" id="users" multiple class="w-full border rounded px-3 py-2">
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            @error('selectedUserIds') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Tạo Cuộc Trò Chuyện
        </button>
    </form>
</div>
