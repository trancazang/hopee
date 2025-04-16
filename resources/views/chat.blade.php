@extends('layouts.app')

@section('content')
<nav class="bg-blue-600 text-white shadow-md">
    <div class="container mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-3xl font-bold flex items-center">
            <span class="mr-2">🧠</span><span>SheZen</span>
        </a>
        <div class="flex items-center space-x-6">
            <a href="{{ route('tests.index') }}" class="link">📝 Test</a>
            <a href="{{ route('chat.show') }}" class="link">📩 Tin nhắn</a>
            <a href="{{ route('forum.category.index') }}" class="link">💬 Forum</a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="link">📊 Dashboard</a>
                @endif
                <a href="{{ route('profile') }}" class="link">👤 Hồ sơ</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">@csrf
                    <button class="link">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="link">🔑 Đăng nhập</a>
                <a href="{{ route('register') }}" class="link">🆕 Đăng ký</a>
            @endauth
        </div>
    </div>
</nav>

<div class="flex h-[calc(100vh-80px)] bg-gray-100">
    {{-- Sidebar --}}
    <aside class="w-1/3 max-w-xs bg-white border-r flex flex-col">
        <div class="p-4 border-b"><h2 class="text-xl font-bold">Conversations</h2></div>
        <div class="p-3 border-b">
            <input id="convSearch" type="text" placeholder="Search…" class="w-full border rounded-full px-4 py-2 focus:outline-none">
        </div>
        <div id="convList" class="flex-1 overflow-y-auto">
            @foreach($conversations as $c)
                <a href="{{ route('chat.show', $c['id']) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 border-b relative">
                    <div class="flex-1">
                        <p class="font-medium text-sm">{{ $c['title'] }}</p>
                        @if (!empty($c['email']))
                            <p class="text-xs text-gray-400 truncate">{{ $c['email'] }}</p>
                        @endif
                        <p class="text-xs text-gray-500 truncate">{{ $c['lastMessage'] ?? 'Chưa có tin nhắn' }}</p>
                    </div>
                    @if($c['unread'] ?? 0)
                        <span class="absolute right-3 top-3 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                            {{ $c['unread'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
        <div class="p-4 border-t">
            <button id="newBtn" class="btn-primary w-full">+ New Chat</button>
        </div>
    </aside>

    {{-- Chat panel --}}
    <main class="flex-1 flex flex-col">
        @isset($id)
        <div class="flex items-center justify-between p-4 bg-white border-b">
            <h2 class="text-lg font-semibold">Conversation #{{ $id }}</h2>
            <div class="flex items-center space-x-4">
                <button id="btnMembers" class="text-sm text-blue-600">👥 Manage</button>
                <button id="deleteConv" class="text-red-500 hover:text-red-700">Delete</button>
            </div>
        </div>

        <div id="messages" class="flex-1 p-4 overflow-y-auto bg-white space-y-4"></div>

        <form id="sendForm" class="flex p-4 border-t bg-white" enctype="multipart/form-data">
            <input id="msgInput" name="body" type="text" placeholder="Type a message…" class="flex-1 border rounded-full px-4 py-2 focus:outline-none">
            <input type="file" id="fileInput" name="file" class="hidden" accept="image/*,.pdf,.docx,.zip">
            <label for="fileInput" class="ml-2 cursor-pointer px-3 py-2 border rounded-full bg-gray-100 hover:bg-gray-200">📎</label>
            <button class="ml-2 btn-success">Send</button>
        </form>
        @else
        <div class="flex-1 flex items-center justify-center text-gray-500">
            Select or create a conversation.
        </div>
        @endisset
    </main>
</div>

{{-- Modal: New Chat --}}
<div id="modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4 text-center">✨ Tạo cuộc trò chuyện mới</h3>
        <input id="userSearch" type="text" placeholder="Tìm người dùng…" class="w-full border rounded-full px-4 py-2 mb-4 focus:outline-none">
        <form id="formNew" method="POST" action="{{ route('chat.store') }}">
            @csrf
            <ul id="userList" class="max-h-60 overflow-y-auto mb-4 space-y-2">
                @foreach($allUsers as $u)
                    <li class="flex items-center">
                        <input type="checkbox" name="participants[]" value="{{ $u->id }}" id="u{{ $u->id }}" class="mr-2">
                        <label for="u{{ $u->id }}">{{ $u->participantDetails['name'] }}</label>
                    </li>
                @endforeach
            </ul>
            <div class="flex justify-center gap-4">
                <button type="button" id="cancel" class="border rounded px-4 py-2">Huỷ</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-full">Tạo</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Manage Members --}}
<div id="memberModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-6">
        <h3 class="font-semibold mb-4">👥 Members</h3>
        <h4 class="font-medium text-sm mb-2">Current</h4>
        <ul id="memberList" class="space-y-1 mb-4"></ul>
        <h4 class="font-medium text-sm mb-2">Add user</h4>
        <input id="addSearch" type="text" placeholder="Search..." class="w-full border rounded px-2 py-1 mb-2">
        <ul id="addList" class="h-32 overflow-y-auto space-y-1"></ul>
        <div class="text-right mt-4">
            <button id="closeMembers" class="btn-secondary">Close</button>
        </div>
    </div>
</div>
@endsection

{{-- Styles --}}
<style>
    .link { @apply hover:text-blue-300 transition-colors duration-200; }
    .btn-primary { @apply bg-blue-500 text-white py-2 rounded-full hover:bg-blue-600; }
    .btn-success { @apply bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600; }
</style>

{{-- Scripts --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const get = sel => document.querySelector(sel);
    const post = (u, d = {}) => fetch(u, { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' }, body: JSON.stringify(d) });
    const del = u => fetch(u, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } });

    const modal = get('#modal');
    get('#newBtn')?.addEventListener('click', () => modal.classList.remove('hidden'));
    get('#cancel')?.addEventListener('click', () => modal.classList.add('hidden'));

    get('#userSearch')?.addEventListener('input', e => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#userList li').forEach(li => {
            li.style.display = li.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    get('#convSearch')?.addEventListener('input', e => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#convList a').forEach(a => {
            a.style.display = a.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    @isset($id)
    const convId = {{ $id }};
    const msgs = get('#messages');
    const myName = @json(auth()->user()->participantDetails['name']);
    const userMap = @json($allUsers->pluck('participantDetails.name','id'));

    async function load() {
        const res = await fetch(`/chat/${convId}/messages`);
        const arr = await res.json();

        msgs.innerHTML = arr.map(m => {
            let content = '';
            if (m.type === 'image') {
                content = `<img src="${m.body}" class="max-w-xs rounded shadow border">`;
            } else if (m.type === 'attachment') {
                const n = m.data?.file_name || 'Download';
                content = `<a href="${m.body}" target="_blank" class="text-blue-600 underline">📎 ${n}</a>`;
            } else {
                content = `<strong>${m.sender}</strong>: ${m.body}`;
            }

            return `<div class="relative mb-4 flex ${m.sender === myName ? 'justify-end' : 'justify-start'} group">
                <div class="px-4 py-2 rounded-xl break-words ${m.sender === myName ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900'}">${content}</div>
                <div class="text-xs text-gray-500 mt-1 ml-2">${m.time}</div>
            </div>`;
        }).join('');

        msgs.scrollTop = msgs.scrollHeight;
        await post(`/chat/${convId}/read`);
    }

    get('#sendForm')?.addEventListener('submit', async e => {
        e.preventDefault();
        const form = e.target;
        const input = form.querySelector('#msgInput');
        const file = form.querySelector('#fileInput').files[0];
        const fd = new FormData();
        fd.append('body', input.value);
        if (file) fd.append('file', file);
        await fetch(`/chat/${convId}/message`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: fd });
        input.value = '';
        form.querySelector('#fileInput').value = '';
        load();
    });

    get('#deleteConv')?.addEventListener('click', () => {
        if (confirm('Delete conversation?')) {
            del(`/chat/${convId}`).then(() => location.href = '{{ route('chat.show') }}');
        }
    });

    // ===== Manage Members =====
    const mModal = get('#memberModal');
    const btnMem = get('#btnMembers');
    const closeMem = get('#closeMembers');
    const memList = get('#memberList');
    const addList = get('#addList');
    const addSearch = get('#addSearch');

    btnMem?.addEventListener('click', () => { mModal?.classList.remove('hidden'); renderMembers(); });
    closeMem?.addEventListener('click', () => { mModal?.classList.add('hidden'); });

    async function renderMembers() {
        const res = await fetch(`/chat/${convId}/participants`);
        const map = await res.json();
        const ids = Object.keys(map).map(Number);

        memList.innerHTML = ids.map(id => `
            <li class="flex justify-between text-sm">
                <span>${map[id]}</span>
                <button onclick="kick(${id})" class="text-red-500 text-xs">remove</button>
            </li>`).join('');

        addList.innerHTML = Object.entries(userMap)
            .filter(([uid]) => !ids.includes(Number(uid)))
            .map(([uid, name]) => `
                <li class="flex justify-between text-sm">
                    <span>${name}</span>
                    <button onclick="add(${uid})" class="text-blue-500 text-xs">add</button>
                </li>`).join('');
    }

    window.kick = async uid => { await del(`/chat/${convId}/member/${uid}`); renderMembers(); load(); };
    window.add  = async uid => { await post(`/chat/${convId}/member`, { user_id: uid }); renderMembers(); load(); };

    addSearch?.addEventListener('input', e => {
        const q = e.target.value.toLowerCase();
        addList.querySelectorAll('li').forEach(li => {
            li.style.display = li.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    load();
    setInterval(load, 2000);
    @endisset
});
</script>
@endpush
