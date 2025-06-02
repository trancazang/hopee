<div class="max-w-xl mx-auto p-6 bg-white shadow rounded-xl mt-6">
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div>
            <label class="block mb-1 font-medium">Thời gian mong muốn</label>
            <input type="datetime-local" wire:model.defer="preferred_time" class="w-full border rounded px-3 py-2">
            @error('preferred_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Ghi chú (tuỳ chọn)</label>
            <textarea wire:model.defer="notes" rows="4" class="w-full border rounded px-3 py-2"></textarea>
            @error('notes') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700" wire:loading.attr="disabled">Gửi yêu cầu</button>
    </form>
</div>