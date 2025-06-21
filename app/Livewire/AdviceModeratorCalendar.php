<?php

namespace App\Livewire;
use App\Models\ModeratorSchedule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class AdviceModeratorCalendar extends Component

{   public $slot_date;
    public $slot_time;
    public $is_available = true;

    public function save()
    {
        $this->validate([
            'slot_date' => ['required', 'date', 'after:today'],
            'slot_time' => ['required'],
        ], [
            'slot_date.after' => 'Ngày phải lớn hơn ngày hôm nay.',
        ]);
        

        // 🔒 Kiểm tra lịch trùng
        $exists = ModeratorSchedule::where('moderator_id', Auth::id())
            ->where('slot_date', $this->slot_date)
            ->where('slot_time', $this->slot_time)
            ->exists();

        if ($exists) {
            session()->flash('error', '❌ Lịch trùng! Bạn đã đăng ký khung giờ này.');
            return;
        }

        // ✅ Nếu không trùng thì tạo mới
        ModeratorSchedule::create([
            'moderator_id' => Auth::id(),
            'slot_date' => $this->slot_date,
            'slot_time' => $this->slot_time,
            'is_available' => $this->is_available,
        ]);

        session()->flash('success', '🗓️ Lịch tư vấn đã được tạo!');
        $this->reset(['slot_date', 'slot_time', 'is_available']);
    }

    // ❌ Huỷ sẵn sàng (is_available = false)
    public function cancelAvailability($id)
    {
        $schedule = ModeratorSchedule::where('moderator_id', Auth::id())->findOrFail($id);
        $schedule->is_available = false;
        $schedule->save();

        session()->flash('success', '❎ Bạn đã huỷ lịch sẵn sàng.');
    }

    public function render()
    {
        $schedules = ModeratorSchedule::where('moderator_id', Auth::id())
            ->orderBy('slot_date')
            ->get();

        return view('livewire.advice.calendar', compact('schedules'));
    }
    
}
