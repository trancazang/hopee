<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AdviceRequest;
use App\Models\ModeratorSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\AdviceRequestNotifier;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class ScheduleManager extends Component
{   
    public string $mode = 'list';               // list | calendar
    public ?int   $requestId = null;            // id yêu cầu đang xử lý
    public string $scheduled_at = '';
    public string $meeting_link = '';
    public $scheduled = [];
    public ?string $successMessage = null;

    /* Nhận yêu cầu */
    
    public function accept(int $id): void
    {      Log::info('ScheduleManager::accept() được gọi với requestId=' . $id);

        // Gán ID request và chuyển màn hình sang form nhập lịch
        $this->requestId = $id;
        $this->mode = 'calendar';
        

    }

    /* Lưu lịch */
    public function saveSchedule(): void
    {
        $this->validate([
            'scheduled_at' => ['required', 'date', 'after:today'],
            'meeting_link' => ['required', 'url'],
        ], [
            'scheduled_at.after' => 'Ngày đặt lịch phải lớn hơn ngày hôm nay.',
            'scheduled_at.required' => 'Vui lòng chọn ngày giờ.',
        ]);
    
        $req = AdviceRequest::findOrFail($this->requestId);
    
        $exists = AdviceRequest::where('moderator_id', Auth::id())
            ->where('scheduled_at', Carbon::parse($this->scheduled_at))
            ->where('status', '!=', AdviceRequest::PENDING)
            ->exists();
    
        if ($exists) {
            $this->addError('scheduled_at', message: 'Trùng lịch! Hãy chọn thời gian khác.');
            return;
        }
    
        $req->update([
            'moderator_id'  => Auth::id(),
            'scheduled_at'  => $this->scheduled_at,
            'meeting_link'  => $this->meeting_link,
            'status'        => AdviceRequest::SCHEDULED,
        ]);
    
        ModeratorSchedule::where('moderator_id', Auth::id())
            ->where('slot_date', Carbon::parse($this->scheduled_at)->toDateString())
            ->where('slot_time', Carbon::parse($this->scheduled_at)->format('H:i'))
            ->update(['is_available' => false]);
    
        try {
            app(AdviceRequestNotifier::class)->notify($req);
        } catch (\Throwable $e) {
            Log::error("Lỗi gửi thông báo: " . $e->getMessage());
        }
    
        unset($this->requestId, $this->scheduled_at, $this->meeting_link);
        $this->successMessage = ' Đã đặt lịch thành công và gửi thông báo đến người yêu cầu!';
        $this->mode = 'list';
    
    }
    
    
    protected $listeners = [
        'openScheduleModal'
    ];
    
    public function openScheduleModal(int $id)
    {
        $this->requestId = $id;
        $this->mode = 'calendar';
    }

    public function render()
    {
        return view('livewire.advice.schedule-manager', [
            'pending' => AdviceRequest::pending()->latest()->get(),
            'mySlots' => ModeratorSchedule::where('moderator_id', Auth::id())->get(),
        ]);
    }
    public function resetForm()
    {
        $this->reset(); 
    }
    public function mount()
    {
        $this->scheduled= AdviceRequest::where('moderator_id', Auth::id())
            ->where('status', 'pending'|| 'scheduled')  
            ->get();
    }
    public function fillSchedule($id)
    {
        $req = AdviceRequest::findOrFail($id);

        // Chỉ cho phép nếu moderator là người đăng nhập
        if ($req->moderator_id !== Auth::id()) {
            abort(403);
        }

        $this->requestId = $id;
        $this->scheduled_at = $req->scheduled_at ?? now()->addDays(1)->format('Y-m-d\TH:i');
        $this->meeting_link = $req->meeting_link ?? '';
        $this->moderator_notes = $req->moderator_notes ?? '';
        $this->mode = 'calendar';
    }
    public function cancelSchedule($id)
    {
        $req = AdviceRequest::find($id);
    
        if (!$req) {
            session()->flash('error', 'Không tìm thấy yêu cầu.');
            return;
        }
    
        // Chỉ cho phép huỷ nếu trạng thái là scheduled và đúng moderator
        if ($req->status !== 'pending' || $req->moderator_id !== auth()->id()) {
            session()->flash('error', 'Bạn không có quyền huỷ lịch này.');
            return;
        }
    
        // Huỷ lịch: xoá thông tin và đưa về trạng thái chờ xử lý
        if ($req) {
            $req->update([
                'scheduled_at' => null,
                'meeting_link' => null,
                'moderator_notes' => null,
                'status' => AdviceRequest::CANCELLED, // hoặc trạng thái mặc định chờ xử lý
            ]);
        }
    
        session()->flash('success', 'Đã huỷ lịch thành công.');
    
        // Cập nhật lại danh sách
        $this->scheduled = AdviceRequest::where('status', 'canceled')->get();
    }
    
}
