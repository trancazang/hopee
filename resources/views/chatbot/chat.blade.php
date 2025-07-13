{{-- resources/views/chatbot/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Bot Tư Vấn Tâm Lý</h4>
                    <button id="setupBtn" class="btn btn-sm btn-warning">Thiết lập Database</button>
                </div>
                <div class="card-body">
                    <div id="chatContainer" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                        <div class="message bot-message">
                            <strong>Bot:</strong> Xin chào! Tôi là bot tư vấn tâm lý. Tôi có thể giúp bạn điều gì?
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" id="messageInput" class="form-control" placeholder="Nhập tin nhắn của bạn...">
                        <button id="sendBtn" class="btn btn-primary">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message {
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 5px;
}
.user-message {
    background-color: #e3f2fd;
    text-align: right;
}
.bot-message {
    background-color: #f1f8e9;
}
.loading {
    font-style: italic;
    color: #666;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chatContainer');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const setupBtn = document.getElementById('setupBtn');
    function addMessage(message, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
        messageDiv.innerHTML = `<strong>${isUser ? 'Bạn' : 'Bot'}:</strong> ${message}`;
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    
    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;
        
        addMessage(message, true);
        messageInput.value = '';
        
        // Hiển thị loading
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'message bot-message loading';
        loadingDiv.innerHTML = '<strong>Bot:</strong> Đang suy nghĩ...';
        chatContainer.appendChild(loadingDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Gửi request
        fetch('/chatbot/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            chatContainer.removeChild(loadingDiv);
            
            if (data.status === 'success') {
                addMessage(data.response);
            } else {
                addMessage('Xin lỗi, tôi đang gặp vấn đề kỹ thuật: ' + data.message);
            }
        })
        .catch(error => {
            chatContainer.removeChild(loadingDiv);
            addMessage('Lỗi kết nối. Vui lòng thử lại.');
        });
    }
    
    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    setupBtn.addEventListener('click', function() {
        setupBtn.disabled = true;
        setupBtn.textContent = 'Đang thiết lập...';
        
        fetch('/chatbot/setup', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            setupBtn.disabled = false;
            setupBtn.textContent = 'Thiết lập Database';
        })
        .catch(error => {
            alert('Lỗi khi thiết lập database');
            setupBtn.disabled = false;
            setupBtn.textContent = 'Thiết lập Database';
        });
    });
});
</script>
@endsection