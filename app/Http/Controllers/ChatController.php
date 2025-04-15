<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Musonza\Chat\Facades\ChatFacade as Chat;
use Musonza\Chat\Models\Message;      
class ChatController extends Controller
    {
        use AuthorizesRequests;
    
        /* ───────── Danh sách conversation ───────── */
        public function index(int $id = null)
        {
            $me        = auth()->user();
            $allUsers  = User::where('id', '!=', $me->id)->get();
        
            // Lấy danh sách participation
            $parts = Chat::conversations()->setParticipant($me)->get();
        
            $conversations = $parts->map(function ($p) use ($me) {
                $conv   = $p->conversation;
                $participants = $conv->getParticipants();
                $others = $participants->filter(fn($pt) => $pt->id !== $me->id);
        
                $title = $others->pluck('participantDetails.name')->implode(', ') ?: 'Conversation #' . $conv->id;
        
                $unread = Chat::conversation($conv)->setParticipant($me)->unreadCount();
        
                $lastMsg = Chat::conversation($conv)->setParticipant($me)->getMessages()->last();
                $last    = $lastMsg?->body;
        
                $avatar = $others->first()?->participantDetails['avatar'] ?? '/default-avatar.png';
        
                return [
                    'id'          => $conv->id,
                    'title'       => $title,
                    'lastMessage' => $last,
                    'avatar'      => $avatar,
                    'unread'      => $unread,
                ];
            });
        
            return view('chat', [
                'allUsers'      => $allUsers,
                'conversations' => $conversations,
                'id'            => $id,
            ]);
        }
    
        /* ───────── Tạo conversation ───────── */
        public function store(Request $r)
        {
            $r->validate(['participants'=>'required|array|min:1']);
    
            $users = User::whereIn('id',$r->participants)->get()->push(auth()->user());
            $conv  = Chat::createConversation($users->all());
    
            return redirect()->route('chat.show', $conv->id);
        }
    
        /* ───────── Lấy message ───────── */
        public function messages(int $id): JsonResponse
        {
            $me   = auth()->user();
            $conv = Chat::conversations()->getById($id);
            abort_unless($conv,404);
    
            $msgs = Chat::conversation($conv)->setParticipant($me)->getMessages();
    
            return response()->json(
                $msgs->map(fn($m)=>[
                    'id'        => $m->id,
                    'body'      => $m->body,
                    'sender'    => $m->sender->participantDetails['name'] ?? 'Unknown',
                    'sender_id' => $m->sender->id,
                    'time'      => $m->created_at->format('H:i'),
                    'type'      => $m->type,
                    'data'      => $m->data,
                    'flagged'   => $m->flagged($me),
                ])
            );
        }
    
        /* ───────── Gửi message ───────── */
        public function message(Request $r, int $id): JsonResponse
        {
            $r->validate(['body'=>'nullable|string','file'=>'nullable|file|max:10240']);
    
            $conv = Chat::conversations()->getById($id);
            abort_unless($conv,404);
    
            $body = $r->body;   $type='text';   $data=[];
            if($r->hasFile('file')){
                $file = $r->file('file');
                $path = $file->store('attachments','public');
                $url  = asset('storage/'.$path);
    
                $type = str_starts_with($file->getMimeType(),'image') ? 'image':'attachment';
                $body = $url;
                $data = ['file_name'=>$file->getClientOriginalName(),
                         'file_url' =>$url,
                         'mime_type'=>$file->getMimeType()];
            }
    
            Chat::message($body)->type($type)->data($data)
                ->from(auth()->user())->to($conv)->send();
    
            return response()->json(['status'=>'sent']);
        }
    
        /* ───────── Xoá 1 message (cho tôi) ───────── */
        public function destroyMessage(int $convId,int $msgId): JsonResponse
        {
            $conv = Chat::conversations()->getById($convId);
            $msg  = Chat::messages()->getById($msgId);
            abort_unless($conv && $msg,404);
    
            Chat::message($msg)->setParticipant(auth()->user())->delete();
            return response()->json(['ok'=>true]);
        }
    
        /* ───────── Clear conversation ───────── */
        public function destroyConversation(int $convId): JsonResponse
        {
            $conv = Chat::conversations()->getById($convId);
            abort_unless($conv,404);
    
            Chat::conversation($conv)->setParticipant(auth()->user())->clear();
            return response()->json(['ok'=>true]);
        }
    
        public function participants($conversationId)
    {
        $conversation = Chat::conversations()->getById($conversationId);
        $participants = $conversation->getParticipants();

        $map = [];
        foreach ($participants as $p) {
            $map[$p->id] = $p->participantDetails['name'];
        }
        return response()->json($map);
    }

    public function addMember(Request $request, $conversationId)
    {
        $conversation = Chat::conversations()->getById($conversationId);
        $user = User::findOrFail($request->user_id);

        Chat::conversation($conversation)->addParticipants([$user]);

        return response()->json(['status' => 'added']);
    }

    public function removeMember($conversationId, $userId)
    {
        \Log::info('⚡️ Vào controller removeMember');

        $conversation = Chat::conversations()->getById($conversationId);
        abort_unless($conversation, 404);

        $currentUser = auth()->user();

        $isParticipant = $conversation->getParticipants()->contains(function ($p) use ($currentUser) {
            return $p->id === $currentUser->id && get_class($p) === get_class($currentUser);
        });

        if (!$isParticipant) {
            return response()->json(['error' => 'Bạn không có quyền xoá người khác khỏi cuộc trò chuyện này'], 403);
        }

        $user = User::findOrFail($userId);
        Chat::conversation($conversation)->removeParticipants([$user]);

        return response()->json(['status' => 'removed']);
    }

    

    
        /* ───────── Mark‑read ───────── */
        public function markRead(int $conversation): JsonResponse
        {
            $conv = Chat::conversations()->getById($conversation);
            abort_unless($conv,404);
    
            Chat::conversation($conv)->setParticipant(auth()->user())->readAll();
            return response()->json(['ok'=>true]);
        }
    
        /* ───────── Flag / un‑flag ───────── */
        public function toggleFlag(Message $message): JsonResponse
        {
            $user = auth()->user();
            Chat::message($message)->setParticipant($user)->toggleFlag();
            $message->refresh();
    
            return response()->json([
                'id'      =>$message->id,
                'flagged' =>$message->flagged($user),
            ]);
        }
    

  
}
