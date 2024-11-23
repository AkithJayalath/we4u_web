<?php
// Start a session to store chat messages
session_start();

// Initialize messages array in session if not already set
if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [];
}

// Handle form submission for new messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    // Get the user's message
    $userMessage = htmlspecialchars($_POST['message']);

    // Add the user's message to the messages array
    $_SESSION['messages'][] = ['type' => 'sent', 'text' => $userMessage];

    // Add the system's response
    $_SESSION['messages'][] = ['type' => 'received', 'text' => 'How can I help you?'];

    // Redirect to the same page to prevent resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Page</title>
    <link rel="stylesheet" href="chat.css">
</head>
<body>
    
    <div class="chat-container">
        <!-- Contacts Section -->
        <div class="contacts">
            <h3>Contacts</h3>
            <ul>
                <li>
                    <img src="profile.jpg" alt="John Doe">
                    <span>Sun Wukong</span>
                </li>
                <li>
                    <img src="profile.jpg" alt="John Doe">
                    <span>Randila</span>
                </li>
                <li>
                    <img src="profile.jpg" alt="John Doe">
                    <span>Dr.Wootan Yu</span>
                </li>
            </ul>
        </div>

        <!-- Chat Section -->
        <div class="chat">
            <!-- Chat Header -->
            <div class="chat-header">
                <img src="profile.jpg" alt="John Doe">
                <div class="chat-header-info">
                    <h2>Sun Wukong</h2>
                    <span class="status online">Online</span>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="chat-messages">
                <?php foreach ($_SESSION['messages'] as $message): ?>
                    <div class="message <?php echo $message['type']; ?>">
                        <?php echo $message['text']; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Message Input -->
            <div class="message-input">
            <button class="attachment-btn">📎</button>
                <form method="POST" action="">
                    <textarea name="message" placeholder="Type your message..." required></textarea>
                    <button type="submit">Send</button>

                </form>
            </div>
        </div>
    </div>
</body>
</html>
