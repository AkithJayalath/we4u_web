<?php 
class M_SessionChat {
    private $db;
 
    public function __construct() {
        $this->db = new Database();
        EncryptionHelper::init(ENCRYPTION_KEY);
    }

    public function getChatBySessionId($session_id) {
        $this->db->query("SELECT * FROM sessionchats WHERE session_id = :session_id");
        $this->db->bind(':session_id', $session_id);
        return $this->db->single();
    }
    
    public function createChat($session_id) {
        $this->db->query("INSERT INTO sessionchats (session_id) VALUES (:session_id)");
        $this->db->bind(':session_id', $session_id);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    public function saveMessage($chat_id, $sender_id, $message_text) {
        // Encrypt the message text before saving
        $encrypted_message = EncryptionHelper::encrypt($message_text);
        
        $this->db->query("INSERT INTO sessionchatmessages (chat_id, sender_id, message_text) 
                          VALUES (:chat_id, :sender_id, :message_text)");
        $this->db->bind(':chat_id', $chat_id);
        $this->db->bind(':sender_id', $sender_id);
        $this->db->bind(':message_text', $encrypted_message);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    public function getMessageById($message_id) {
        $this->db->query("SELECT cm.*, u.username, u.profile_picture 
                          FROM sessionchatmessages cm
                          JOIN user u ON cm.sender_id = u.user_id
                          WHERE cm.message_id = :message_id");
        $this->db->bind(':message_id', $message_id);
        $message = $this->db->single();
        
        if ($message) {
            // Decrypt the message text
            $message->message_text = EncryptionHelper::decrypt($message->message_text);
        }
        
        return $message;
    }
    
    public function getMessagesByChatId($chat_id) {
        $this->db->query("SELECT cm.*, u.username, u.profile_picture 
                          FROM sessionchatmessages cm
                          JOIN user u ON cm.sender_id = u.user_id
                          WHERE cm.chat_id = :chat_id
                          ORDER BY cm.created_at ASC");
        $this->db->bind(':chat_id', $chat_id);
        $messages = $this->db->resultSet();
        
        // Decrypt all messages
        foreach ($messages as $message) {
            $message->message_text = EncryptionHelper::decrypt($message->message_text);
        }
        
        return $messages;
    }
    
    public function getNewMessages($chat_id, $last_message_id) {
        $this->db->query("SELECT cm.*, u.username, u.profile_picture 
                          FROM sessionchatmessages cm
                          JOIN user u ON cm.sender_id = u.user_id
                          WHERE cm.chat_id = :chat_id AND cm.message_id > :last_message_id
                          ORDER BY cm.created_at ASC");
        $this->db->bind(':chat_id', $chat_id);
        $this->db->bind(':last_message_id', $last_message_id);
        $messages = $this->db->resultSet();
        
        // Decrypt all new messages
        foreach ($messages as $message) {
            $message->message_text = EncryptionHelper::decrypt($message->message_text);
        }
        
        return $messages;
    }
    
    public function getOrCreateChatForSession($session_id) {
        // Check if a chat already exists for this session
        $chat = $this->getChatBySessionId($session_id);
        
        if ($chat) {
            return $chat->chat_id;
        } else {
            // Create a new chat for this session
            return $this->createChat($session_id);
        }
    }
    
    public function getSessionById($session_id) {
        $this->db->query("SELECT * FROM consultantsessions WHERE session_id = :session_id");
        $this->db->bind(':session_id', $session_id);
        return $this->db->single();
    }
     
    public function getCareseekerById($id) {
        $this->db->query("SELECT c.*, u.username, u.profile_picture, u.gender, u.email, u.date_of_birth 
                        FROM careseeker c
                        JOIN user u ON c.careseeker_id = u.user_id
                        WHERE c.careseeker_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function getElderProfileById($elderId) {
        $this->db->query("
            SELECT 
                *, 
                CONCAT_WS(' ', first_name, middle_name, last_name) AS full_name 
            FROM elderprofile 
            WHERE elder_id = :elder_id
        ");
        $this->db->bind(':elder_id', $elderId);
        return $this->db->single();
    }

    public function getConsultantById($id) {
        $this->db->query("SELECT c.*, u.username, u.profile_picture, u.gender, u.email, u.date_of_birth 
                        FROM consultant c
                        JOIN user u ON c.consultant_id = u.user_id
                        WHERE c.consultant_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
?>