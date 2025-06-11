<div class="max-w-2xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">📅 Đăng ký lịch tư vấn</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label>Ngày:</label>
            <input type="date" wire:model="slot_date" class="form-input w-full" required>
            @error('slot_date') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Chọn giờ:</label>
            
            {{-- Giờ mặc định --}}
            <div class="flex flex-wrap gap-2 mb-2">
                @foreach (['08:00', '09:00', '10:00', '14:00', '15:00', '16:00', '18:00', '19:00','20:00'] as $preset)
                    <button
                        type="button"
                        wire:click="$set('slot_time', '{{ $preset }}')"
                        class="px-3 py-1 border rounded
                            {{ $slot_time === $preset ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        {{ $preset }}
                    </button>
                @endforeach
            </div>
        
            {{-- Giờ tuỳ chỉnh --}}
            <input type="time" wire:model="slot_time" class="form-input w-full">
            @error('slot_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="is_available" class="form-checkbox">
                <span class="ml-2">Sẵn sàng tiếp nhận tư vấn</span>
            </label>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lưu lịch</button>
    </form>

    <hr class="my-6">

    <h3 class="text-lg font-semibold mb-2">📋 Danh sách lịch đã đăng ký</h3>

    <table class="table-auto w-full text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Ngày</th>
                <th class="px-4 py-2">Giờ</th>
                <th class="px-4 py-2">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($schedules as $slot)
                <tr>
                    <td class="px-4 py-2">{{ $slot->slot_date }}</td>
                    <td class="px-4 py-2">{{ $slot->slot_time }}</td>
                    <td class="px-4 py-2">
                        @if ($slot->is_available)
                            <span class="text-green-600">✅ Sẵn sàng</span>
                        @else
                            <span class="text-red-600">❌ Không sẵn sàng</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-2 text-center text-gray-500">Chưa có lịch nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
