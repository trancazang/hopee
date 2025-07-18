@extends('layouts.app')

@section('content')
<div class="fixed bottom-6 right-6 z-50">
    {{-- Nút bật / tắt khung chat --}}
    <button id="toggleChat"
            class="bg-gradient-to-r from-teal-500 to-emerald-500 text-white p-3 rounded-full shadow-lg focus:outline-none">
        <i class="fas fa-comments"></i>
    </button>

    {{-- Khung chat --}}
    <div id="chatWrapper"
         class="hidden w-80 sm:w-96 bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between bg-gradient-to-r from-emerald-600 to-teal-500 px-4 py-3 text-white">
            <h5 class="font-semibold flex items-center gap-2">
                <i class="fas fa-robot text-xl"></i> Tư Vấn Tâm Lý
            </h5>
            @if(optional(auth()->user())->role && in_array(auth()->user()->role, ['moderator','admin']))
            <button id="setupBtn"
                    class="text-white/80 hover:text-white text-lg flex items-center gap-1"
                    title="Thiết lập database">
                <i class="fas fa-database"></i> <span>Thiết lập DB</span>
            </button>
        @endif

            <button id="collapseChat" class="text-white/70 hover:text-white text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Nội dung chat --}}
        <div id="chatContainer"
             class="flex-1 px-4 py-3 space-y-3 overflow-y-auto bg-gray-50" style="max-height: 320px">
            {{-- Lời chào khởi đầu --}}
            <div class="flex items-start gap-2">
                <i class="fas fa-robot text-emerald-600 mt-1"></i>
                <div class="bg-emerald-100 text-gray-800 rounded-lg px-3 py-2 text-sm leading-relaxed">
                    <h6 class="font-semibold mb-1">👋 Xin chào!</h6>
                    <p>Tôi là <strong>Bot Tư Vấn Tâm Lý</strong>.<br>
                       Bạn đang lo lắng điều gì? Hãy chia sẻ nhé!</p>
                </div>
            </div>
        </div>

        {{-- Ô nhập --}}
        <div class="border-t bg-white p-3">
            <div class="flex gap-2">
                <input id="messageInput"
                       class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400"
                       placeholder="Nhập tin nhắn..." autocomplete="off">
                <button id="sendBtn"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white rounded-full w-10 h-10 flex items-center justify-center">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    {{-- Font Awesome (CDN) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chatWrapper   = document.getElementById('chatWrapper');
    const toggleBtn     = document.getElementById('toggleChat');
    const collapseBtn   = document.getElementById('collapseChat');
    const chatContainer = document.getElementById('chatContainer');
    const messageInput  = document.getElementById('messageInput');
    const sendBtn       = document.getElementById('sendBtn');
    const setupBtn      = document.getElementById('setupBtn');

    // 👉 Gọi lại lịch sử chat ngay khi trang load
    loadChatHistory();

    // Ẩn / hiện khung chat
    toggleBtn.addEventListener('click', () => {
        chatWrapper.classList.toggle('hidden');
    });

    collapseBtn.addEventListener('click', () => chatWrapper.classList.add('hidden'));

    function addMessage(html, isUser = false) {
        const wrapper = document.createElement('div');
        wrapper.className = `flex ${isUser ? 'justify-end' : 'items-start gap-2'}`;

        const bubble = document.createElement('div');
        bubble.className = `${isUser
            ? 'bg-teal-500 text-white'
            : 'bg-emerald-100 text-gray-800'} rounded-lg px-3 py-2 text-sm leading-relaxed max-w-[75%]`;

        bubble.innerHTML = html;
        if (!isUser) {
            wrapper.insertAdjacentHTML('afterbegin', '<i class="fas fa-robot text-emerald-600 mt-1"></i>');
        }
        wrapper.appendChild(bubble);
        chatContainer.appendChild(wrapper);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function sendMessage() {
        const text = messageInput.value.trim();
        if (!text) return;

        addMessage(`<p>${text}</p>`, true);
        messageInput.value = '';

        const loader = document.createElement('div');
        loader.className = 'flex items-start gap-2 loader';
        loader.innerHTML =
            '<i class="fas fa-robot text-emerald-600 mt-1"></i>' +
            '<div class="bg-emerald-50 text-emerald-700 rounded-lg px-3 py-2 text-sm italic">Đang trả lời…</div>';
        chatContainer.appendChild(loader);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        fetch('/chatbot/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include',
            body: JSON.stringify({ message: text })
        })
        .then(r => r.json())
        .then(d => {
            loader.remove();
            if (d.status === 'success') {
                addMessage(d.response);
            } else {
                addMessage(`<p>⚠️ Xin lỗi, tôi đang gặp lỗi kỹ thuật:<br>${d.message}</p>`);
            }
        })
        .catch(() => {
            loader.remove();
            addMessage('<p>⚠️ Lỗi kết nối, vui lòng thử lại.</p>');
        });
    }

    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', e => e.key === 'Enter' && sendMessage());

    if (setupBtn) {
        setupBtn.addEventListener('click', function () {
            setupBtn.disabled = true;
            setupBtn.textContent = 'Đang thiết lập...';

            fetch('/chatbot/setup', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                setupBtn.disabled = false;
                setupBtn.textContent = 'Thiết lập Database';
            })
            .catch(() => {
                alert('Lỗi khi thiết lập database');
                setupBtn.disabled = false;
                setupBtn.textContent = 'Thiết lập Database';
            });
        });
    }

    function loadChatHistory() {
        fetch('/chatbot/chat/history', {
            credentials: 'include'
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success' && Array.isArray(data.history)) {
                chatContainer.innerHTML = '';

                chatContainer.insertAdjacentHTML('beforeend', `
                    <div class="flex items-start gap-2">
                        <i class="fas fa-robot text-emerald-600 mt-1"></i>
                        <div class="bg-emerald-100 text-gray-800 rounded-lg px-3 py-2 text-sm leading-relaxed">
                            <h6 class="font-semibold mb-1">👋 Xin chào!</h6>
                            <p>Tôi là <strong>Bot Tư Vấn Tâm Lý</strong>.<br>
                            Bạn đang lo lắng điều gì? Hãy chia sẻ nhé!</p>
                        </div>
                    </div>
                `);

                data.history.forEach(item => {
                    addMessage(`<p>${item.message}</p>`, item.role === 'user');
                });
            }
        })
        .catch(err => {
            console.error('❌ Lỗi khi load lịch sử chat:', err);
        });
    }
});

</script>
@endpush
@endsection
