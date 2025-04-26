<?php 
class caregiverChat extends controller {
    private $caregiverChatModel;

    public function __construct()
    {
        if (!$_SESSION['user_id']) {
            redirect('users/login');
        } else {
            if ($_SESSION['user_role'] != 'Careseeker' && $_SESSION['user_role'] != 'Caregiver') {
                redirect('pages/permissonerror');
            }  
        }
        $this->caregiverChatModel = $this->model('M_CaregiverChat');
    }

    // Get or create chat data for a request
    public function getChatData($request_id) {
        // Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        // Get request information
        $request = $this->caregiverChatModel->getRequestById($request_id);
       
        
        if (!$request) {
            echo json_encode(['status' => 'error', 'message' => 'Request not found']);
            return;
        }
        
        // Check if user has access to this request (either caregiver or careseeker)
        if ($_SESSION['user_id'] != $request->caregiver_id && $_SESSION['user_id'] != $request->requester_id) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
            return;
        }
        
        // Get elder profile information
        $elder = $this->caregiverChatModel->getElderProfileById($request->elder_id);

        // Get caregiver profile information
        $caregiver = $this->caregiverChatModel->getCaregiverById($request->caregiver_id);
        
        // Get careseeker profile information
        $careseeker = $this->caregiverChatModel->getCareseekerById($request->requester_id);
        
        // Get or create chat for this request
        $chat_id = $this->caregiverChatModel->getOrCreateChatForRequest($request_id);
        
        // Get chat messages
        $messages = $this->caregiverChatModel->getMessagesByChatId($chat_id);
        
        // Prepare data for the view
        $data = [
            'caregiver' => $caregiver,
            'careseeker' => $careseeker,
            'request' => $request,
            'elder' => $elder,
            'messages' => $messages,
            'chat_id' => $chat_id,
            'user_id' => $_SESSION['user_id']
        ];
        
        // Determine which view to load based on user role
        $view_path = 'caregiver/v_caregiverChatPopup'; // Default
        
        // If the current user is the careseeker, load the careseeker view
        if ($_SESSION['user_id'] == $request->requester_id) {
            $view_path = 'careseeker/v_caregiverChatPopup';
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
        $message_id = $this->caregiverChatModel->saveMessage($chat_id, $sender_id, $message_text);
        
        if ($message_id) {
            // Get the saved message with user details
            $message = $this->caregiverChatModel->getMessageById($message_id);
            
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
        
        $messages = $this->caregiverChatModel->getNewMessages($chat_id, $last_message_id);
        
        echo json_encode([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    // Controller method to get or create a chat ID without loading the full chat
    public function getOrCreateChatId($request_id) {
        // Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        // Get request information
        $request = $this->caregiverChatModel->getRequestById($request_id);
        
        if (!$request) {
            echo json_encode(['status' => 'error', 'message' => 'Request not found']);
            return;
        }
        
        // Check if user has access to this request (either caregiver or careseeker)
        if ($_SESSION['user_id'] != $request->caregiver_id && $_SESSION['user_id'] != $request->requester_id) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
            return;
        }
        
        // Get or create chat for this request
        $chat_id = $this->caregiverChatModel->getOrCreateChatForRequest($request_id);
        
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
        
        $messages = $this->caregiverChatModel->getMessagesByChatId($chat_id);
        
        echo json_encode([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    // Mark messages as read
    public function markMessagesAsRead() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('pages/error');
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $chat_id = $_POST['chat_id'];
        $user_id = $_SESSION['user_id'];
        
        $success = $this->caregiverChatModel->markMessagesAsRead($chat_id, $user_id);
        
        echo json_encode([
            'status' => $success ? 'success' : 'error'
        ]);
    }
}
?>