<?php

namespace App\Livewire;

use Livewire\Component;
use Musonza\Chat\Facades\ChatFacade as Chat;
class ChatHistory extends Component
{   public $conversationId;
    public $messages = [];

    public function mount($conversationId)
    {
        $this->conversationId = $conversationId;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $user = auth()->user();

        // 1) Lấy conversation bất kể participant
        $conversation = Chat::conversations()
            ->getById($this->conversationId);

        if (! $conversation) {
            $this->messages = [];
            return;
        }

        // 2) Sau khi có conversation, load messages cho participant
        $this->messages = Chat::conversation($conversation)
            ->setParticipant($user)
            ->getMessages();
    }

    // Nếu muốn tự động cập nhật, bạn có thể sử dụng polling:
    protected $listeners = ['messageSent' => 'loadMessages'];

    public function render()
    {
        return view('livewire.chat-history');
    }
}
