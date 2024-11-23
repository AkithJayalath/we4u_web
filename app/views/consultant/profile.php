<?php
// Start a session to store feedback messages
session_start();

// Initialize feedback array in session if not already set
if (!isset($_SESSION['feedbacks'])) {
    $_SESSION['feedbacks'] = [];
}

// Handle form submission for feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['feedback'])) {
    $userFeedback = htmlspecialchars($_POST['feedback']); // Sanitize user input
    $_SESSION['feedbacks'][] = ['type' => 'user', 'text' => $userFeedback];
    $_SESSION['feedbacks'][] = ['type' => 'system', 'text' => 'Thank you for your feedback!'];

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultant Profile</title>
    <link rel="stylesheet" href="profile.css">
    <script src="profile.js" defer></script>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <h1>Consultant Profile</h1>
    </header>

    <!-- Profile Section -->
    <div class="profile-container">
        <img src="profile.jpg" alt="Consultant Picture">
        <h2>Dr. Wootan Yu</h2>
        <ul>
            <p><strong>Email:</strong> consultant@example.com</p>
            <p><strong>Address:</strong> 1234 Health Street, Wellness City</p>
            <p><strong>Contact:</strong> +123 456 7890</p>
            <p><strong>Registration Number:</strong> TPR-12345</p>
            <p><strong>Experience:</strong> 5 years</p>
            <p><strong>Specialities:</strong></p>
            <p> Musculoskeletal Injuries</p>
            <p> Electrotherapy</p>
        </ul>
    </div>

    <!-- Bio Section -->
    <div class="section bio-section">
        <h3>About Me</h3>
        <p>I’m a licensed physical therapist with years of experience. I earned a <b>Bachelor’s Degree in Physical Therapy</b> from <b> University Health Science Center</b>. I specialize in treating [specific conditions, e.g., musculoskeletal injuries, neurological disorders, sports injuries, etc.] through [specific techniques, e.g., manual therapy, therapeutic exercise, electrotherapy, etc.].
</p>
    </div>

    <!-- Book Slot Button -->
    <div class="book-slot">
        <button class="book-slot-btn">Book a Slot</button>
    </div>

    <!-- Rate Me Section -->
    <div class="section rate-me">
        <h3>Rate Me</h3>
        <div class="stars">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
    </div>

    <!-- Feedback and Replies -->
    <div class="section comment-reply-section">
        <h3>Feedback and Replies</h3>
        <div class="chat-message">
            <?php foreach ($_SESSION['feedbacks'] as $feedback): ?>
                <div class="<?php echo $feedback['type'] === 'user' ? 'comment' : 'reply'; ?>">
                    <p><?php echo $feedback['text']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Feedback Form -->
    <div class="section feedback-section">
        <h3>Your Feedback</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <textarea class="feedback-textarea" name="feedback" placeholder="Share your feedback..."></textarea>
            <button class="send-feedback-btn" type="submit">Submit</button>
        </form>
    </div>

    <!-- Note Section -->
    <div class="note">
        <p>Your feedback helps us improve. Thank you for sharing!</p>
    </div>
</body>
</html>
