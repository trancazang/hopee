<?php

namespace App\Livewire;

use Livewire\Component;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Illuminate\Support\Facades\Auth;
class ChatInput extends Component
{     public $conversationId;
    public $messageText = '';

    public function mount($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function sendMessage()
    {
        $this->validate(['messageText' => 'required|string']);

        $user = auth()->user();
        $conversation = Chat::conversations()
            ->getById($this->conversationId);

        if (! $conversation) {
            session()->flash('error', 'Cuộc trò chuyện không tồn tại.');
            return;
        }

        Chat::message($this->messageText)
            ->from($user)
            ->to($conversation)
            ->send();

        $this->messageText = '';
        $this->emit('messageSent');
    }

    public function render()
    {
        return view('livewire.chat-input');
    }
}
