@extends('website.layout.app')

@section('title', __('messages.chat'))

@section('content')
<div class="content-header border-0 shadow-none mb-4">
    <h2>{{ __('messages.project_chat') }}</h2>
    <p>{{ __('messages.team_group_chat') }}</p>
</div>

<section class="px-md-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card B_shadow">
                    <div class="card-body p-0">
                        <div id="chatContainer" class="chat-container">
                            <div id="chatMessages" class="chat-messages"></div>
                            <div class="chat-input-wrapper">
                                <form id="chatForm" class="d-flex gap-2 align-items-center" onsubmit="return false;">
                                    <input type="text" id="messageInput" class="form-control" 
                                           placeholder="{{ __('messages.type_message') }}" maxlength="1000" required>
                                    <button type="button" class="btn orange_btn" id="sendBtn" onclick="handleSendMessage(event)">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.chat-container {
    height: calc(100vh - 300px);
    min-height: 500px;
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.chat-message {
    display: flex;
    margin-bottom: 15px;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-message.own {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin: 0 10px;
    flex-shrink: 0;
}

.message-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.message-avatar .avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #4477C4;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.message-content {
    max-width: 60%;
}

.message-header {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 4px;
}

.message-bubble {
    padding: 10px 15px;
    border-radius: 15px;
    word-wrap: break-word;
}

.chat-message:not(.own) .message-bubble {
    background: white;
    border: 1px solid #e0e0e0;
}

.chat-message.own .message-bubble {
    background: #4477C4;
    color: white;
}

.message-time {
    font-size: 11px;
    color: #999;
    margin-top: 4px;
}

.chat-message.own .message-time {
    text-align: right;
}

.chat-input-wrapper {
    padding: 15px;
    background: white;
    border-top: 1px solid #e0e0e0;
}

#messageInput {
    border-radius: 25px;
    padding: 10px 20px;
}

#sendBtn {
    border-radius: 50%;
    width: 45px;
    height: 45px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-messages {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.no-messages {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.no-messages i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}
</style>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
let currentProjectId = null;
let currentUserId = null;
let pusher = null;
let channel = null;

function getProjectIdFromUrl() {
    const pathParts = window.location.pathname.split('/');
    const projectIndex = pathParts.indexOf('project');
    return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : null;
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Chat page loaded');
    console.log('API available:', typeof api !== 'undefined');
    console.log('API methods:', api ? Object.keys(api).filter(k => k.includes('Chat')) : 'API not loaded');
    
    currentProjectId = getProjectIdFromUrl();
    currentUserId = {{ auth()->check() ? auth()->user()->id : 'null' }};

    console.log('Project ID:', currentProjectId);
    console.log('User ID:', currentUserId);

    if (!currentProjectId || !currentUserId) {
        console.error('Project ID or User ID not found');
        document.getElementById('chatMessages').innerHTML = '<div class="no-messages"><i class="fas fa-exclamation-circle"></i><p>Please login to use chat</p></div>';
        return;
    }

    initializeChat();
    loadMessages();
    setupWebSocket();
});

function initializeChat() {
    // Remove form submit listener since we're using onclick now
    const messageInput = document.getElementById('messageInput');
    
    // Allow Enter key to send message
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSendMessage(e);
            }
        });
    }
}

async function loadMessages() {
    const messagesContainer = document.getElementById('chatMessages');
    messagesContainer.innerHTML = '<div class="loading-messages"><i class="fas fa-spinner fa-spin"></i><p>Loading messages...</p></div>';

    try {
        const response = await api.getChatMessages({
            user_id: currentUserId,
            project_id: currentProjectId,
            limit: 50,
            page: 1
        });

        console.log('Chat messages response:', response);

        if (response.code === 200 && response.data) {
            // Handle both paginated and non-paginated responses
            const messages = response.data.data ? response.data.data.reverse() : (Array.isArray(response.data) ? response.data.reverse() : []);
            renderMessages(messages);
            scrollToBottom();
        } else {
            messagesContainer.innerHTML = '<div class="no-messages"><i class="fas fa-comments"></i><p>No messages yet. Start the conversation!</p></div>';
        }
    } catch (error) {
        console.error('Error loading messages:', error);
        messagesContainer.innerHTML = '<div class="no-messages"><i class="fas fa-exclamation-circle"></i><p>Failed to load messages</p></div>';
    }
}

function renderMessages(messages) {
    const messagesContainer = document.getElementById('chatMessages');
    
    if (!messages || messages.length === 0) {
        messagesContainer.innerHTML = '<div class="no-messages"><i class="fas fa-comments"></i><p>No messages yet. Start the conversation!</p></div>';
        return;
    }

    messagesContainer.innerHTML = messages.map(msg => createMessageHTML(msg)).join('');
}

function createMessageHTML(message) {
    const isOwn = message.user_id == currentUserId;
    const userName = message.user?.name || 'Unknown';
    const userInitial = userName.charAt(0).toUpperCase();
    const profileImage = message.user?.profile_image;
    const messageTime = new Date(message.created_at).toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });

    return `
        <div class="chat-message ${isOwn ? 'own' : ''}">
            <div class="message-avatar">
                ${profileImage 
                    ? `<img src="${profileImage}" alt="${userName}">` 
                    : `<div class="avatar-placeholder">${userInitial}</div>`
                }
            </div>
            <div class="message-content">
                ${!isOwn ? `<div class="message-header">${userName}</div>` : ''}
                <div class="message-bubble">${escapeHtml(message.message)}</div>
                <div class="message-time">${messageTime}</div>
            </div>
        </div>
    `;
}

async function handleSendMessage(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const message = messageInput.value.trim();

    if (!message) return;

    // Check if API is available
    if (typeof api === 'undefined' || !api.sendChatMessage) {
        console.error('API not available');
        toastr.error('Chat API not loaded. Please refresh the page.');
        return;
    }

    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
        const formData = new FormData();
        formData.append('user_id', currentUserId);
        formData.append('project_id', currentProjectId);
        formData.append('message', message);

        const response = await api.sendChatMessage(formData);
        
        console.log('Send message response:', response);

        if (response.code === 200) {
            messageInput.value = '';
            appendMessage(response.data);
            scrollToBottom();
            toastr.success('Message sent');
        } else {
            toastr.error(response.message || 'Failed to send message');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        toastr.error('Failed to send message');
    } finally {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    }
}

function appendMessage(message) {
    const messagesContainer = document.getElementById('chatMessages');
    const noMessages = messagesContainer.querySelector('.no-messages');
    
    if (noMessages) {
        messagesContainer.innerHTML = '';
    }

    messagesContainer.insertAdjacentHTML('beforeend', createMessageHTML(message));
}

function setupWebSocket() {
    // Using Pusher for WebSocket connection
    pusher = new Pusher('{{ env("PUSHER_APP_KEY", "biltix-key") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER", "mt1") }}',
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
        enabledTransports: ['ws', 'wss']
    });

    channel = pusher.subscribe('project-chat.' + currentProjectId);
    
    channel.bind('new-message', function(data) {
        if (data.user_id != currentUserId) {
            appendMessage(data);
            scrollToBottom();
        }
    });

    pusher.connection.bind('connected', function() {
        console.log('WebSocket connected');
    });

    pusher.connection.bind('error', function(err) {
        console.error('WebSocket error:', err);
    });
}

function scrollToBottom() {
    const messagesContainer = document.getElementById('chatMessages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (channel) {
        channel.unbind_all();
        channel.unsubscribe();
    }
    if (pusher) {
        pusher.disconnect();
    }
});
</script>
@endsection
