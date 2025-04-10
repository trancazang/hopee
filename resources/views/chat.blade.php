@extends('layouts.app')
@section('content')
<!-- Navbar -->
<nav class="bg-blue-600 text-white shadow-md">
  <div class="container mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Logo / Brand -->
      <a href="{{ url('/') }}" class="text-3xl font-bold flex items-center">
          <span class="mr-2">🧠</span>
          <span>SheZen</span>
      </a>
      <!-- Navigation Links -->
      <div class="flex items-center space-x-6">
          <a href="{{ route('tests.index') }}" class="hover:text-blue-300 transition-colors duration-200">📝 Test</a>
          <a href="{{ route('chat.show') }}" class="hover:text-blue-300 transition-colors duration-200">📩 Tin nhắn</a>

          <a href="{{ route('forum.category.index') }}" class="hover:text-blue-300 transition-colors duration-200">💬 Forum</a>
          @auth
              @if(in_array(auth()->user()->role, ['admin']))
              <a href="{{ route('dashboard') }}" class="hover:text-blue-300 transition-colors duration-200">📊 Dashboard</a>
              @endif
              <a href="{{ route('profile') }}" class="hover:text-blue-300 transition-colors duration-200">👤 Hồ sơ</a>
              <form action="{{ route('logout') }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="hover:text-blue-300 transition-colors duration-200">Logout</button>
              </form>
          @else
              <a href="{{ route('login') }}" class="hover:text-blue-300 transition-colors duration-200">🔑 Đăng nhập</a>
              <a href="{{ route('register') }}" class="hover:text-blue-300 transition-colors duration-200">🆕 Đăng ký</a>
          @endauth
      </div>
  </div>
</nav>

<div class="flex h-screen bg-gray-100">
  {{-- Sidebar --}}
  <div class="w-1/3 max-w-xs bg-white border-r flex flex-col">
    <div class="p-4 border-b"><h2 class="text-xl font-bold">Conversations</h2></div>
    <div class="p-3 border-b">
      <input id="convSearch" type="text" placeholder="Search..."
             class="w-full border rounded-full px-4 py-2 focus:outline-none">
    </div>
    <div class="flex-1 overflow-y-auto" id="convList">
      @foreach($conversations as $c)
        <a href="{{ route('chat.show', $c['id']) }}"
           class="block p-3 hover:bg-gray-50 border-b">
          <p class="font-medium">{{ $c['title'] }}</p>
          @if($c['lastMessage'])
            <p class="text-sm text-gray-500 truncate">{{ $c['lastMessage'] }}</p>
          @endif
        </a>
      @endforeach
    </div>
    <div class="p-4 border-t">
      <button id="newBtn"
              class="w-full bg-blue-500 text-white py-2 rounded-full hover:bg-blue-600">
        + New Chat
      </button>
    </div>
  </div>

  {{-- New Conversation Modal --}}
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white w-full max-w-md rounded shadow-lg p-6">
      <h3 class="text-lg font-semibold mb-4">New Conversation</h3>
      <input id="userSearch" type="text" placeholder="Search users..."
             class="w-full border rounded-full px-4 py-2 mb-4 focus:outline-none">
      <form id="formNew" method="POST" action="{{ route('chat.store') }}">
        @csrf
        <ul id="userList" class="max-h-60 overflow-y-auto mb-4">
          @foreach($allUsers as $u)
            <li class="flex items-center mb-2">
              <input type="checkbox" name="participants[]" value="{{ $u->id }}"
                     id="u{{ $u->id }}" class="mr-2">
              <label for="u{{ $u->id }}">{{ $u->participantDetails['name'] }}</label>
            </li>
          @endforeach
        </ul>
        <div class="flex justify-end">
          <button type="button" id="cancel"
                  class="px-4 py-2 mr-2 border rounded">Cancel</button>
          <button type="submit"
                  class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Create
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Chat Panel --}}
  <div class="flex-1 flex flex-col">
    @isset($id)
      <div class="flex items-center justify-between p-4 bg-white border-b">
        <h2 class="text-lg font-semibold">Conversation #{{ $id }}</h2>
        <button id="deleteConv" class="text-red-500 hover:text-red-700">
          Delete Conversation
        </button>
      </div>
      <div id="messages" class="flex-1 p-4 overflow-y-auto bg-white space-y-4"></div>
      <form id="sendForm" class="flex p-4 border-t bg-white" enctype="multipart/form-data">
        <input id="msgInput" name="body" type="text" placeholder="Type a message..."
               class="flex-1 border rounded-full px-4 py-2 focus:outline-none">
        <input type="file" name="file" id="fileInput" class="hidden" accept="image/*,.pdf,.docx,.zip" />
        <label for="fileInput" class="ml-2 cursor-pointer px-3 py-2 border rounded-full bg-gray-100 hover:bg-gray-200">📎</label>
        <button class="ml-2 bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600">
          Send
        </button>
      </form>
      
    @else
      <div class="flex-1 flex items-center justify-center text-gray-500">
        Select or create a conversation.
      </div>
    @endisset
  </div>
</div>

{{-- Scripts --}}
<script>
  // Sidebar search
  document.getElementById('convSearch').addEventListener('input', function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll('#convList a').forEach(a=>{
      a.style.display = a.textContent.toLowerCase().includes(q)?'':'none';
    });
  });

  // Modal toggle
  const modal = document.getElementById('modal');
  document.getElementById('newBtn').onclick = ()=> modal.classList.remove('hidden');
  document.getElementById('cancel').onclick = ()=> modal.classList.add('hidden');

  // User search in modal
  document.getElementById('userSearch').addEventListener('input', function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll('#userList li').forEach(li=>{
      li.style.display = li.textContent.toLowerCase().includes(q)?'':'none';
    });
  });

  @isset($id)
  document.addEventListener('DOMContentLoaded',()=>{
    const convId = {{ $id }};
    const token  = document.querySelector('meta[name="csrf-token"]').content;
    const msgs   = document.getElementById('messages');
    const myName = '{{ auth()->user()->participantDetails["name"] }}';

    async function load(){
      const res = await fetch(`/chat/${convId}/messages`);
      const arr = await res.json();
      msgs.innerHTML = arr.map(m => {
      let content = '';

      if (m.type === 'image') {
        content = `<img src="${m.body}" class="max-w-xs rounded shadow border">`;
      } else if (m.type === 'attachment') {
        content = `<a href="${m.body}" target="_blank" class="text-blue-600 underline">📎 ${m.data?.file_name || 'Download'}</a>`;
      } else {
        content = `<strong>${m.sender}</strong>: ${m.body}`;
      }

      return `
        <div class="message-item relative mb-4 flex ${m.sender === myName ? 'justify-end' : 'justify-start'} group">
          <div class="px-4 py-2 rounded-xl break-words
                      ${m.sender === myName ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900'}">
            ${content}
          </div>
          <button class="ellipsis absolute top-1 ${m.sender === myName ? 'right-0 mr-2' : 'left-0 ml-2'}
                          opacity-0 group-hover:opacity-100 transition-opacity"
                  onclick="toggleMenu(event)">⋯</button>
          <div class="options-menu hidden absolute top-6 ${m.sender === myName ? 'right-0' : 'left-0'}
                      mt-2 w-32 bg-white border rounded-lg shadow-lg z-10">
            <button class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                    onclick="copyMessage(\`${m.body}\`)">Copy</button>
            <button class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                    onclick="deleteMessage(${m.id})">Delete</button>
          </div>
          <div class="text-xs text-gray-500 mt-1 ml-2">${m.time}</div>
        </div>
      `;
    }).join('');


      msgs.scrollTop = msgs.scrollHeight;
    }

    document.getElementById('sendForm').addEventListener('submit', async e => {
    e.preventDefault();

  const form = e.target;
  const input = form.querySelector('#msgInput');
  const file = form.querySelector('#fileInput').files[0];
  const token = document.querySelector('meta[name="csrf-token"]').content;

  const formData = new FormData();
  formData.append('body', input.value);
  if (file) formData.append('file', file);

  await fetch(`/chat/${convId}/message`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': token },
    body: formData
  });

  input.value = '';
  form.querySelector('#fileInput').value = '';
  load(); // reload messages
});



    document.getElementById('deleteConv').addEventListener('click',async ()=>{
      if(!confirm('Delete this conversation?')) return;
      await fetch(`/chat/${convId}`, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':token}
      });
      window.location.href = '{{ route("chat.show") }}';
    });

    window.toggleMenu = function(event){
      event.stopPropagation();
      const btn = event.currentTarget;
      const menu = btn.nextElementSibling;
      document.querySelectorAll('.options-menu').forEach(m=>m.classList.add('hidden'));
      menu.classList.toggle('hidden');
    };

    window.copyMessage = text => navigator.clipboard.writeText(text);
    window.deleteMessage = async msgId => {
      if(!confirm('Delete this message?')) return;
      await fetch(`/chat/${convId}/message/${msgId}`, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':token}
      });
      load();
    };

    document.addEventListener('click',()=>{
      document.querySelectorAll('.options-menu').forEach(m=>m.classList.add('hidden'));
    });

    load();
    setInterval(load,2000);
  });
  @endisset
</script>
@endsection
