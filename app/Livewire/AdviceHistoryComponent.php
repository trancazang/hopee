<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Auth;
use App\Models\AdviceRequest;
use Livewire\WithPagination;
use Livewire\Component;

class AdviceHistoryComponent extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function render()
    {
        $query = AdviceRequest::with(['moderator', 'rating'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.advice.history', [
            'requests' => $query->paginate(10),
        ]);
    }
}
