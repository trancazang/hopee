<?php

namespace App\Livewire\Forum\Components;

use Livewire\Component;
use App\Models\ForumPostReport;

class ReportPost extends Component
{
    public $postId;
    public $reason_select = '';
    public $reason_custom = '';
    public $showModal = false;

    protected $rules = [
        'reason_select' => 'nullable|string',
        'reason_custom' => 'nullable|string|max:1000',
    ];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'reason_select', 'reason_custom']);
    }

    public function submit()
    {
        $this->validate();

        ForumPostReport::create([
            'post_id' => $this->postId,
            'user_id' => auth()->id(),
            'reason' => $this->reason_custom ?: $this->reason_select,
        ]);

        $this->dispatch('notify', message: 'Đã gửi báo cáo.');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.forum.components.report-post');
    }
}
