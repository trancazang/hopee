@livewireStyles
@livewireScripts

<div class="max-w-7xl mx-auto py-10 px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
    {{-- CỘT TRÁI: DANH SÁCH BÀI TEST --}}
    <div class="bg-white p-6 rounded-lg shadow space-y-4">
        <h2 class="text-2xl font-semibold text-pink-700">Các bài test sàng lọc tâm lý</h2>
        <p class="text-pink-500">Chọn bài test phù hợp để đánh giá tình trạng tâm lý của bạn</p>
        @if(isset($tests) && $tests->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($tests as $test)
                    <div class="bg-white rounded-lg shadow-md border border-pink-200 p-4 hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold text-pink-700 mb-2">{{ $test->title }}</h3>
                        <p class="text-pink-600 text-sm mb-4">{{ Str::limit($test->description, 80) }}</p>
                        <a href="{{ route('tests.show', $test) }}" class="block text-center bg-pink-500 text-white font-medium py-2 rounded-md hover:bg-pink-600 transition">
                            Bắt đầu
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-8">Hiện chưa có bài test nào.</div>
        @endif
    </div>

    {{-- CỘT PHẢI: FORM ĐĂNG KÝ TƯ VẤN --}}
    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
        @if (session()->has('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-xl font-semibold mb-6 text-pink-700"> Đăng ký tư vấn tâm lý</h2>

        {{-- 1. Chọn người tư vấn --}}
        <div class="mb-6">
            <label class="block font-semibold text-gray-700 mb-2">1. Chọn người hỗ trợ:</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ($moderators as $mod)
                    <button
                        wire:click="selectModerator({{ $mod->id }})"
                        class="border px-4 py-2 rounded 
                            {{ $moderator_id === $mod->id 
                                ? 'bg-pink-600 text-white' 
                                : 'bg-white text-pink-700 hover:bg-pink-100 border-pink-300' }}"
                    >
                        {{ $mod->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- 2. Chọn ngày --}}
        @if ($availableDates)
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">2. Chọn ngày hẹn:</label>
                <input
                    type="date"
                    wire:model="selectedDate"
                    wire:change="loadSlots"
                    class="border border-pink-300 px-4 py-2 rounded w-full focus:ring-pink-400 focus:border-pink-400"
                    min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                    max="{{ collect($availableDates)->max() }}"
                />
            </div>
        @endif

        {{-- 3. Chọn khung giờ --}}
        @if ($slots)
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">3. Chọn khung giờ:</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($slots as $slot)
                        <button
                            @if (! $slot['is_available'])
                                disabled
                                class="px-4 py-2 border rounded bg-gray-200 text-gray-500 cursor-not-allowed"
                            @else
                                wire:click="selectSlot('{{ $slot['time'] }}')"
                                class="px-4 py-2 border rounded 
                                    {{ $selectedSlot === $slot['time'] 
                                        ? 'bg-pink-600 text-white' 
                                        : 'hover:bg-pink-100 text-pink-700 border-pink-300' }}"
                            @endif
                        >
                            {{ $slot['time'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 4. Thông tin tư vấn --}}
        <div class="mb-4">
            <label class="block font-semibold text-gray-700 mb-2">4. Chuyên mục tư vấn:</label>
            <select wire:model="category" class="w-full border border-pink-300 rounded px-3 py-2 focus:ring-pink-400 focus:border-pink-400">
                <option value="">-- Chọn chuyên mục --</option>
                @foreach ($categories as $title => $value)
                    <option value="{{ $title }}">{{ $value }}</option>
                @endforeach
            </select>
            @error('category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold text-gray-700 mb-2">Ghi chú thêm (nếu có):</label>
            <textarea wire:model="notes" rows="3" class="w-full border border-pink-300 rounded px-3 py-2 focus:ring-pink-400 focus:border-pink-400" placeholder="Bạn muốn chia sẻ thêm điều gì không?"></textarea>
        </div>

        <div class="mb-6">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="confirm_info" class="mr-2 text-pink-600 focus:ring-pink-500">
                Tôi xác nhận thông tin trên là chính xác.
            </label>
            @error('confirm_info') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button
                wire:click="submit"
                class="px-6 py-2 bg-pink-600 text-white rounded hover:bg-pink-700 transition"
            >
                Gửi yêu cầu tư vấn
            </button>
        </div>
    </div>
</div>
