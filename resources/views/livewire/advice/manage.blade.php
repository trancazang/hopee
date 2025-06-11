{{-- resources/views/livewire/advice/manage.blade.php --}}
<div class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Quản lý yêu cầu tư vấn</h2>

    {{-- Dropdown lọc trạng thái --}}
    <div class="flex justify-end mb-4">
<select wire:change="$refresh" wire:model.defer="statusFilter" ...>
            class="border rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">Tất cả</option>
            <option value="pending">Pending</option>
            <option value="scheduled">Scheduled</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="no-show">No-show</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-left">#</th>
                    <th class="p-3 border text-left">Người yêu cầu</th>
                    <th class="p-3 border text-left">Trạng thái</th>
                    <th class="p-3 border text-left">Thời gian mong muốn</th>
                    <th class="p-3 border text-left">Thời gian lên lịch</th>
                    <th class="p-3 border text-left">Chuyên gia</th>
                    <th class="p-3 border text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $req)
                <tr wire:key="req-{{ $req->id }}" class="hover:bg-gray-50">                        
                    <td class="p-3 border">{{ $req->id }}</td>
                        <td class="p-3 border">{{ $req->user->name }}</td>
                        <td class="p-3 border capitalize">{{ $req->status }}</td>
                        <td class="p-3 border">
                            {{ $req->preferred_time
                                ? \Carbon\Carbon::parse($req->preferred_time)->format('d/m H:i')
                                : '-' }}
                        </td>
                        <td class="p-3 border">
                            {{ $req->scheduled_at
                                ? \Carbon\Carbon::parse($req->scheduled_at)->format('d/m H:i')
                                : '-' }}
                        </td>
                        <td class="p-3 border">{{ optional($req->moderator)->name ?? '-' }}</td>
                        <td class="p-3 border text-center">
                            {{-- Chỉ hiển nút "Hoàn thành" nếu: (1) status chưa phải completed, (2) moderator chính là người đang login --}}
                            @if ($req->status == 'scheduled' && $req->moderator_id === Auth::id())
                                <button
                                    wire:click="markCompleted({{ $req->id }})"
                                    class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition"
                                    onclick="confirm('Bạn có chắc chắn muốn đánh dấu #{{ $req->id }} là Completed?') || event.stopImmediatePropagation()"
                                >
                                    Hoàn thành
                                </button>
                                                          
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-600">
                            Không tìm thấy yêu cầu nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
