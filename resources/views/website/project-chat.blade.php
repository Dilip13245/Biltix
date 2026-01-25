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
                                    <input type="file" id="fileInput" class="d-none" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('fileInput').click()">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    <input type="text" id="messageInput" class="form-control" 
                                           placeholder="{{ __('messages.type_message') }}" maxlength="1000">
                                    <button type="button" class="btn orange_btn" id="sendBtn" onclick="handleSendMessage(event)">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                                <div id="filePreview" class="file-preview mt-2" style="display:none;"></div>
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

.file-preview {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 2px dashed #dee2e6;
}

.file-preview img {
    max-width: 150px;
    max-height: 150px;
    border-radius: 8px;
    object-fit: cover;
}

.message-attachment {
    margin-top: 8px;
}

.message-attachment img {
    max-width: 250px;
    max-height: 250px;
    border-radius: 8px;
    cursor: pointer;
    display: block;
}

.message-attachment video {
    max-width: 300px;
    max-height: 300px;
    border-radius: 8px;
    display: block;
    background: #000;
}

.message-attachment audio {
    width: 100%;
    max-width: 280px;
    height: 54px;
    border-radius: 25px;
}

.audio-wrapper {
    background: #f0f0f0;
    padding: 8px 12px;
    border-radius: 25px;
    display: inline-block;
    min-width: 280px;
}

.chat-message.own .audio-wrapper {
    background: rgba(255,255,255,0.2);
}

.message-attachment a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background: rgba(0,0,0,0.05);
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: background 0.2s;
    font-size: 14px;
}

.message-attachment a i {
    font-size: 20px;
}

.message-attachment a:hover {
    background: rgba(0,0,0,0.1);
}

.chat-message.own .message-attachment a {
    background: rgba(255,255,255,0.2);
    color: white;
}

.chat-message.own .message-attachment a:hover {
    background: rgba(255,255,255,0.3);
}
</style>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
let currentProjectId = null;
let currentUserId = null;
let pusher = null;
let channel = null;
let selectedFile = null;

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
    currentUserId = {{ $user ? $user->id : 'null' }};

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
    const messageInput = document.getElementById('messageInput');
    const fileInput = document.getElementById('fileInput');
    
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSendMessage(e);
            }
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
    }
}

function handleFileSelect(e) {
    const file = e.target.files[0];
    if (!file) return;

    const maxSize = 50 * 1024 * 1024; // 50MB
    if (file.size > maxSize) {
        toastr.error('File size must be less than 50MB');
        e.target.value = '';
        return;
    }

    selectedFile = file;
    showFilePreview(file);
}

function showFilePreview(file) {
    const preview = document.getElementById('filePreview');
    const isImage = file.type.startsWith('image/');

    if (isImage) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div style="position: relative; display: inline-block;">
                    <img src="${e.target.result}" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="clearFileSelection()" style="position: absolute; top: -8px; right: -8px; border-radius: 50%; width: 24px; height: 24px; padding: 0;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <span style="margin-left: 10px;">${file.name}</span>
            `;
            preview.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <i class="fas fa-file fa-2x text-primary"></i>
            <span style="flex: 1;">${file.name}</span>
            <button type="button" class="btn btn-sm btn-danger" onclick="clearFileSelection()">
                <i class="fas fa-times"></i>
            </button>
        `;
        preview.style.display = 'flex';
    }
}

function clearFileSelection() {
    selectedFile = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').style.display = 'none';
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

    let attachmentHTML = '';
    if (message.attachment) {
        const fileName = message.attachment.split('/').pop();
        const fileExt = fileName.split('.').pop().toLowerCase();
        
        // Image
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) {
            attachmentHTML = `
                <div class="message-attachment">
                    <img src="${message.attachment}" alt="Image" onclick="window.open('${message.attachment}', '_blank')" loading="lazy">
                </div>
            `;
        }
        // Video
        else if (['mp4', 'webm', 'ogg', 'mov'].includes(fileExt)) {
            attachmentHTML = `
                <div class="message-attachment">
                    <video controls preload="metadata" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                        <source src="${message.attachment}" type="video/${fileExt === 'mov' ? 'mp4' : fileExt}">
                        Your browser does not support video playback.
                    </video>
                </div>
            `;
        }
        // Audio
        else if (['mp3', 'wav', 'ogg', 'm4a', 'aac'].includes(fileExt)) {
            const audioType = fileExt === 'm4a' || fileExt === 'aac' ? 'audio/mp4' : `audio/${fileExt}`;
            attachmentHTML = `
                <div class="message-attachment">
                    <div class="audio-wrapper">
                        <audio controls preload="metadata">
                            <source src="${message.attachment}" type="${audioType}">
                            Your browser does not support audio playback.
                        </audio>
                    </div>
                </div>
            `;
        }
        // Documents
        else {
            const iconMap = {
                'pdf': 'pdf',
                'doc': 'word', 'docx': 'word',
                'xls': 'excel', 'xlsx': 'excel',
                'ppt': 'powerpoint', 'pptx': 'powerpoint',
                'zip': 'archive', 'rar': 'archive',
                'txt': 'alt'
            };
            const icon = iconMap[fileExt] || 'alt';
            attachmentHTML = `
                <div class="message-attachment">
                    <a href="${message.attachment}" target="_blank" download>
                        <i class="fas fa-file-${icon}"></i>
                        <span>${decodeURIComponent(fileName)}</span>
                    </a>
                </div>
            `;
        }
    }

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
                <div class="message-bubble">
                    ${message.message ? `<div>${escapeHtml(message.message)}</div>` : ''}
                    ${attachmentHTML}
                </div>
                <div class="message-time">${messageTime}</div>
            </div>
        </div>
    `;
}

function getFileIcon(ext) {
    const icons = {
        'PDF': 'pdf',
        'DOC': 'word',
        'DOCX': 'word',
        'XLS': 'excel',
        'XLSX': 'excel'
    };
    return icons[ext] || 'alt';
}

async function handleSendMessage(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const message = messageInput.value.trim();

    if (!message && !selectedFile) {
        toastr.error('Please enter a message or select a file');
        return;
    }

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
        if (message) formData.append('message', message);
        if (selectedFile) formData.append('attachment', selectedFile);

        const response = await api.sendChatMessage(formData);
        
        console.log('Send message response:', response);

        if (response.code === 200) {
            messageInput.value = '';
            clearFileSelection();
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
    // Using Pusher for WebSocket connection with Reverb
    const reverbConfig = {
        key: '{{ config("reverb.apps.apps.0.key") }}',
        host: '{{ config("reverb.apps.apps.0.options.host") }}',
        port: {{ config('reverb.apps.apps.0.options.port') }},
        scheme: '{{ config("reverb.apps.apps.0.options.scheme") }}'
    };
    
    console.log('Reverb Config:', reverbConfig);
    
    pusher = new Pusher(reverbConfig.key, {
        wsHost: reverbConfig.host,
        wsPort: 443,
        wssPort: 443,
        forceTLS: true,
        enabledTransports: ['ws', 'wss'],
        disableStats: true,
        cluster: 'mt1' // Required by Pusher
    });

    console.log('Subscribing to channel: project-chat.' + currentProjectId);
    channel = pusher.subscribe('project-chat.' + currentProjectId);
    
    channel.bind('new-message', function(data) {
        console.log('New message received:', data);
        appendMessage(data);
        scrollToBottom();
    });

    pusher.connection.bind('connected', function() {
        console.log('WebSocket connected successfully');
    });

    pusher.connection.bind('disconnected', function() {
        console.log('WebSocket disconnected');
    });

    pusher.connection.bind('error', function(err) {
        console.error('WebSocket error:', err);
    });

    channel.bind('pusher:subscription_succeeded', function() {
        console.log('Successfully subscribed to project-chat.' + currentProjectId);
    });

    channel.bind('pusher:subscription_error', function(err) {
        console.error('Subscription error:', err);
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
