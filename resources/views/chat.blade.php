@extends('layouts.app')
@section('content')
<head>  
    <meta name="csrf-token" content="{{ csrf_token() }}">
@livewireStyles
</head>
<div class="container mx-auto my-4">

  {{-- 1. Tạo cuộc trò chuyện --}}
  <div class="mb-6 p-4 border rounded bg-white">
    <h2 class="font-bold mb-2">Tạo cuộc trò chuyện</h2>
    <form id="createConv" method="POST" action="{{ route('chat.store') }}">
      @csrf
      <select name="participants[]" multiple class="border p-2 w-full">
        @foreach($allUsers as $u)
          <option value="{{ $u->id }}">{{ $u->name }}</option>
        @endforeach
      </select>
      <button class="mt-2 bg-blue-500 text-white px-4 py-2">Tạo</button>
    </form>
  </div>

  {{-- 2. Danh sách conversation --}}
  <div class="mb-6 p-4 border rounded bg-white">
    <h2 class="font-bold mb-2">Danh sách cuộc trò chuyện</h2>
    <ul>
      @foreach($conversations as $c)
        <li class="mb-1">
          <a href="{{ route('chat.show',$c['id']) }}"
             class="text-blue-600 hover:underline">
            {{ $c['title'] }}
          </a>
        </li>
      @endforeach
    </ul>
  </div>

  {{-- 3. Chat panel --}}
  @isset($id)
  <div class="p-4 border rounded bg-white flex flex-col h-96">
    <h2 class="font-bold mb-2">Chat #{{ $id }}</h2>
    <div id="messages" class="flex-1 overflow-y-auto mb-2"></div>
    <form id="sendMsg" class="flex">
      <input type="text" id="msgBody" class="flex-1 border p-2" placeholder="Nhập tin nhắn…">
      <button class="bg-green-500 text-white px-4">Gửi</button>
    </form>
  </div>
  @endisset

</div>
@if(isset($id))
<script>
document.addEventListener('DOMContentLoaded', () => {
  const convId = {{ $id }};
  const token  = document.querySelector('meta[name="csrf-token"]').content;
  const msgDiv = document.getElementById('messages');

  console.log('Chat script initialized for conversation', convId);

  async function loadMessages() {
    try {
      const res = await fetch(`/chat/${convId}/messages`);
      if (!res.ok) throw new Error(res.statusText);
      const arr = await res.json();
      console.log('Fetched messages:', arr);
      msgDiv.innerHTML = arr.map(m => `
        <div class="mb-1 ${m.sender === '{{ auth()->user()->participantDetails["name"] }}' ? 'text-right' : 'text-left'}">
          <div class="inline-block p-2 rounded ${m.sender === '{{ auth()->user()->participantDetails["name"] }}' ? 'bg-green-100' : 'bg-blue-100'}">
            <strong>${m.sender}:</strong> ${m.body}
          </div>
          <div class="text-xs text-gray-500">${m.time}</div>
        </div>
      `).join('');
      msgDiv.scrollTop = msgDiv.scrollHeight;
    } catch (e) {
      console.error('Error loading messages', e);
    }
  }

  document.getElementById('sendMsg').addEventListener('submit', async e => {
    e.preventDefault();
    const input = document.getElementById('msgBody');
    const body  = input.value.trim();
    if (!body) return;
    try {
      const res = await fetch(`/chat/${convId}/message`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ body })
      });
      if (!res.ok) throw new Error(res.statusText);
      input.value = '';
      console.log('Message sent');
      await loadMessages();
    } catch (e) {
      console.error('Error sending message', e);
    }
  });

  // Khởi chạy lần đầu và polling
  loadMessages();
  setInterval(loadMessages, 2000);
});
</script>
@endif
