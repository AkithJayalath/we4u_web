<?php
class M_CaregiverChat {
    private $db;

    public function __construct() {
        $this->db = new Database;
        EncryptionHelper::init(ENCRYPTION_KEY);
    }

     // Get request by ID 
     public function getRequestById($request_id) {
        $this->db->query('SELECT * FROM carerequests WHERE request_id = :request_id');
        $this->db->bind(':request_id', $request_id);
        return $this->db->single();
    }

    // Get elder profile by ID 
    public function getElderProfileById($elder_id) {
        $this->db->query('SELECT * FROM elderprofile WHERE elder_id = :elder_id');
        $this->db->bind(':elder_id', $elder_id);
        return $this->db->single();
    }

    // Get caregiver profile by ID 
    public function getCaregiverById($caregiver_id) {
        $this->db->query('SELECT u.*, c.* FROM user u 
                         JOIN caregiver c ON u.user_id = c.caregiver_id
                         WHERE u.user_id = :caregiver_id');
        $this->db->bind(':caregiver_id', $caregiver_id);
        return $this->db->single();
    }

    // Get careseeker profile by ID 
    public function getCareseekerById($careseeker_id) {
        $this->db->query('SELECT u.*, cs.* FROM user u 
                         JOIN careseeker cs ON u.user_id = cs.careseeker_id
                         WHERE u.user_id = :careseeker_id');
        $this->db->bind(':careseeker_id', $careseeker_id);
        return $this->db->single();
    }

    // Get or create chat for a request 
    public function getOrCreateChatForRequest($request_id) {
        // Check if chat already exists for this request
        $this->db->query('SELECT chat_id FROM caregiverchats WHERE request_id = :request_id');
        $this->db->bind(':request_id', $request_id);
        $result = $this->db->single();

        if ($result) {
            return $result->chat_id;
        }

        // If not, get request details to create a new chat
        $request = $this->getRequestById($request_id);
        
        // Create new chat
        $this->db->query('INSERT INTO caregiverchats (careseeker_id, caregiver_id, request_id, elder_id, created_at, updated_at) 
                         VALUES (:careseeker_id, :caregiver_id, :request_id, :elder_id, NOW(), NOW())');
        $this->db->bind(':careseeker_id', $request->requester_id);
        $this->db->bind(':caregiver_id', $request->caregiver_id);
        $this->db->bind(':request_id', $request_id);
        $this->db->bind(':elder_id', $request->elder_id);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    // Get messages by chat ID - with decryption
    public function getMessagesByChatId($chat_id) {
        $this->db->query('
            SELECT m.*, u.username, u.profile_picture 
            FROM caregiverchatmessages m
            JOIN user u ON m.sender_id = u.user_id
            WHERE m.chat_id = :chat_id
            ORDER BY m.created_at ASC
        ');
        $this->db->bind(':chat_id', $chat_id);
        $messages = $this->db->resultSet();
        
        // Decrypt message texts
        foreach ($messages as $message) {
            if ($message->message_text) {
                try {
                    $message->message_text = EncryptionHelper::decrypt($message->message_text);
                } catch (Exception $e) {
                    // In case of decryption error, leave as is
                    // You might want to log this error
                }
            }
        }
        
        return $messages;
    }

    // Save a new message - with encryption
    public function saveMessage($chat_id, $sender_id, $message_text) {
        // Encrypt the message text
        $encrypted_message = EncryptionHelper::encrypt($message_text);
        
        $this->db->query('
            INSERT INTO caregiverchatmessages (chat_id, sender_id, message_text, is_read, created_at) 
            VALUES (:chat_id, :sender_id, :message_text, 0, NOW())
        ');
        $this->db->bind(':chat_id', $chat_id);
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':message_text', $encrypted_message);
        
        if ($this->db->execute()) {
            // Update chat's updated_at timestamp
            $this->db->query('UPDATE caregiverchats SET updated_at = NOW() WHERE chat_id = :chat_id');
            $this->db->bind(':chat_id', $chat_id);
            $this->db->execute();
            
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    // Get a specific message by ID - with decryption
    public function getMessageById($message_id) {
        $this->db->query('
            SELECT m.*, u.username, u.profile_picture 
            FROM caregiverchatmessages m
            JOIN user u ON m.sender_id = u.user_id
            WHERE m.message_id = :message_id
        ');
        $this->db->bind(':message_id', $message_id);
        $message = $this->db->single();
        
        // Decrypt message text
        if ($message && $message->message_text) {
            try {
                $message->message_text = EncryptionHelper::decrypt($message->message_text);
            } catch (Exception $e) {
                // In case of decryption error, leave as is
                // You might want to log this error
            }
        }
        
        return $message;
    }

    // Get new messages since last check - with decryption
    public function getNewMessages($chat_id, $last_message_id) {
        $this->db->query('
            SELECT m.*, u.username, u.profile_picture 
            FROM caregiverchatmessages m
            JOIN user u ON m.sender_id = u.user_id
            WHERE m.chat_id = :chat_id AND m.message_id > :last_message_id
            ORDER BY m.created_at ASC
        ');
        $this->db->bind(':chat_id', $chat_id);
        $this->db->bind(':last_message_id', $last_message_id);
        $messages = $this->db->resultSet();
        
        // Decrypt message texts
        foreach ($messages as $message) {
            if ($message->message_text) {
                try {
                    $message->message_text = EncryptionHelper::decrypt($message->message_text);
                } catch (Exception $e) {
                    // In case of decryption error, leave as is
                    // You might want to log this error
                }
            }
        }
        
        return $messages;
    }

    // Mark messages as read 
    public function markMessagesAsRead($chat_id, $user_id) {
        $this->db->query('
            UPDATE caregiverchatmessages 
            SET is_read = 1
            WHERE chat_id = :chat_id AND sender_id != :user_id AND is_read = 0
        ');
        $this->db->bind(':chat_id', $chat_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }
}
?>