<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AdviceRequest;
use App\Models\ModeratorSchedule;
use App\Models\User;
use App\Models\Test;
use TeamTeaTime\Forum\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdviceRequestForm extends Component
{
    public $moderators;
    public ?int $moderator_id = null;
    public array $availableDates = [];
    public ?string $selectedDate = null;
    public array $slots = [];
    public ?string $selectedSlot = null;

    public string $category = '';
    public string $notes = '';
    public bool $confirm_info = false;
    public function updatedModeratorId($id)
    {
        $this->reset(['selectedDate', 'slots']);
        $this->loadAvailableDates();
    }

    public function mount()
    {
        $this->moderators = User::where('role', 'moderator')->get();
        logger('\ud83d\udce5 Mount: Moderators loaded', $this->moderators->pluck('name', 'id')->toArray());
    }

    public function selectModerator($id)
    {
        if ($this->moderator_id !== $id) {
            $this->moderator_id = $id;
            $this->selectedDate = null;
            $this->selectedSlot = null;
            $this->slots = [];

            logger("🧑 Selected moderator_id = $id");

            $this->availableDates = ModeratorSchedule::where('moderator_id', $id)
            ->where('is_available', true)
            ->whereDate('slot_date', '>', Carbon::today()) // chỉ lấy ngày lớn hơn hôm nay
            ->pluck('slot_date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->all();

            logger("📆 Available Dates", $this->availableDates);
        }
    }
    public function loadSlots()
    {
        if (!$this->moderator_id || !$this->selectedDate) return;

        logger("📅 Người dùng chọn: {$this->selectedDate}");
        logger("🧑 Moderator ID đang được chọn: {$this->moderator_id}");

        $raw = ModeratorSchedule::where('moderator_id', $this->moderator_id)
            ->where('slot_date', $this->selectedDate)
            ->get();

        logger("🔧 Raw slot schedules", $raw->toArray());

        $this->slots = $raw->map(function ($slot) {
            return [
                'time' => Carbon::parse($slot->slot_time)->format('H:i'),
                'is_available' => $slot->is_available,
            ];
        })->toArray();

        if (empty($this->slots)) {
            session()->flash('no_slots', 'Hiện tại moderator chưa có lịch tư vấn vào ngày này.');
        }

        logger("✅ Slot đã load", $this->slots);
    }

    


    public function selectSlot($time)
    {
        $this->selectedSlot = $time;
        logger("\u2705 Selected slot: $time");
    }

    protected function rules(): array
    {
        return [
            'moderator_id'   => ['required', 'exists:users,id'],
            'selectedDate'   => ['required', 'date'],
            'selectedSlot'   => ['required', 'date_format:H:i'],
            'category'       => ['required', 'string', 'max:255'],
            'confirm_info'   => ['accepted'],
        ];
    }

    public function submit()
    {
        $this->validate();

        $scheduledAt = Carbon::parse("{$this->selectedDate} {$this->selectedSlot}:00");

        AdviceRequest::create([
            'user_id'        => Auth::id(),
            'moderator_id'   => $this->moderator_id,
            'preferred_time' => $scheduledAt,
            'scheduled_at'   => $scheduledAt,
            'notes'          => $this->category . (trim($this->notes) ? "\n\n{$this->notes}" : ''),
            'status'         => AdviceRequest::PENDING,
        ]);

        session()->flash('success', 'Đăng ký tư vấn thành công!');

        $this->reset([
            'moderator_id', 'availableDates', 'selectedDate', 'slots', 'selectedSlot',
            'category', 'notes', 'confirm_info'
        ]);
    }

    public function render()
    {
        return view('livewire.advice.request', [
            'categories'     => Category::pluck('title', 'title'),
            'tests'          => Test::all(),
            'moderators'     => $this->moderators,
            'availableDates' => $this->availableDates,
            'slots'          => $this->slots,
        ]);
    }
}
