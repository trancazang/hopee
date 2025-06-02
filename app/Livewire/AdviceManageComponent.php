<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdviceRequest;

class AdviceManageComponent extends Component
{   
    use WithPagination;

    public string $statusFilter = '';

    public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $q = AdviceRequest::query()->with(['user','moderator']);

        if ($this->statusFilter !== '') {
            $q->where('status', $this->statusFilter);
        }

        return view('livewire.advice.manage', [
            'requests' => $q->latest()->paginate(15),
        ]);
    }
}
