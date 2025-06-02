<div class="max-w-5xl mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Lịch sử yêu cầu tư vấn</h2>

    <select wire:model="statusFilter" class="mb-4 border rounded px-3 py-1">
        <option value="">Tất cả</option>
        <option value="pending">Pending</option>
        <option value="scheduled">Scheduled</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>

    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">Trạng thái</th>
                <th class="p-2 border">Thời gian mong muốn</th>
                <th class="p-2 border">Lịch hẹn</th>
                <th class="p-2 border">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $req)
                <tr>
                    <td class="p-2 border">{{ $req->id }}</td>
                    <td class="p-2 border">{{ $req->status }}</td>
                    <td class="p-2 border">{{ $req->preferred_time ? \Carbon\Carbon::parse($req->preferred_time)->format('d/m H:i') : '-' }}</td>
                    <td class="p-2 border">{{ $req->scheduled_at ? \Carbon\Carbon::parse($req->scheduled_at)->format('d/m H:i') : '-' }}</td>
                    <td class="p-2 border">{{ $req->moderator?->name ?? '-' }}</td>
                    <td class="p-2 border">
                        @if ($req->rating)
                            ⭐ {{ $req->rating->rating }}<br>
                            <span class="text-sm text-gray-600 italic">{{ $req->rating->comment }}</span>
                        @else
                            -
                        @endif
                    </td>

                    <td class="p-2 border">
                        @if ($req->status === 'completed')
                            @if ($req->rating)
                                <span class="text-green-600 text-sm">Đã đánh giá</span>
                            @else
                                <a href="{{ route('advice.rate', $req->id) }}" class="text-blue-500 underline text-sm">Đánh giá</a>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Không có yêu cầu nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $requests->links() }}</div>
</div>
