@extends('website.layout.app')

@section('title', 'Group Chat')

@push('styles')
@push('styles')
<style>
    :root {
        --chat-bg: #eefeef; /* Very light green user requested match theme? Or just sticking to clean. User said "match existing website theme". The site has orange/green/blue. Let's keep clean. */
        --chat-header-bg: #ffffff;
        --sent-message-bg: #4477c4;
        --received-message-bg: #ffffff;
        --input-bg: #f0f2f5;
        --header-height: 120px; /* Fixed header height from style.css */
    }

    /* Override Main Layout padding/margins for this page only to be full screen */
    .main-content {
        padding: 0 !important;
        height: calc(100vh - var(--header-height));
        overflow: hidden !important; /* Prevent global scroll */
    }

    .chat-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
        background-color: #f0f2f5;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234477c4' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* Header */
    .chat-header {
        height: 70px; /* Fixed chat header height */
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 0 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .chat-project-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #4477c4 0%, #3b66a8 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 4px 6px -1px rgba(68, 119, 196, 0.3);
    }

    /* Message Area */
    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        scroll-behavior: smooth;
    }

    /* Scrollbar */
    .chat-body::-webkit-scrollbar {
        width: 6px;
    }
    .chat-body::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-body::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.1);
        border-radius: 20px;
    }

    /* Messages */
    .message-container {
        display: flex;
        margin-bottom: 2px;
        position: relative;
        max-width: 65%;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .message-container.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    
    .message-container.received {
        align-self: flex-start;
    }

    .avatar-spacer {
        width: 38px;
        margin-right: 0.75rem;
        display: flex;
        align-items: flex-end;
    }
    .message-container.sent .avatar-spacer {
        margin-right: 0;
        margin-left: 0.75rem;
    }
    
    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 2px solid #fff;
    }

    .message-card {
        padding: 8px 12px;
        border-radius: 18px;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08); /* Subtle shadow */
        min-width: 100px;
    }

    .message-container.sent .message-card {
        background: var(--sent-message-bg);
        color: white;
        border-bottom-right-radius: 4px; /* Tail effect */
    }

    .message-container.received .message-card {
        background: var(--received-message-bg);
        color: #111b21;
        border-bottom-left-radius: 4px; /* Tail effect */
    }

    .sender-name {
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 2px;
        color: #e5932e; /* Use a distinct color for names in group chat */
    }

    .message-content {
        font-size: 0.95rem;
        line-height: 1.4;
        word-wrap: break-word;
    }

    .message-meta {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
        margin-left: 10px;
        float: right;
    }

    .message-time {
        font-size: 0.65rem;
        opacity: 0.7;
    }
    
    .message-status i {
        font-size: 0.7rem;
    }

    /* Attachments */
    .attachment-preview {
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 5px;
        max-width: 250px;
    }
    .attachment-preview img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.2s;
    }
    .attachment-preview img:hover {
        transform: scale(1.02);
    }

    .file-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: rgba(0,0,0,0.05);
        border-radius: 8px;
        text-decoration: none !important;
        transition: background 0.2s;
    }
    .message-container.sent .file-chip {
        background: rgba(255,255,255,0.15);
        color: white;
    }
    .message-container.received .file-chip {
        color: #333;
    }
    .file-chip:hover {
        background: rgba(0,0,0,0.1);
    }
    .message-container.sent .file-chip:hover {
        background: rgba(255,255,255,0.25);
    }
    .file-icon {
        font-size: 1.5rem;
    }

    /* Footer / Input Area */
    .chat-footer {
        background: #f0f2f5;
        padding: 10px 16px;
        display: flex;
        align-items: flex-end;
        gap: 8px;
        position: relative;
        flex-shrink: 0; /* Don't shrink */
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        :root {
            --header-height: 60px; /* Adjust if mobile header is smaller, usually sidebar is hidden */
        }
        
        /* If header is sticky/fixed on mobile, adjust accordingly. 
           Assuming standard responsive behavior where header might be smaller.
        */
        
        .main-content {
             /* On mobile, sometimes viewport height is tricky due to browser bars. dvh is better if supported, else fallback */
             height: calc(100vh - 70px); 
             height: calc(100dvh - 70px);
        }

        .chat-header {
            padding: 0 1rem;
            height: 60px;
        }

        .chat-body {
            padding: 1rem;
        }

        .message-container {
            max-width: 85%; /* Wider bubbles on mobile */
        }

        .action-btn {
            width: 36px;
            height: 36px;
        }
        
        .chat-input {
            font-size: 16px; /* Prevent zoom on iOS */
        }
    }

    .input-wrapper {
        flex: 1;
        background: white;
        border-radius: 24px;
        display: flex;
        align-items: center;
        padding: 5px 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid transparent;
        transition: border 0.2s;
    }
    .input-wrapper:focus-within {
        border-color: #4477c4;
    }

    .chat-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px 5px;
        max-height: 100px;
        overflow-y: auto;
        font-size: 1rem;
        background: transparent;
        resize: none; /* Auto-expanding textarea would be ideal, but keeping simple input for now */
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #54656f;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .action-btn:hover {
        background: rgba(0,0,0,0.05);
        color: #111b21;
    }
    
    .send-btn {
        background: #4477c4;
        color: white;
        box-shadow: 0 2px 5px rgba(68, 119, 196, 0.4);
    }
    .send-btn:hover {
        background: #3661a5;
        color: white;
        transform: scale(1.05);
    }
    .send-btn:disabled {
        background: #9ca3af;
        transform: none;
        box-shadow: none;
    }

    /* Custom Emoji Picker Style */
    emoji-picker {
        width: 100%;
        height: 350px;
        --border-radius: 12px;
        --background: #fff;
        --emoji-size: 1.5rem;
    }
    .emoji-popover {
        position: absolute;
        bottom: 70px;
        left: 20px;
        z-index: 100;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        display: none;
        width: 320px;
        animation: slideUp 0.2s ease;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .emoji-popover.active { display: block; }

    /* File Preview Area */
    #file-preview-area {
        position: absolute;
        bottom: 70px;
        left: 0;
        right: 0;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(5px);
        padding: 10px 20px;
        border-top: 1px solid #e5e7eb;
        display: none;
        align-items: center;
        justify-content: space-between;
        z-index: 5;
        animation: fadeIn 0.2s;
    }
</style>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
@endpush

@section('content')
<div class="chat-wrapper">
    <!-- Header -->
    <div class="chat-header">
        <div class="d-flex align-items-center gap-3">
            <div class="chat-project-icon">
                <i class="fas fa-cubes"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold text-dark">{{ $project->project_title }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border">{{ ucfirst($project->status ?? 'Active') }}</span>
                    <small class="text-muted d-none d-md-inline">Group Chat</small>
                </div>
            </div>
        </div>
        <div>
            <!-- Optional Header Actions -->
             <div class="dropdown">
                <button class="btn btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><h6 class="dropdown-header">Members</h6></li>
                   {{-- List members could go here --}}
                   <li><a class="dropdown-item" href="#"><i class="fas fa-users me-2 text-muted"></i> View Team</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Messages Body -->
    <div class="chat-body" id="messages-list">
        @foreach($messages as $msg)
            @php
                $isMe = $msg->user_id == auth()->id();
            @endphp
            <div class="message-container {{ $isMe ? 'sent' : 'received' }}">
                <div class="avatar-spacer">
                    @if(!$isMe)
                        <img src="{{ $msg->user->profile_image_url ?? asset('website/images/no-user.png') }}" class="message-avatar" title="{{ $msg->user->name }}">
                    @endif
                </div>

                <div class="message-card">
                    @if(!$isMe)
                        <div class="sender-name">{{ $msg->user->name }}</div>
                    @endif

                    <div class="message-content">
                        @if($msg->type == 'image')
                            <div class="attachment-preview">
                                <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank">
                                    <img src="{{ Storage::url($msg->attachment_path) }}" alt="Image">
                                </a>
                            </div>
                        @elseif($msg->type == 'file')
                            <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank" class="file-chip">
                                <i class="fas fa-file-alt file-icon"></i>
                                <div class="text-truncate" style="max-width: 150px;">
                                    {{ basename($msg->attachment_path) }}
                                    <div style="font-size: 0.7em; opacity: 0.7;">Download</div>
                                </div>
                            </a>
                        @endif

                        @if($msg->message)
                            <div>{{ $msg->message }}</div>
                        @endif
                    </div>

                    <div class="message-meta">
                        <span class="message-time">{{ $msg->created_at->format('H:i') }}</span>
                        @if($isMe)
                            <span class="message-status text-white-50"><i class="fas fa-check"></i></span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Footer Input -->
    <div class="chat-footer">
        <!-- Emoji Popover -->
        <div class="emoji-popover" id="emoji-popover">
            <emoji-picker></emoji-picker>
        </div>

        <!-- File Upload Preview -->
        <div id="file-preview-area">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-light p-2 rounded">
                    <i class="fas fa-file text-primary fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0 fs-6" id="file-name-display">Filename.jpg</h6>
                    <small class="text-muted">Ready to send</small>
                </div>
            </div>
            <button class="btn btn-sm btn-close" onclick="clearFile()"></button>
        </div>

        <button class="action-btn" id="emoji-btn" title="Emoji">
            <i class="far fa-smile fs-5"></i>
        </button>
        
        <button class="action-btn" onclick="document.getElementById('file-input').click()" title="Attach File">
            <i class="fas fa-paperclip fs-5"></i>
        </button>
        <input type="file" id="file-input" class="d-none" onchange="handleFileSelect(this)">

        <div class="input-wrapper">
            <input type="text" id="message-input" class="chat-input" placeholder="Type a message..." autocomplete="off">
        </div>

        <button class="action-btn send-btn" id="send-btn" onclick="sendMessage(event)">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

@push('scripts')
<script type="module">
    const projectId = "{{ $project->id }}";
    const userId = "{{ auth()->id() }}";
    const messagesList = document.getElementById('messages-list');
    const messageInput = document.getElementById('message-input');
    const fileInput = document.getElementById('file-input');
    
    // Auto scroll logic
    const scrollToBottom = () => {
        messagesList.scrollTop = messagesList.scrollHeight;
    };
    // Scroll initially
    scrollToBottom();

    // Setup Echo listener
    if (typeof Echo !== 'undefined') {
        Echo.private(`project.${projectId}`)
            .listen('NewProjectMessage', (e) => {
                appendMessage(e.message);
            });
    }

    // Emoji Picker Logic
    const emojiBtn = document.getElementById('emoji-btn');
    const emojiPopover = document.getElementById('emoji-popover');
    const emojiPicker = document.querySelector('emoji-picker');

    emojiBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        emojiPopover.classList.toggle('active');
    });

    emojiPicker.addEventListener('emoji-click', event => {
        messageInput.value += event.detail.unicode;
        messageInput.focus();
    });

    // Close picker on outside click
    document.addEventListener('click', (e) => {
        if (!emojiPopover.contains(e.target) && e.target !== emojiBtn && !emojiBtn.contains(e.target)) {
            emojiPopover.classList.remove('active');
        }
    });

    // File Handling
    window.handleFileSelect = function(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('file-preview-area').style.display = 'flex';
            document.getElementById('file-name-display').textContent = file.name;
        }
    };

    window.clearFile = function() {
        fileInput.value = '';
        document.getElementById('file-preview-area').style.display = 'none';
        document.getElementById('file-name-display').textContent = '';
    };

    // Send Message
    window.sendMessage = async function(e) {
        if(e) e.preventDefault();
        
        const message = messageInput.value.trim();
        const file = fileInput.files[0];
        
        if (!message && !file) return;

        const btn = document.getElementById('send-btn');
        btn.disabled = true;

        const formData = new FormData();
        if (message) formData.append('message', message);
        if (file) formData.append('attachment', file);

        try {
            // Optimistic Append (Text Only)
            if (message && !file) {
                // appendMessage({
                //     user_id: userId,
                //     user: { name: 'You' },
                //     message: message,
                //     type: 'text',
                //     created_at: new Date().toISOString()
                // });
            }

            const response = await window.axios.post("{{ route('website.project.chat.store', $project->id) }}", formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            appendMessage(response.data.message);
            
            messageInput.value = '';
            clearFile();
            emojiPopover.classList.remove('active');
            
        } catch (error) {
            console.error(error);
            toastr.error('Failed to send message');
        } finally {
            btn.disabled = false;
        }
    };

    // Enter key to send
    messageInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage(e);
        }
    });

    function appendMessage(msg) {
        const isMe = msg.user_id == userId;
        const containerClass = isMe ? 'sent' : 'received';
        
        // Avatar HTML
        const avatarHtml = !isMe ? 
            `<div class="avatar-spacer"><img src="${msg.user.profile_image_url || '{{ asset('website/images/no-user.png') }}'}" class="message-avatar" title="${msg.user.name}"></div>` 
            : '<div class="avatar-spacer"></div>'; // Spacer to align bubbles if needed, or remove

        // Build Content
        let contentHtml = '';
        
        if (msg.type === 'image') {
            contentHtml += `
                <div class="attachment-preview">
                    <a href="/storage/${msg.attachment_path}" target="_blank">
                        <img src="/storage/${msg.attachment_path}" alt="Image">
                    </a>
                </div>`;
        } else if (msg.type === 'file') {
            const fileName = msg.attachment_path.split('/').pop();
            contentHtml += `
                <a href="/storage/${msg.attachment_path}" target="_blank" class="file-chip">
                    <i class="fas fa-file-alt file-icon"></i>
                    <div class="text-truncate" style="max-width: 150px;">
                        ${fileName}
                        <div style="font-size: 0.7em; opacity: 0.7;">Download</div>
                    </div>
                </a>`;
        }

        if (msg.message) {
            contentHtml += `<div>${msg.message}</div>`;
        }

        // Meta (Time + Check)
        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const checkIcon = isMe ? '<span class="message-status text-white-50"><i class="fas fa-check"></i></span>' : '';

        // Name (Received only)
        const nameHtml = !isMe ? `<div class="sender-name">${msg.user.name}</div>` : '';

        const html = `
            <div class="message-container ${containerClass}">
                ${avatarHtml}
                <div class="message-card">
                    ${nameHtml}
                    <div class="message-content">
                        ${contentHtml}
                    </div>
                    <div class="message-meta">
                        <span class="message-time">${time}</span>
                        ${checkIcon}
                    </div>
                </div>
            </div>
        `;

        messagesList.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
    }
</script>
@endpush
@endsection
