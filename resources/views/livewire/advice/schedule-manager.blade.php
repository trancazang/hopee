{{-- resources/views/livewire/advice/schedule-manager.blade.php --}}

@php
    // Nếu Livewire chưa truyền vào, chúng ta khởi tạo mặc định
    $mode       = $mode       ?? 'list';
    $requestId  = $requestId  ?? null;
    $scheduled_at  = $scheduled_at  ?? '';
    $meeting_link  = $meeting_link  ?? '';
    $moderator_notes = $moderator_notes ?? '';
    $scheduled    = $scheduled    ?? collect();
@endphp

<div class="max-w-7xl mx-auto p-6">
    {{-- 1) TRƯỜNG HỢP TRUY CẬP TRỰC TIẾP mode = 'list' --}}
    @if ($mode === 'list')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-4 rounded-lg">
            <h2 class="text-lg font-semibold text-yellow-800 mb-2">Bạn đang ở trang Đặt lịch trực tiếp</h2>
            <p class="text-yellow-700 mb-4">
                Để quản lý và tiếp nhận yêu cầu tư vấn, vui lòng quay về trang Quản lý.
            </p>
            <a href="{{ route('advice.manage') }}"
               class="inline-block bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500 transition">
                ← Quay về Quản lý
            </a>
        </div>
    @endif

    {{-- 2) TRƯỜNG HỢP moderator emit sang mode = 'calendar' --}}
    @if ($mode === 'calendar')
        <div class="mt-6 bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Đặt lịch tư vấn cho yêu cầu #{{ $requestId }}</h3>

            {{-- Lấy thông tin request (chỉ khi $requestId != null) --}}
            @php
                $req = null;
                if ($requestId !== null) {
                    $req = \App\Models\AdviceRequest::find($requestId);
                }
            @endphp

            @if (! $req)
                {{-- Nếu không tìm thấy request, show lỗi nhẹ --}}
                <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-md mb-4">
                    <p class="text-red-700">Yêu cầu tư vấn không tồn tại hoặc đã bị xóa.</p>
                    <a href="{{ route('advice.manage') }}"
                       class="inline-block mt-3 bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition">
                        ← Quay về Quản lý
                    </a>
                </div>
            @else
                {{-- Hiện thông tin request --}}
                <div class="mb-4 border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <p class="text-gray-800 font-medium mb-2">
                        Người gửi: <span class="text-blue-600">{{ $req->user->name }}</span>
                    </p>
                    <p class="text-gray-800 font-medium mb-2">
                        Thời gian mong muốn:
                        <span class="text-blue-600">
                            {{ \Carbon\Carbon::parse($req->preferred_time)->format('H:i d/m/Y') }}
                        </span>
                    </p>
                    <p class="text-gray-800 font-medium mb-2">Ghi chú của user:</p>
                    <p class="text-gray-700 whitespace-pre-line">{{ $req->notes ?? '— Không có ghi chú —' }}</p>
                </div>

                {{-- Form đặt lịch --}}
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Chọn ngày/giờ --}}
                        <div>
                            <label for="scheduled_at" class="block font-medium text-gray-700 mb-1">Chọn thời gian</label>
                            <input
                                type="datetime-local"
                                id="scheduled_at"
                                wire:model.defer="scheduled_at"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                            @error('scheduled_at')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nhập link họp --}}
                        <div>
                            <label for="meeting_link" class="block font-medium text-gray-700 mb-1">Link cuộc họp</label>
                            <input
                                type="url"
                                id="meeting_link"
                                wire:model.defer="meeting_link"
                                placeholder="https://your-meeting-link"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                            @error('meeting_link')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Ghi chú nội bộ --}}
                    <div>
                        <label for="moderator_notes" class="block font-medium text-gray-700 mb-1">Ghi chú nội bộ (cho moderator)</label>
                        <textarea
                            id="moderator_notes"
                            wire:model.defer="moderator_notes"
                            rows="3"
                            class="w-full border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Bạn có thể thêm lưu ý riêng…"
                        ></textarea>
                        @error('moderator_notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                   {{-- Nút Hủy / Hủy lịch / Lưu --}}
                    <div class="flex justify-between items-center mt-4">
                        
                        <div class="flex space-x-3">
                            <button
                                wire:click="$reset(['mode','requestId','scheduled_at','meeting_link','moderator_notes'])"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition"
                            >
                                Hủy
                            </button>
                            <button
                                wire:click="saveSchedule"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                wire:loading.attr="disabled"
                            >
                                Lưu lịch
                            </button>
                        </div>
</div>

                </div>
            @endif
        </div>
    @endif

   
    @if ($mode === 'list')
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Danh sách yêu cầu đang chờ</h2>
            <table class="w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Người dùng</th>
                        <th class="p-2 border">Thời gian mong muốn</th>
                        <th class="p-2 border">Ghi chú</th>
                        <th class="p-2 border">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scheduled as $req)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border text-center">{{ $req->id }}</td>
                            <td class="p-2 border">{{ $req->user->name }}</td>
                            <td class="p-2 border">
                                {{ \Carbon\Carbon::parse($req->preferred_time)->format('d/m H:i d-m-Y') }}
                            </td>
                            <td class="p-2 border">
                                <p class="whitespace-pre-line text-gray-700 text-sm">{{ Str::limit($req->notes, 50) }}</p>
                            </td>
                            <td class="p-2 border text-center">
                                <button
                                        wire:click="fillSchedule({{ $req->id }})"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                                    >
                                    Tiếp nhận 
                                    </button>
                                    <button
                                    wire:click="cancelSchedule({{ $req->id }})"
                                    type="button"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                    >
                                    Hủy lịch 
                                </button>          
                            </td>
                        </tr>
                    @endforeach
                    @if ($scheduled->isEmpty())
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-600">Không có yêu cầu nào đang chờ.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif
   
</div>
