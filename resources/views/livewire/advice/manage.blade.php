
<div class="max-w-7xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Quản lý yêu cầu tư vấn</h2>
        <select wire:model="statusFilter" class="border rounded px-3 py-1">
            <option value="">Tất cả</option>
            <option value="pending">Pending</option>
            <option value="scheduled">Scheduled</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">User</th>
                <th class="p-2 border">Status</th>
                
                <th class="p-2 border">Preferred</th>
                <th class="p-2 border">Scheduled</th>
                <th class="p-2 border">Moderator</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $req)
                <tr>
                    <td class="p-2 border">{{ $req->id }}</td>
                    <td class="p-2 border">{{ $req->user->name }}</td>
                    <td class="p-2 border">{{ $req->status }}</td>
                    
                    <td class="p-2 border">
                        {{ \Carbon\Carbon::parse($req->preferred_time)->format('d/m H:i') }}
                    </td>
                    <td class="p-2 border">
                        {{ $req->scheduled_at ? \Carbon\Carbon::parse($req->scheduled_at)->format('d/m H:i') : '-' }}
                    </td>
                  
                    <td class="p-2 border">{{ $req->moderator?->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $requests->links() }}</div>
</div>
