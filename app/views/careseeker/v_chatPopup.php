<!-- File: app/views/consultant/partials/chat_popup.php -->
<div id="chat-popup" class="chat-popup">
    <div class="chat-popup-header">
        <div class="chat-session-info">
            <div class="elder-info">
                <?php
                // Determine which image to display
                $elderprofilePic  = !empty($data['consultant']->profile_picture)
                    ? URLROOT . '/public/images/profile_imgs/' . $data['consultant']->profile_picture
                    : URLROOT . '/public/images/def_profile_pic2.jpg';

                ?>
                <img src="<?php echo $elderprofilePic ?>" alt="Elder Profile" class="elder-pic">
                <div>
                    <h3><?php echo $data['consultant']->username ?></h3>
                </div>
            </div>
        </div>
        <div class="chat-popup-controls">
            <button id="minimize-chat" class="minimize-btn"><i class="fas fa-minus"></i></button>
            <button id="close-chat" class="close-btn"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <div class="chat-messages" id="chat-messages">
        <?php if (empty($data['messages'])): ?>
            <div class="no-messages">
                <p>No messages yet. Start the conversation!</p>
            </div>
        <?php else: ?>
            <?php foreach ($data['messages'] as $message): ?>
                <div class="message <?php echo ($message->sender_id == $data['user_id']) ? 'outgoing' : 'incoming'; ?>" data-message-id="<?php echo $message->message_id; ?>">
                    <div class="message-content">
                        <div class="message-header">
                            <?php
                            // Determine which image to display
                            $profilePic = !empty($message->profile_picture)
                                ? URLROOT . '/images/profile_imgs/' . $message->profile_picture
                                : URLROOT . '/images/def_profile_pic2.jpg';
                            ?>
                            <img src="<?php echo $profilePic ?>" alt="Profile" class="message-pic">
                            <span class="message-username"><?php echo $message->username; ?></span>
                            <span class="message-time"><?php echo date('h:i A', strtotime($message->created_at)); ?></span>
                        </div>
                        <div class="message-text">
                            <?php echo $message->message_text; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="chat-input">
        <form id="message-form">
            <input type="hidden" id="chat_id" value="<?php echo $data['chat_id']; ?>">
            <textarea id="message" placeholder="Type your message..."></textarea>
            <button type="submit" id="send-btn">Send</button>
        </form>
    </div>
</div>