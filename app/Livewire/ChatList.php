<?php

namespace App\Livewire;

use Livewire\Component;

use Musonza\Chat\Facades\ChatFacade as Chat;

class ChatList extends Component
{    public function render()
    {
        $user = auth()->user();

        // Lấy Paginator
        $paginated = Chat::conversations()
            ->setParticipant($user)
            ->setPaginationParams([
                'page'    => request()->get('page', 1),
                'perPage' => 10,
                'sorting' => 'desc',
            ])
            ->get();

        // Chỉ map xuống array thuần
        $conversations = collect($paginated->items())->map(function ($c) {
            return [
                'id'    => $c->id,
                'title' => $c->data['title'] ?? 'Cuộc trò chuyện #' . $c->id,
            ];
        })->toArray();

        return view('livewire.chat-list', [
            'conversations' => $conversations,
        ]);
    }
}
