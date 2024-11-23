<?php
// Start a session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

    // Redirect to prevent resubmission
    header('Location: chat.php'); // Explicitly redirect to the current file
    exit;
}
?>
<?php require APPROOT.'/views/includes/header.php'; ?>
<body>
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/chat.css">

<div class="chat-container">
    <!-- Contacts Section -->
    <div class="contacts">
        <h3>Contacts</h3>
        <ul>
            <li>
                <img src="./profile.jpg" alt="Sun Wukong">
                <span>Sun Wukong</span>
            </li>
            <li>
                <img src="./profile.jpg" alt="Randila">
                <span>Randila</span>
            </li>
            <li>
                <img src="./profile.jpg" alt="Dr. Wootan Yu">
                <span>Dr. Wootan Yu</span>
            </li>
        </ul>
    </div>

    <!-- Chat Section -->
    <div class="chat">
        <!-- Chat Header -->
        <div class="chat-header">
            <img src="profile.jpg" alt="Sun Wukong">
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

<footer>
<?php require APPROOT.'/views/includes/footer.php'; ?>
</footer>
