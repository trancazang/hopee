<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdviceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdviceManageComponent extends Component
{   
    use WithPagination;

    // Filter trạng thái (empty = tất cả)
    public string $statusFilter = '';

    // Khi filter thay đổi, reset phân trang
    public function updatedStatusFilter()
    {
        $this->resetPage(); // Để load lại trang đầu sau khi lọc
    }

    /**
     * Moderator dấu completed
     */
    public function markCompleted(int $id)
    {
        $req = AdviceRequest::findOrFail($id);

        // Chỉ moderator phụ trách mới có quyền
        if ($req->moderator_id !== Auth::id()) {
            abort(403);
        }

        $req->update([
            'status' => AdviceRequest::COMPLETED,
        ]);

        session()->flash('success', "Yêu cầu #{$id} đã được đánh dấu là Completed.");
        Log::info("AdviceManageComponent: Request #{$id} marked COMPLETED by moderator " . Auth::id());

        // Refresh lại trang (nếu cần)
        $this->resetPage();
    }

    /**
     * Moderator đánh dấu no-show
     */
    public function markNoShow(int $id)
    {
        $req = AdviceRequest::findOrFail($id);

        if ($req->moderator_id !== Auth::id()) {
            abort(403);
        }

        $req->update([
            'status' => AdviceRequest::NO_SHOW,
        ]);

        session()->flash('warning', "Yêu cầu #{$id} đã được đánh dấu là No-Show.");
        Log::info("AdviceManageComponent: Request #{$id} marked NO-SHOW by moderator " . Auth::id());

        $this->resetPage();
    }

    /**
     * Khi nhấn "Nhận & Lên lịch" → emit event mở ScheduleManager modal
     */
    public function openSchedule(int $id)
    {
        $this->emit('openScheduleModal', $id);
    }

    public function render()
{
    $query = AdviceRequest::with(['user', 'moderator']);

    if (!empty($this->statusFilter)) {
        $query->where('status', $this->statusFilter);
    }

    $requests = $query->latest()->paginate(15);
    Log::info('Filter applied: ' . $this->statusFilter);

    return view('livewire.advice.manage', [
        'requests' => $requests,
    ]);
    
}
}
