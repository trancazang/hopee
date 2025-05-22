<div >
    <button wire:click="openModal" class="text-gray-600 hover:text-red-600 flex flex-row items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
      </svg>
       Báo cáo
    </button>

    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-md w-full max-w-md relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-black">&times;</button>
                <h3 class="text-lg font-semibold mb-3">Báo cáo bài viết</h3>

                <div class="mb-3">
                    <label class="block text-sm">Chọn lý do:</label>
                    <select wire:model="reason_select" class="w-full border p-2 rounded">
                        <option value="">-- chọn --</option>
                        <option value="Ngôn từ xúc phạm">Ngôn từ xúc phạm</option>
                        <option value="Spam / quảng cáo">Spam / quảng cáo</option>
                        <option value="Thông tin sai sự thật">Thông tin sai sự thật</option>
                        <option value="Nội dung không phù hợp">Nội dung không phù hợp</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Hoặc lý do khác:</label>
                    <textarea wire:model="reason_custom" class="w-full border p-2 rounded" rows="3"></textarea>
                </div>

                <button wire:click="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Gửi báo cáo
                </button>
            </div>
        </div>
    @endif
</div>

