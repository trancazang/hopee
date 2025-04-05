<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    
use Musonza\Chat\Facades\ChatFacade as Chat;
use App\Models\User;
class ChatController extends Controller
{
    public function index($id = null)
    {
        // Danh sách user để chọn participants
        $allUsers = User::where('id', '!=', auth()->id())->get();
        // List conversation: chỉ map id+title
        $paginated = Chat::conversations()
            ->setParticipant(auth()->user())
            ->setPaginationParams(['perPage'=>10])
            ->get();
        $conversations = collect($paginated->items())->map(fn($c)=>[
            'id'=>$c->id,
            'title'=>$c->data['title'] ?? "Conv #{$c->id}"
        ]);
        return view('chat', compact('allUsers','conversations','id'));
    }

    // Xử lý tạo conversation
    public function storeConversation(Request $req)
    {
        $req->validate(['participants'=>'required|array|min:1']);
        // Lấy models
        $others = User::whereIn('id',$req->participants)->get();
        $me = User::find(auth()->id());
        $arr = $others->push($me)->all();
        $conv = Chat::createConversation($arr);
        return redirect()->route('chat.show',$conv->id);
    }

    // Trả về JSON lịch sử tin nhắn
    public function getMessages($id)
    {
        $conv = Chat::conversations()->getById($id);
        abort_unless($conv,404);
        $msgs = Chat::conversation($conv)
                    ->setParticipant(auth()->user())
                    ->getMessages();
        return response()->json($msgs->map(fn($m)=>[
            'id'     => $m->id,
            'body'   => $m->body,
            'sender' => $m->sender->participantDetails['name'] ?? $m->sender->name,
            'time'   => $m->created_at->format('H:i'),
        ]));
    }

    // Xử lý gửi tin nhắn
    public function sendMessage(Request $req,$id)
    {
        $req->validate(['body'=>'required|string']);
        $conv = Chat::conversations()->getById($id);
        abort_unless($conv,404);
        Chat::message($req->body)
            ->from(auth()->user())
            ->to($conv)
            ->send();
        return response()->json(['ok'=>true]);
    }
}
