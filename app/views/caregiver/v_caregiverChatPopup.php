<div id="chat-popup" class="chat-popup">
    <div class="chat-popup-header">
        <div class="chat-session-info">
            <div class="elder-info">
                <?php
                // Determine which image to display
                $caregiver_pic = !empty($data['careseeker']->profile_picture)
                    ? URLROOT . '/public/images/profile_imgs/' . $data['careseeker']->profile_picture
                    : URLROOT . '/public/images/def_profile_pic2.jpg';
                ?>
                <img src="<?php echo $caregiver_pic ?>" alt="Caregiver Profile" class="elder-pic">
                <div>
                    <h3><?php echo $data['careseeker']->username ?></h3>
                    <p>For: <?php echo $data['elder']->first_name . ' ' . 
    ($data['elder']->middle_name ? $data['elder']->middle_name . ' ' : '') . 
    $data['elder']->last_name; ?></p>
                </div>
            </div>
        </div>
        <div class="chat-popup-controls">
            <!-- Minimize button removed -->
            <button id="close-chat" class="close-btn"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <div class="chat-messages" id="chat-messages">
        <?php if (empty($data['messages'])): ?>
            <div class="no-messages">
                <p>No messages yet. Start the conversation!</p>
            </div>
        <?php else: ?>
            <?php 
            $lastDate = '';
            foreach ($data['messages'] as $message): 
                $messageDate = new DateTime($message->created_at);
                $today = new DateTime();
                $yesterday = new DateTime('-1 day');
                
                if ($messageDate->format('Y-m-d') === $today->format('Y-m-d')) {
                    $formattedDate = 'Today';
                } elseif ($messageDate->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                    $formattedDate = 'Yesterday';
                } else {
                    $formattedDate = $messageDate->format('l, F j, Y');
                }
                
                // Check if we need a new date separator
                if ($formattedDate !== $lastDate):
            ?>
                <div class="date-separator">
                    <span><?php echo $formattedDate; ?></span>
                </div>
            <?php 
                    $lastDate = $formattedDate;
                endif;
            ?>
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