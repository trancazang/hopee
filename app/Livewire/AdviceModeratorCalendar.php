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
    {   logger('💡 slot_date: ' . $this->slot_date);
        logger('💡 slot_time: ' . $this->slot_time);
    
        $this->validate([
            'slot_date' => 'required|date',
            'slot_time' => 'required',
        ]);
        
        ModeratorSchedule::create([
            'moderator_id' => Auth::id(),
            'slot_date' => $this->slot_date,
            'slot_time' => $this->slot_time,
            'is_available' => $this->is_available,
        ]);

        session()->flash('success', '🗓️ Lịch tư vấn đã được tạo!');
        $this->reset(['slot_date', 'slot_time', 'is_available']);
     
        
    }
    public function render()
    {
        $schedules = ModeratorSchedule::where('moderator_id', Auth::id())->orderBy('slot_date')->get();
        return view('livewire.advice.calendar', compact('schedules'));
    }
    
}
