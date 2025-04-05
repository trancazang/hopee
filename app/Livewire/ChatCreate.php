<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Musonza\Chat\Facades\ChatFacade as Chat;

class ChatCreate extends Component
{   
    public $selectedUserIds = [];
    // Danh sách tất cả người dùng (để hiển thị trong dropdown)
    public $allUsers = [];

    public function mount()
    {
        // Lấy danh sách tất cả người dùng trừ người đang đăng nhập (nếu cần)
        $this->allUsers = User::where('id', '!=', auth()->id())->get();
    }
    
    public function createConversation()
    {
        // 1. Validate đầu vào
        $this->validate([
            'selectedUserIds' => 'required|array|min:1',
        ], [
            'selectedUserIds.required' => 'Vui lòng chọn ít nhất một người tham gia.',
            'selectedUserIds.min'      => 'Vui lòng chọn ít nhất một người tham gia.',
        ]);

        // 2. Lấy ID và instance của user hiện tại
        $userId = auth()->id();
        if (!$userId) {
            session()->flash('error', 'Bạn phải đăng nhập trước khi tạo cuộc trò chuyện.');
            return;
        }

        $currentUser = User::find($userId);
        if (!$currentUser) {
            session()->flash('error', 'Không tìm thấy tài khoản người dùng.');
            return;
        }

        // 3. Lấy các model User từ selectedUserIds
        $others = User::whereIn('id', $this->selectedUserIds)->get();
        if ($others->isEmpty()) {
            session()->flash('error', 'Không tìm thấy người dùng được chọn.');
            return;
        }

        // 4. Thêm user hiện tại vào collection và chuyển sang array
        $participants = $others->push($currentUser)->all();

        // 5. Tạo conversation (mặc định public, có thể ->makePrivate() hoặc ->makeDirect())
        try {
            $conversation = Chat::createConversation($participants);
        } catch (\Exception $e) {
            session()->flash('error', 'Tạo cuộc trò chuyện thất bại: ' . $e->getMessage());
            return;
        }

        // 6. Thông báo thành công và redirect
        session()->flash('message', 'Cuộc trò chuyện đã được tạo thành công!');
        return redirect()->route('chat.show', ['id' => $conversation->id]);
    }
    

    public function render()
    {
        return view('livewire.chat-create');
    }
}
