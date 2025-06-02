<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AdviceRequest;
use App\Models\ModeratorSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\AdviceRequestNotifier;

use Carbon\Carbon;

class ScheduleManager extends Component
{   
    public string $mode = 'list';               // list | calendar
    public ?int   $requestId = null;            // id yêu cầu đang xử lý
    public string $scheduled_at = '';
    public string $meeting_link = '';

    /* Nhận yêu cầu */
    public function accept(int $id): void
    {       \Log::info('confirmAccept() được gọi với requestId=' . $this->requestId);

        $this->requestId = $id;
        $this->mode = 'calendar';
        $request = AdviceRequest::findOrFail($this->requestId);
        $request->update([
        'status' => AdviceRequest::SCHEDULED,
        'moderator_id' => auth()->id(),
    ]);

    app(AdviceRequestNotifier::class)->notify($request);
    session()->flash('success', 'Đã tiếp nhận & gửi thông báo.');
    }

    /* Lưu lịch */
    public function schedule(): void
    {
        $this->validate([
            'scheduled_at' => ['required','date','after:now'],
            'meeting_link' => ['nullable','url'],
        ]);

        $req = AdviceRequest::findOrFail($this->requestId);

        // đảm bảo moderator không đặt trùng
        $exists = AdviceRequest::where('moderator_id', Auth::id())
                 ->where('scheduled_at', Carbon::parse($this->scheduled_at))
                 ->exists();
        if ($exists) {
            $this->addError('scheduled_at','Trùng lịch! Hãy chọn thời gian khác.');
            return;
        }

        $req->update([
            'moderator_id' => Auth::id(),
            'scheduled_at' => $this->scheduled_at,
            'meeting_link' => $this->meeting_link,
            'status'       => AdviceRequest::SCHEDULED,
        ]);

        // có thể dispatch Mail ở đây
        session()->flash('success','Đã đặt lịch thành công.');
        $this->reset(['mode','requestId','scheduled_at','meeting_link']);
        
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
    

}
