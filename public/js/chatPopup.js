document.addEventListener('DOMContentLoaded', function() {
    const openChatBtn = document.getElementById('open-chat-btn');
    const chatPopupContainer = document.getElementById('chat-popup-container');
    let chatIsOpen = false;
    let chatIsMinimized = false;
    let chatMessages = null;
    let lastMessageId = 0;
    let lastReadMessageId = 0; // Track the last read message
    let pollingInterval = null;
    let currentUserId = null;
    let unreadMessageCount = 0;
    let messageBadge = null;
    let lastDisplayedDate = '';
    
    // Get session ID for localStorage key
    const sessionId = openChatBtn.getAttribute('data-session-id');
    const storageKey = `lastReadMessageId_${sessionId}`;
    
    // Get last read message ID from localStorage
    if (localStorage.getItem(storageKey)) {
        lastReadMessageId = parseInt(localStorage.getItem(storageKey)) || 0;
    }
    
    // Create and initialize message badge
    function createMessageBadge() {
        if (!messageBadge) {
            messageBadge = document.createElement('span');
            messageBadge.className = 'message-badge hidden';
            openChatBtn.appendChild(messageBadge);
        }
    }
    
    // Update the message badge display
    function updateMessageBadge() {
        if (!messageBadge) {
            createMessageBadge();
        }
        
        if (unreadMessageCount > 0) {
            messageBadge.textContent = unreadMessageCount > 9 ? '9+' : unreadMessageCount;
            messageBadge.classList.remove('hidden');
        } else {
            messageBadge.classList.add('hidden');
        }
    }
    
    // Reset unread counter and mark messages as read
    function resetUnreadCounter() {
        unreadMessageCount = 0;
        lastReadMessageId = lastMessageId; // Mark all messages as read
        // Store last read message ID in localStorage
        localStorage.setItem(storageKey, lastReadMessageId.toString());
        updateMessageBadge();
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
    
    // Initialize the message badge
    createMessageBadge();
    
    // When initializing, retrieve all messages and check for unread ones
    function initializeUnreadCount(chatId) {
        fetch(`${URLROOT}/sessionChat/getAllMessages`, {
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
                
                // Calculate unread messages (only ones from others and newer than lastReadMessageId)
                const unreadMessages = data.messages.filter(message => 
                    message.sender_id != currentUserId && 
                    parseInt(message.message_id) > lastReadMessageId
                );
                
                unreadMessageCount = unreadMessages.length;
                updateMessageBadge();
            }
        })
        .catch(error => {
            console.error('Error initializing unread count:', error);
        });
    }
    
    // Start polling for new messages even if chat is not opened yet
    function startGlobalMessagePolling() {
        const sessionId = openChatBtn.getAttribute('data-session-id');
        currentUserId = openChatBtn.getAttribute('data-user-id');
        
        // Get or create chat ID
        fetch(`${URLROOT}/sessionChat/getOrCreateChatId/${sessionId}`, {
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
                initializeUnreadCount(chatId);
                
                // Poll for new messages periodically
                setInterval(function() {
                    // If chat is open and not minimized, don't increment the counter
                    const shouldUpdateCounter = !chatIsOpen || chatIsMinimized;
                    
                    pollGlobalMessages(chatId, shouldUpdateCounter);
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Error getting chat ID:', error);
        });
    }
    
    // Poll for new messages globally (even when chat is closed)
    function pollGlobalMessages(chatId, shouldUpdateCounter) {
        fetch(`${URLROOT}/sessionChat/getNewMessages`, {
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
                    const newUnreadMessages = data.messages.filter(message => 
                        message.sender_id != currentUserId && 
                        parseInt(message.message_id) > lastReadMessageId
                    );
                    
                    // Only update unread count if we're supposed to and there are unread messages
                    if (shouldUpdateCounter && newUnreadMessages.length > 0) {
                        unreadMessageCount += newUnreadMessages.length;
                        updateMessageBadge();
                    }
                    
                    // Update last message ID
                    lastMessageId = parseInt(data.messages[data.messages.length - 1].message_id);
                    
                    // If chat is open and NOT minimized, mark all as read
                    if (chatIsOpen && !chatIsMinimized) {
                        lastReadMessageId = lastMessageId;
                        localStorage.setItem(storageKey, lastReadMessageId.toString()); // Save to localStorage
                        resetUnreadCounter(); // Clear badge immediately when chat is open
                    }
                    
                    // If chat is open, update the chat display
                    if (chatIsOpen) {
                        data.messages.forEach(message => {
                            // Check if message is from current user
                            const isOutgoing = message.sender_id == currentUserId;
                            addMessage(message, isOutgoing);
                        });
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Open chat when the chat button is clicked
    openChatBtn.addEventListener('click', function() {
        if (chatIsOpen && !chatIsMinimized) {
            // If chat is already open and not minimized, do nothing
            return;
        } else if (chatIsOpen && chatIsMinimized) {
            // If chat is minimized, maximize it
            document.getElementById('chat-popup').classList.remove('minimized');
            const minimizeIcon = document.getElementById('minimize-chat').querySelector('i');
            minimizeIcon.className = 'fas fa-minus';
            chatIsMinimized = false;
            resetUnreadCounter(); // Reset counter when maximized
            return;
        }
        
        // Reset unread counter when opening chat
        resetUnreadCounter();
        
        // Get session ID from data attribute
        const sessionId = openChatBtn.getAttribute('data-session-id');
        currentUserId = openChatBtn.getAttribute('data-user-id');
        
        // Load chat via AJAX
        fetch(`${URLROOT}/sessionChat/getChatData/${sessionId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Set the HTML content of the popup
                chatPopupContainer.innerHTML = data.html;
                
                // Show the popup
                chatPopupContainer.classList.remove('hidden');
                
                // Set up event listeners for the chat popup
                setupChatEventListeners();
                
                chatIsOpen = true;
            } else {
                alert('Failed to load chat: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error loading chat:', error);
            alert('Failed to load chat. Please try again.');
        });
    });
    
    // Set up event listeners for chat popup
    function setupChatEventListeners() {
        const chatPopup = document.getElementById('chat-popup');
        const minimizeBtn = document.getElementById('minimize-chat');
        const minimizeIcon = minimizeBtn.querySelector('i');
        const closeBtn = document.getElementById('close-chat');
        const messageForm = document.getElementById('message-form');
        
        // Get chat messages element
        chatMessages = document.getElementById('chat-messages');
        
        // Scroll to bottom of chat
        scrollToBottom();
        
        // Minimize/Maximize chat
        minimizeBtn.addEventListener('click', function() {
            if (chatPopup.classList.contains('minimized')) {
                // If minimized, maximize it
                chatPopup.classList.remove('minimized');
                minimizeIcon.className = 'fas fa-minus';
                chatIsMinimized = false;
                resetUnreadCounter(); // Reset counter when maximized
            } else {
                // If maximized, minimize it
                chatPopup.classList.add('minimized');
                minimizeIcon.className = 'fas fa-expand';
                chatIsMinimized = true;
            }
        });
        
        // Close chat
        closeBtn.addEventListener('click', function() {
            chatPopupContainer.classList.add('hidden');
            chatIsOpen = false;
            chatIsMinimized = false;
        });
        
        // Send message
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('message');
            const messageText = messageInput.value.trim();
            const chatId = document.getElementById('chat_id').value;
            
            if (!messageText) return;
            
            // Send message via AJAX
            fetch(`${URLROOT}/sessionChat/sendMessage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `chat_id=${chatId}&message=${encodeURIComponent(messageText)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Clear input
                    messageInput.value = '';
                    
                    // Add message to chat
                    addMessage(data.message, true);
                    
                    // Update last message ID and read ID since we just sent a message
                    lastMessageId = parseInt(data.message.message_id);
                    lastReadMessageId = lastMessageId;
                    localStorage.setItem(storageKey, lastReadMessageId.toString()); // Save to localStorage
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        // Get the last message ID and initialize last displayed date
        const messages = chatMessages.querySelectorAll('.message');
        if (messages.length > 0) {
            const lastMessage = messages[messages.length - 1];
            lastMessageId = parseInt(lastMessage.getAttribute('data-message-id')) || 0;
            lastReadMessageId = lastMessageId; // Mark all existing messages as read
            localStorage.setItem(storageKey, lastReadMessageId.toString()); // Save to localStorage
            
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
        // If the profile picture doesn't already include the URL root, add it
        if (profilePicPath && !profilePicPath.includes(URLROOT)) {
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
    
    // Start global polling for all sessions
    startGlobalMessagePolling();
});