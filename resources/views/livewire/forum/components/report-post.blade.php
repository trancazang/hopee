<div>
    <button wire:click="openModal" class="text-gray-600 hover:text-red-600">🏴 Báo cáo</button>

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

