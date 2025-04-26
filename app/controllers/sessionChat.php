<?php 
class sessionChat extends controller{
    private $sessionChatModel;

    public function __construct()
    {
        if (!$_SESSION['user_id']) {
            redirect('users/login');
        } else {
            if ($_SESSION['user_role'] != 'Careseeker' && $_SESSION['user_role'] != 'Consultant') {
                redirect('pages/permissonerror');
            }  
        }
        $this->sessionChatModel = $this->model('M_SessionChat');
       
    }

    // for chat


public function getChatData($session_id) {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        return;
    }
    
    // Get session information
    $session = $this->sessionChatModel->getSessionById($session_id);
    
    if (!$session) {
        echo json_encode(['status' => 'error', 'message' => 'Session not found']);
        return;
    }
    
    // Check if user has access to this session (either consultant or careseeker)
    if ($_SESSION['user_id'] != $session->consultant_id && $_SESSION['user_id'] != $session->careseeker_id) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
        return;
    }
    
    // Get elder profile information
    $elder = $this->sessionChatModel->getElderProfileById($session->elder_id);

    //Get consultant profile information
    $consultant = $this->sessionChatModel->getConsultantById($session->consultant_id);
    //Get careseeker profile information
    $careseeker = $this->sessionChatModel->getCareseekerById($session->careseeker_id);
    
    // Get or create chat for this session
    $chat_id = $this->sessionChatModel->getOrCreateChatForSession($session_id);
    
    // Get chat messages
    $messages = $this->sessionChatModel->getMessagesByChatId($chat_id);
    
    // Prepare data for the view
    $data = [
        'consultant' => $consultant,
        'careseeker' => $careseeker,
        'session' => $session,
        'elder' => $elder,
        'messages' => $messages,
        'chat_id' => $chat_id,
        'user_id' => $_SESSION['user_id']
    ];
    
    // Determine which view to load based on user role
    $view_path = 'consultant/v_chatPopup'; // Default
    
    // If the current user is the careseeker, load the careseeker view
    if ($_SESSION['user_id'] == $session->careseeker_id) {
        $view_path = 'careseeker/v_chatPopup';
    }
    
    // Load the appropriate chat partial view
    ob_start();
    $this->view($view_path, $data, true);
    $html = ob_get_clean();
    
    echo json_encode([
        'status' => 'success',
        'html' => $html
    ]);
}

// Function to send a message
public function sendMessage() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        redirect('pages/error');
    }
    
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        return;
    }
    
    $chat_id = $_POST['chat_id'];
    $message_text = trim($_POST['message']);
    $sender_id = $_SESSION['user_id'];
    
    if (empty($message_text)) {
        echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty']);
        return;
    }
    
    // Save the message
    $message_id = $this->sessionChatModel->saveMessage($chat_id, $sender_id, $message_text);
    
    if ($message_id) {
        // Get the saved message with user details
        $message = $this->sessionChatModel->getMessageById($message_id);
        
        echo json_encode([
            'status' => 'success',
            'message' => $message
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }
}

// Function to get new messages since last check
public function getNewMessages() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        redirect('pages/error');
    }
    
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        return;
    }
    
    $chat_id = $_POST['chat_id'];
    $last_message_id = $_POST['last_message_id'];
    
    $messages = $this->sessionChatModel->getNewMessages($chat_id, $last_message_id);
    
    echo json_encode([
        'status' => 'success',
        'messages' => $messages
    ]);
}


//Controller method to get or create a chat ID without loading the full chat
public function getOrCreateChatId($session_id) {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        return;
    }
    
    // Get session information
    $session = $this->sessionChatModel->getSessionById($session_id);
    
    if (!$session) {
        echo json_encode(['status' => 'error', 'message' => 'Session not found']);
        return;
    }
    
    // Check if user has access to this session (either consultant or careseeker)
    if ($_SESSION['user_id'] != $session->consultant_id && $_SESSION['user_id'] != $session->careseeker_id) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
        return;
    }
    
    // Get or create chat for this session
    $chat_id = $this->sessionChatModel->getOrCreateChatForSession($session_id);
    
    echo json_encode([
        'status' => 'success',
        'chat_id' => $chat_id
    ]);
}


// Function to get all messages for a chat
public function getAllMessages() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        redirect('pages/error');
    }
    
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        return;
    }
    
    $chat_id = $_POST['chat_id'];
    
    $messages = $this->sessionChatModel->getMessagesByChatId($chat_id);
    
    echo json_encode([
        'status' => 'success',
        'messages' => $messages
    ]);
}

}
?>