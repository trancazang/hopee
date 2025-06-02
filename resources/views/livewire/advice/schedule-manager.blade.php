
<div class="max-w-6xl mx-auto p-6">
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">{{ session('success') }}</div>
    @endif

    @if ($mode === 'list')
        <h2 class="text-xl font-semibold mb-4">Yêu cầu chờ xử lý</h2>
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Người dùng</th>
                    <th class="p-2 border">Thời gian mong muốn</th>
                    <th class="p-2 border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pending as $req)
                    <tr>
                        <td class="p-2 border">{{ $req->id }}</td>
                        <td class="p-2 border">{{ $req->user->name }}</td>
                        <td class="p-2 border">
                            {{ \Carbon\Carbon::parse($req->preferred_time)->format('d/m H:i') }}
                        </td>
                        <td class="p-2 border text-center">
                            <button wire:click="accept({{ $req->id }})" class="px-3 py-1 bg-indigo-600 text-white rounded text-xs">Nhận</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-3 text-center">Không có yêu cầu</td></tr>
                @endforelse
            </tbody>
        </table>
    @elseif ($mode === 'calendar')
        <h2 class="text-xl font-semibold mb-4">Đặt lịch tư vấn</h2>
        <div class="space-y-4 max-w-md">
            <div>
                <label class="block mb-1 font-medium">Thời gian hẹn</label>
                <input type="datetime-local" wire:model.defer="scheduled_at" class="w-full border rounded px-3 py-2">
                @error('scheduled_at') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 font-medium">Link cuộc họp (tuỳ chọn)</label>
                <input type="url" wire:model.defer="meeting_link" class="w-full border rounded px-3 py-2">
                {{-- @error('meeting_link') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror --}}
            </div>
            <div class="flex gap-2">
                <button wire:click="schedule" class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Xác nhận</button>
                <button wire:click="resetForm" class="flex-1 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Huỷ</button>
            </div>
        </div>
    @endif
</div>