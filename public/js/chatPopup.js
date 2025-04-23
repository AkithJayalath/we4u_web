// chatPopup.js
document.addEventListener('DOMContentLoaded', function() {
    const openChatBtn = document.getElementById('open-chat-btn');
    const chatPopupContainer = document.getElementById('chat-popup-container');
    let chatIsOpen = false;
    let chatIsMinimized = false;
    let chatMessages = null;
    let lastMessageId = 0;
    let pollingInterval = null;
    let currentUserId = null;
    
    // Open chat when the chat button is clicked
    openChatBtn.addEventListener('click', function() {
        if (chatIsOpen && !chatIsMinimized) {
            // If chat is already open and not minimized, do nothing
            return;
        } else if (chatIsOpen && chatIsMinimized) {
            // If chat is minimized, maximize it
            document.getElementById('chat-popup').classList.remove('minimized');
            chatIsMinimized = false;
            return;
        }
        
        // Get session ID from data attribute
        const sessionId = openChatBtn.getAttribute('data-session-id');
        currentUserId = openChatBtn.getAttribute('data-user-id');
        
        // Load chat via AJAX
        fetch(`${URLROOT}/consultant/getChatData/${sessionId}`, {
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
                
                // Initialize polling for new messages
                startMessagePolling(sessionId);
                
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
        const closeBtn = document.getElementById('close-chat');
        const messageForm = document.getElementById('message-form');
        
        // Get chat messages element
        chatMessages = document.getElementById('chat-messages');
        
        // Scroll to bottom of chat
        scrollToBottom();
        
        // Minimize chat
        minimizeBtn.addEventListener('click', function() {
            chatPopup.classList.add('minimized');
            chatIsMinimized = true;
        });
        
        // Close chat
        closeBtn.addEventListener('click', function() {
            chatPopupContainer.classList.add('hidden');
            chatIsOpen = false;
            chatIsMinimized = false;
            
            // Stop polling for new messages
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        });
        
        // Send message
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('message');
            const messageText = messageInput.value.trim();
            const chatId = document.getElementById('chat_id').value;
            
            if (!messageText) return;
            
            // Send message via AJAX
            fetch(`${URLROOT}/consultant/sendMessage`, {
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
                    
                    // Update last message ID
                    lastMessageId = data.message.message_id;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        // Get the last message ID
        const messages = chatMessages.querySelectorAll('.message');
        if (messages.length > 0) {
            const lastMessage = messages[messages.length - 1];
            lastMessageId = lastMessage.getAttribute('data-message-id') || 0;
        }
    }
    
    // Start polling for new messages
    function startMessagePolling(sessionId) {
        pollingInterval = setInterval(function() {
            pollMessages(sessionId);
        }, 3000);
    }
    
    // Poll for new messages
    function pollMessages(sessionId) {
        if (!chatIsOpen) return;
        
        fetch(`${URLROOT}/consultant/getNewMessages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `chat_id=${document.getElementById('chat_id').value}&last_message_id=${lastMessageId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                data.messages.forEach(message => {
                    // Check if message is from current user
                    const isOutgoing = message.sender_id == currentUserId;
                    addMessage(message, isOutgoing);
                });
                
                if (data.messages.length > 0) {
                    lastMessageId = data.messages[data.messages.length - 1].message_id;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Add message to chat
    // Modify the addMessage function to handle profile picture paths correctly
function addMessage(message, isOutgoing = false) {
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
});