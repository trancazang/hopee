<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Musonza\Chat\Facades\ChatFacade as Chat;

class ChatController extends Controller
{
    public function index($id = null)
    {
        $me = auth()->user();

        // 1. Lấy allUsers cho modal tạo conversation
        $allUsers = User::where('id', '!=', $me->id)->get();

        // 2. Lấy Collection<Participation> cho user này
        $participations = Chat::conversations()
            ->setParticipant($me)
            ->get(); // Trả về Collection<Participation>

        // 3. Map mỗi Participation thành mảng thuần
        $conversations = $participations->map(function ($participation) use ($me) {
            // 3.1 Lấy Conversation model từ Participation
            $conv = $participation->conversation;

            // 3.2 Lấy tất cả participation pivot rows của conversation
            //    (mỗi phần tử là Participation)
            $parts = $conv->participants; // relation participants trên Conversation

            // 3.3 Lấy tên các participant (messageable) ngoại trừ chính bạn
            $names = $parts
                ->map(fn($p) => optional($p->messageable)->participantDetails['name'])
                ->filter(fn($n) => $n && $n !== $me->participantDetails['name'])
                ->implode(', ');

            // 3.4 Lấy tin nhắn cuối cùng
            $last = Chat::conversation($conv)
                ->setParticipant($me)
                ->getMessages()
                ->last()?->body;

            return [
                'id'          => $conv->id,
                'title'       => $names ?: 'Conversation #' . $conv->id,
                'lastMessage' => $last,
            ];
        })->toArray();

        return view('chat', compact('allUsers', 'conversations', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'participants' => 'required|array|min:1',
        ], [
            'participants.required' => 'Chọn ít nhất một người tham gia.',
            'participants.min'      => 'Chọn ít nhất một người tham gia.',
        ]);

        $me = auth()->user();
        $others = User::whereIn('id', $request->participants)->get();
        $participants = $others->push($me)->all();

        $conv = Chat::createConversation($participants);

        return redirect()->route('chat.show', $conv->id);
    }

    public function messages($id)
    {
        $me = auth()->user();
        $conv = Chat::conversations()->getById($id);
        abort_unless($conv, 404);

        $msgs = Chat::conversation($conv)
            ->setParticipant($me)
            ->getMessages();

            return response()->json(
                $msgs->map(fn($m) => [
                    'id'     => $m->id,
                    'body'   => $m->body,
                    'sender' => $m->sender->participantDetails['name'] ?? 'Unknown',
                    'time'   => $m->created_at->format('H:i'),
                    'type'   => $m->type,
                    'data'   => $m->data,
                ])
        );
    }

    public function message(Request $request, $id)
        {
            $request->validate([
                'body' => 'nullable|string',
                'file' => 'nullable|file|max:10240' // tối đa 10MB
            ]);

            $me = auth()->user();
            $conversation = Chat::conversations()->getById($id);
            abort_unless($conversation, 404);

            $body = $request->body;
            $type = 'text';
            $data = [];

            // Nếu có file thì override body, type và data
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('attachments', 'public');
                $url = asset('storage/' . $path);

                $type = str_starts_with($file->getMimeType(), 'image') ? 'image' : 'attachment';
                $body = $url;

                $data = [
                    'file_name' => $file->getClientOriginalName(),
                    'file_url' => $url,
                    'mime_type' => $file->getMimeType()
                ];
            }

            Chat::message($body)
                ->type($type)
                ->data($data)
                ->from($me)
                ->to($conversation)
                ->send();

            return response()->json(['status' => 'sent']);
        }


    public function destroyMessage(Request $request, $convId, $msgId)
    {
        $me   = auth()->user();
        $conv = Chat::conversations()->getById($convId);
        abort_unless($conv, 404);

        $msg = Chat::messages()->getById($msgId);
        abort_unless($msg, 404);

        // Xóa tin nhắn cho participant
        Chat::message($msg)->setParticipant($me)->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Xóa (clear) conversation cho participant hiện tại
     */
    public function destroyConversation(Request $request, $convId)
    {
        $me   = auth()->user();
        $conv = Chat::conversations()->getById($convId);
        abort_unless($conv, 404);

        // Clear toàn bộ messages cho participant
        Chat::conversation($conv)->setParticipant($me)->clear();

        return response()->json(['ok' => true]);
    }
  
}
