
<div class="max-w-md mx-auto p-6 bg-white shadow rounded-xl mt-6">
    <h2 class="text-lg font-semibold mb-4">Đánh giá phiên tư vấn</h2>
    <form wire:submit.prevent="submit" class="space-y-4">
        <div class="flex items-center gap-2">
            @for ($i = 1; $i <= 5; $i++)
                <label>
                    <input type="radio" wire:model="rating" value="{{ $i }}" class="hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 cursor-pointer {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}">
                        <path d="M11.614 1.924c.2-.608.972-.608 1.172 0l2.148 6.554a.6.6 0 00.57.414h6.887c.63 0 .892.807.383 1.18l-5.57 4.05a.6.6 0 00-.218.67l2.148 6.553c.2.608-.502 1.112-.983.737l-5.57-4.05a.6.6 0 00-.71 0l-5.57 4.05c-.481.375-1.183-.129-.983-.737l2.148-6.553a.6.6 0 00-.218-.67l-5.57-4.05c-.509-.373-.247-1.18.383-1.18h6.887a.6.6 0 00.57-.414l2.148-6.554z" />
                    </svg>
                </label>
            @endfor
            @error('rating') <span class="text-red-600 text-sm ml-2">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block mb-1 font-medium">Nhận xét</label>
            <textarea wire:model.defer="comment" rows="4" class="w-full border rounded px-3 py-2"></textarea>
            @error('comment') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700" wire:loading.attr="disabled">Gửi đánh giá</button>
    </form>
</div>
