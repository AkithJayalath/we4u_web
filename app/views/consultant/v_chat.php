<?php
$required_styles = [
    'careseeker/careseekerCreateProfile',
    'consultant/chat'
];
echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="total-container">
        <div class="careseeker-profile-container">
            <h2>Chat</h2>
            
            <div class="chat-container">
                <!-- Contacts Section -->
                <div class="contactss">
                    <h3>Contacts</h3>
                    <ul>
                        
                        <li>
                            <img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Randila">
                            <span>Randila</span>
                        </li>
                        
                        
                    </ul>
                </div>

                <!-- Chat Section -->
                <div class="chat">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Sun Wukong">
                        <div class="chat-header-info">
                            <h2>Sun Wukong</h2>
                    
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="chat-messages"></div>

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
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
