<x-layout bodyClass="g-sidenav-show bg-gray-200">
    @push('styles')
        <style>
            @media (max-width: 767.98px) {
                #conversations-list {
                    max-height: 400px; /* Adjust height as needed */
                    overflow-y: auto;
                }
                .chat-container {
                    flex-direction: column;
                }
                .chat-container .col-3, .chat-container .col-9 {
                    width: 100%;
                    max-width: 100%;
                    flex: 0 0 100%;
                }
                #chat-body {
                    height: calc(100vh - 150px);
                    display: flex;
                    flex-direction: column;
                }
                #chat-messages {
                    flex-grow: 1;
                    overflow-y: auto;
                    margin-bottom: 10px; /* Add some margin between messages and input */
                }
                .chat-input-container {
                    background: white;
                    padding: 10px;
                    border-top: 1px solid #ddd;
                }
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const chatMessagesContainer = document.getElementById('chat-messages');
                const chatInput = document.getElementById('chat-input');
                const chatForm = document.getElementById('chat-form');
                const conversationsList = document.getElementById('conversations-list');
                const chatBody = document.getElementById('chat-body');
                const noChatMessage = document.getElementById('no-chat-message');
                const sendMessageButton = document.querySelector('#chat-form button[type="submit"]');
                let currentChatRoomId = null;
                let channel;

                // Initially hide the send button
                sendMessageButton.style.display = 'none';
                chatInput.disabled = true;

                // Function to fetch and display conversations
                function fetchConversations() {
                    fetch('{{ route('chat.get-conversations') }}')
                        .then(response => response.json())
                        .then(data => {
                            conversationsList.innerHTML = '';

                            data.forEach(conversation => {
                                const listItem = document.createElement('li');
                                listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

                                const otherUser = conversation.sender_id == {{ Auth::id() }}
                                    ? conversation.receiver
                                    : conversation.sender;

                                const otherUserName = otherUser?.first_name || 'Unknown';
                                const latestMessage = conversation.latest_message?.message || 'No messages yet';

                                const unreadCount = conversation.unread_messages_count > 0
                                    ? `<span class="badge bg-danger rounded-pill">${conversation.unread_messages_count}</span>`
                                    : '';

                                listItem.innerHTML = `
                    <span>
                        <strong>${otherUserName}</strong><br>
                        <small>${latestMessage}</small>
                    </span>
                    <div>
                        ${unreadCount}
                        <button class="btn btn-sm btn-primary" onclick="openChat(${conversation.id})">فتح</button>
                    </div>
                `;

                                conversationsList.appendChild(listItem);
                            });
                        })
                        .catch(error => console.error('Error fetching conversations:', error));
                }


                // Function to open a chat
                window.openChat = function (chatRoomId) {
                    currentChatRoomId = chatRoomId;

                    // Show send button and enable input when chat is selected
                    sendMessageButton.style.display = 'block';
                    chatInput.disabled = false;

                    // Update UI to mark conversation as read
                    const allButtons = document.querySelectorAll('#conversations-list button');
                    allButtons.forEach(button => {
                        button.classList.remove('btn-success'); // Remove green color
                        button.classList.add('btn-primary');   // Add default blue color
                    });

                    const clickedButton = [...allButtons].find(button =>
                        button.getAttribute('onclick').includes(`openChat(${chatRoomId})`)
                    );
                    if (clickedButton) {
                        clickedButton.classList.remove('btn-primary');
                        clickedButton.classList.add('btn-success'); // Keep the opened chat button green
                    }

                    if (channel) {
                        channel.unbind();
                    }

                    channel = pusher.subscribe('chat-room-' + chatRoomId + '_chat_type_id_1');

                    channel.bind('message-sent', function (data) {
                        if (data.chat_room_id === currentChatRoomId) {
                            displayMessage(data);
                        }
                    });

                    fetchMessages(chatRoomId);

                    // Mark messages as read
                    fetch('{{ route('chat.mark-as-read') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ chat_room_id: chatRoomId }),
                    }).then(() => {
                        fetchConversations(); // Refresh conversations list
                    });

                    noChatMessage.style.display = 'none';
                    chatBody.style.display = 'block';
                };


                // Function to fetch messages for a chat room
                function fetchMessages(chatRoomId) {
                    fetch('{{ route('chat.get-messages') }}?chatRoomId=' + chatRoomId)
                        .then(response => response.json())
                        .then(data => {
                            chatMessagesContainer.innerHTML = '';
                            data.messages.forEach(message => displayMessage(message));
                            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                        })
                        .catch(error => console.error('Error fetching messages:', error));
                }

                function displayMessage(message) {
                    const messageElement = document.createElement('div');
                    const isOwnMessage = message.sender_id == {{ Auth::id() }};

                    messageElement.classList.add('p-3', 'rounded-3', 'mb-2');

                    // Adjust width for mobile and desktop
                    if (window.innerWidth <= 767.98) { // Mobile devices
                        messageElement.classList.add('w-75'); // 75% width on mobile
                    } else { // Desktop devices
                        messageElement.classList.add('w-40'); // 40% width on desktop
                    }

                    if (isOwnMessage) {
                        messageElement.classList.add('bg-success', 'text-white', 'ms-auto');
                        messageElement.textContent = `${message.message}`;
                    } else {
                        messageElement.classList.add('bg-light', 'text-dark', 'me-auto');
                        messageElement.textContent = `${message.message}`;
                    }

// Format the timestamp (created_at) for Africa/Cairo timezone
                    const timestamp = new Date(message.created_at).toLocaleString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        timeZone: 'Africa/Cairo'
                    });

                    // Add the message content and timestamp
                    messageElement.innerHTML = `
        <div>${message.message}</div>
        <small class="d-block text-end">${timestamp}</small>
    `;

                    chatMessagesContainer.appendChild(messageElement);
                    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                }

                // Handle chat form submission
                chatForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const message = chatInput.value;

                    if (!currentChatRoomId) {
                        console.error('No chat room selected.');
                        return;
                    }

                    fetch('{{ route('chat.send-message') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            message: message,
                            chat_room_id: currentChatRoomId,
                        }),
                    })
                        .then(() => {
                            chatInput.value = '';
                        })
                        .catch(error => console.error('Error sending message:', error));
                });

                // Initialize Pusher
                const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                    encrypted: true,
                });

                // Subscribe to the conversations channel
                const conversationsChannel = pusher.subscribe('conversations');

                // Listen for conversation updates
                conversationsChannel.bind('conversation.updated', function (data) {
                    // Refresh the conversations list
                    fetchConversations();
                });

                // Fetch conversations on page load
                fetchConversations();

                // FIXED: Prevent null error in material-dashboard.js
                const navbarElement = document.querySelector('.navbar');
                if (navbarElement) {
                    navbarElement.classList.add('navbar-enhanced');
                }
            });
        </script>
    @endpush

        <x-navbars.sidebar activePage="chats"></x-navbars.sidebar>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            <x-navbars.navs.auth titlePage="غرفة الدردشة"></x-navbars.navs.auth>

            <div class="container-fluid py-4">
                <div class="row chat-container">
                    <div class="col-12 col-md-3 mb-2">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5>المحادثات</h5>
                            </div>
                            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                                <ul id="conversations-list" class="list-group list-group-flush"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div id="no-chat-message" class="text-center" style="display: block;">
                                    <p>الرجاء تحديد الدردشة</p>
                                </div>
                                <div id="chat-body" class="d-flex flex-column" style="height: 505px; display: none;">
                                    <div id="chat-messages" class="flex-grow-1 overflow-auto mb-3 p-3"></div>
                                    <div class="chat-input-container">
                                        <form id="chat-form" class="d-flex gap-2">
                                            <input type="text" id="chat-input" class="form-control" placeholder="اكتب رسالتك">
                                            <button type="submit" class="form-control max-width-100 btn btn-primary">ارسال</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
</x-layout>
