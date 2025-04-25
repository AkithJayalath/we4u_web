document.addEventListener('DOMContentLoaded', function() {
    const floatingChatButtons = document.querySelectorAll('.floating-chat-button');
    let chatPopupContainer = document.getElementById('caregiver-chat-popup-container');
    
    if (!chatPopupContainer) {
        // Create container div if it doesn't exist
        chatPopupContainer = document.createElement('div');
        chatPopupContainer.id = 'caregiver-chat-popup-container';
        chatPopupContainer.className = 'hidden';
        document.body.appendChild(chatPopupContainer);
    }
    
    let chatIsOpen = false;
    let chatIsMinimized = false;
    let chatMessages = null;
    let lastMessageId = 0;
    let lastReadMessageId = 0;
    let pollingInterval = null;
    let currentUserId = null;
    let unreadMessageCount = 0;
    let messageBadges = new Map(); // Map to store message badges for each button
    let lastDisplayedDate = '';
    let currentRequestId = null;
    
    // Initialize all floating chat buttons
    floatingChatButtons.forEach(button => {
        const requestId = button.getAttribute('data-request-id');
        if (!requestId) return;
        
        // Create and initialize message badge for each button
        createMessageBadge(button, requestId);
        
        // Get user ID from data attribute
        const userId = button.getAttribute('data-user-id');
        
        // Start polling messages for this request
        startGlobalMessagePolling(requestId, userId);
        
        // Set up click event for each button
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            const userId = this.getAttribute('data-user-id');
            
            handleChatButtonClick(requestId, userId, button);
        });
    });
    
    // Create and initialize message badge
    function createMessageBadge(button, requestId) {
        const storageKey = `lastReadMessageId_caregiverChat_${requestId}`;
        
        // Get last read message ID from localStorage
        if (localStorage.getItem(storageKey)) {
            lastReadMessageId = parseInt(localStorage.getItem(storageKey)) || 0;
        }
        
        const messageBadge = document.createElement('span');
        messageBadge.className = 'message-badge hidden';
        button.appendChild(messageBadge);
        
        // Store reference to badge in map
        messageBadges.set(requestId, {
            element: messageBadge,
            count: 0,
            lastReadId: lastReadMessageId
        });
    }
    
    // Update the message badge display
    function updateMessageBadge(requestId) {
        const badge = messageBadges.get(requestId);
        if (!badge) return;
        
        if (badge.count > 0) {
            badge.element.textContent = badge.count > 99 ? '99+' : badge.count;
            badge.element.classList.remove('hidden');
        } else {
            badge.element.classList.add('hidden');
        }
    }
    
    // Reset unread counter and mark messages as read
    function resetUnreadCounter(requestId) {
        const badge = messageBadges.get(requestId);
        if (!badge) return;
        
        badge.count = 0;
        badge.lastReadId = lastMessageId;
        
        // Store last read message ID in localStorage
        const storageKey = `lastReadMessageId_caregiverChat_${requestId}`;
        localStorage.setItem(storageKey, lastMessageId.toString());
        
        updateMessageBadge(requestId);
        
        // Also mark messages as read in database
        if (currentRequestId) {
            const chatId = document.getElementById('chat_id')?.value;
            if (chatId) {
                fetch(`${URLROOT}/caregiverChat/markMessagesAsRead`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `chat_id=${chatId}`
                });
            }
        }
    }
    
    // Format message date
    function formatMessageDate(dateString) {
        const messageDate = new Date(dateString);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        // Check if it's today
        if (messageDate.toDateString() === today.toDateString()) {
            return 'Today';
        }
        // Check if it's yesterday
        else if (messageDate.toDateString() === yesterday.toDateString()) {
            return 'Yesterday';
        }
        // Otherwise return formatted date
        else {
            return messageDate.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
    }
    
    // Initialize unread message count for a request
    function initializeUnreadCount(chatId, requestId, userId) {
        fetch(`${URLROOT}/caregiverChat/getAllMessages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `chat_id=${chatId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.messages.length > 0) {
                // Update last message ID
                lastMessageId = parseInt(data.messages[data.messages.length - 1].message_id);
                
                const badge = messageBadges.get(requestId);
                if (!badge) return;
                
                // Calculate unread messages (only ones from others and newer than lastReadMessageId)
                const unreadMessages = data.messages.filter(message => 
                    message.sender_id != userId && 
                    parseInt(message.message_id) > badge.lastReadId
                );
                
                badge.count = unreadMessages.length;
                updateMessageBadge(requestId);
            }
        })
        .catch(error => {
            console.error('Error initializing unread count:', error);
        });
    }
    
    // Start polling for messages for a specific request
    function startGlobalMessagePolling(requestId, userId) {
        // Get or create chat ID
        fetch(`${URLROOT}/caregiverChat/getOrCreateChatId/${requestId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const chatId = data.chat_id;
                
                // Initialize unread count when page loads
                initializeUnreadCount(chatId, requestId, userId);
                
                // Poll for new messages periodically
                setInterval(function() {
                    // If chat is open, focused on this request, and not minimized, don't increment the counter
                    const shouldUpdateCounter = !chatIsOpen || currentRequestId !== requestId;
                    
                    pollGlobalMessages(chatId, requestId, userId, shouldUpdateCounter);
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Error getting chat ID:', error);
        });
    }
    
    // Poll for new messages
    function pollGlobalMessages(chatId, requestId, userId, shouldUpdateCounter) {
        fetch(`${URLROOT}/caregiverChat/getNewMessages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `chat_id=${chatId}&last_message_id=${lastMessageId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.messages.length > 0) {
                    // Filter messages that are new (not from current user) AND unread
                    const badge = messageBadges.get(requestId);
                    if (!badge) return;
                    
                    const newUnreadMessages = data.messages.filter(message => 
                        message.sender_id != userId && 
                        parseInt(message.message_id) > badge.lastReadId
                    );
                    
                    // Only update unread count if we're supposed to and there are unread messages
                    if (shouldUpdateCounter && newUnreadMessages.length > 0) {
                        badge.count += newUnreadMessages.length;
                        updateMessageBadge(requestId);
                    }
                    
                    // Update last message ID
                    lastMessageId = parseInt(data.messages[data.messages.length - 1].message_id);
                    
                    // If chat is open for this request, mark all as read
                    if (chatIsOpen && currentRequestId === requestId) {
                        badge.lastReadId = lastMessageId;
                        const storageKey = `lastReadMessageId_caregiverChat_${requestId}`;
                        localStorage.setItem(storageKey, lastMessageId.toString()); // Save to localStorage
                        resetUnreadCounter(requestId); // Clear badge immediately when chat is open
                        
                        // Also mark messages as read in database
                        fetch(`${URLROOT}/caregiverChat/markMessagesAsRead`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `chat_id=${chatId}`
                        });
                    }
                    
                    // If chat is open and for this request, update the chat display
                    if (chatIsOpen && currentRequestId === requestId) {
                        data.messages.forEach(message => {
                            // Check if message is from current user
                            const isOutgoing = message.sender_id == userId;
                            addMessage(message, isOutgoing);
                        });
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error polling messages:', error);
        });
    }
    
    // Handle chat button click to load and open chat
    function handleChatButtonClick(requestId, userId, button) {
        // Reset unread counter for this request immediately when clicked
        resetUnreadCounter(requestId);
        
        if (chatIsOpen && currentRequestId === requestId) {
            // If chat is already open for this request, just return
            return;
        } else {
            // Load chat via AJAX
            fetch(`${URLROOT}/caregiverChat/getChatData/${requestId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Close any existing chat
                    if (chatIsOpen) {
                        // If we're switching to a different chat, reset the previously visible one
                        if (currentRequestId && currentRequestId !== requestId) {
                            // No need to do anything with the old chat
                        }
                    }
                    
                    // Set the HTML content of the popup
                    chatPopupContainer.innerHTML = data.html;
                    
                    // Show the popup
                    chatPopupContainer.classList.remove('hidden');
                    
                    // Set up event listeners for the chat popup
                    setupChatEventListeners(requestId, userId);
                    
                    chatIsOpen = true;
                    chatIsMinimized = false;
                    currentRequestId = requestId;
                    currentUserId = userId;
                } else {
                    alert('Failed to load chat: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error loading chat:', error);
                alert('Failed to load chat. Please try again.');
            });
        }
    }
    
    // Set up event listeners for chat popup
    function setupChatEventListeners(requestId, userId) {
        const chatPopup = document.getElementById('chat-popup');
        const closeBtn = document.getElementById('close-chat');
        const messageForm = document.getElementById('message-form');
        
        // Get chat messages element
        chatMessages = document.getElementById('chat-messages');
        
        // Scroll to bottom of chat
        scrollToBottom();
        
        // Close chat
        closeBtn.addEventListener('click', function() {
            chatPopupContainer.classList.add('hidden');
            chatIsOpen = false;
            chatIsMinimized = false;
            currentRequestId = null;
        });
        
        // Send message
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('message');
            const messageText = messageInput.value.trim();
            const chatId = document.getElementById('chat_id').value;
            
            if (!messageText) return;
            
            // Send message via AJAX
            fetch(`${URLROOT}/caregiverChat/sendMessage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `chat_id=${chatId}&message=${encodeURIComponent(messageText)}`
            })
            .then(response => {
                // Check if the response is valid before trying to parse it as JSON
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Clear input
                    messageInput.value = '';
                    
                    // Add message to chat
                    addMessage(data.message, true);
                    
                    // Update last message ID and read ID since we just sent a message
                    lastMessageId = parseInt(data.message.message_id);
                    
                    // Update the badge data
                    const badge = messageBadges.get(requestId);
                    if (badge) {
                        badge.lastReadId = lastMessageId;
                        const storageKey = `lastReadMessageId_caregiverChat_${requestId}`;
                        localStorage.setItem(storageKey, lastMessageId.toString()); // Save to localStorage
                    }
                } else {
                    console.error('Error sending message:', data.message);
                    // Clear the message anyway since it was saved in the database
                    messageInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Still clear the input since the message likely went through
                messageInput.value = '';
            });
        });
        
        // Get the last message ID and initialize last displayed date
        const messages = chatMessages.querySelectorAll('.message');
        if (messages.length > 0) {
            const lastMessage = messages[messages.length - 1];
            lastMessageId = parseInt(lastMessage.getAttribute('data-message-id')) || 0;
            
            // Mark all existing messages as read
            const badge = messageBadges.get(requestId);
            if (badge) {
                badge.lastReadId = lastMessageId;
                const storageKey = `lastReadMessageId_caregiverChat_${requestId}`;
                localStorage.setItem(storageKey, lastMessageId.toString()); // Save to localStorage
            }
            
            // Find the last date separator
            const dateSeparators = chatMessages.querySelectorAll('.date-separator');
            if (dateSeparators.length > 0) {
                lastDisplayedDate = dateSeparators[dateSeparators.length - 1].querySelector('span').textContent;
            }
        }
    }
    
    // Add message to chat
    function addMessage(message, isOutgoing = false) {
        if (!chatMessages) {
            return; // Skip if chat messages container doesn't exist yet
        }
        
        const messageDate = new Date(message.created_at);
        const formattedDate = formatMessageDate(message.created_at);
        
        // Check if we need to add a date separator
        if (lastDisplayedDate !== formattedDate) {
            const dateSeparator = document.createElement('div');
            dateSeparator.className = 'date-separator';
            dateSeparator.innerHTML = `<span>${formattedDate}</span>`;
            chatMessages.appendChild(dateSeparator);
            lastDisplayedDate = formattedDate;
        }
        
        const messageElement = document.createElement('div');
        messageElement.className = `message ${isOutgoing ? 'outgoing' : 'incoming'}`;
        messageElement.setAttribute('data-message-id', message.message_id);
        
        const messageTime = new Date(message.created_at);
        const formattedTime = messageTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        // Make sure profile_picture has the complete path
        let profilePicPath = message.profile_picture;
        if (!profilePicPath) {
            profilePicPath = `${URLROOT}/public/images/def_profile_pic2.jpg`;
        } else if (!profilePicPath.includes(URLROOT)) {
            profilePicPath = `${URLROOT}/images/profile_imgs/${profilePicPath}`;
        }
        
        messageElement.innerHTML = `
            <div class="message-content">
                <div class="message-header">
                    <img src="${profilePicPath}" alt="Profile" class="message-pic">
                    <span class="message-username">${message.username}</span>
                    <span class="message-time">${formattedTime}</span>
                </div>
                <div class="message-text">
                    ${message.message_text}
                </div>
            </div>
        `;
        
        chatMessages.appendChild(messageElement);
        scrollToBottom();
    }
    
    // Scroll to bottom of chat
    function scrollToBottom() {
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }
});