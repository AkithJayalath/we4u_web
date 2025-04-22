<?php
class Notifications extends Controller {
    private $notificationModel;
    
    // Notification type definitions with messages and icons
    private $notificationTypes = [
        'care_request_new' => [
            'message' => '{requester_name} sent a caregiver request to you.',
            'icon' => 'fa-solid fa-user-plus',
            'action' => 'caregivers/viewreqinfo/{related_id}'
        ],
        'care_request_accepted' => [
            'message' => '{caregiver_name} accepted your caregiving request.',
            'icon' => 'fa-solid fa-circle-check',
            'action' => 'careseeker/viewRequests'
        ],
        'care_request_rejected' => [
            'message' => '{caregiver_name} rejected your caregiving request.',
            'icon' => 'fa-solid fa-circle-xmark',
            'action' => 'careseeker/viewRequests'
        ],
        'schedule_change' => [
            'message' => '{requester_name} requested a schedule change.',
            'icon' => 'fa-solid fa-bell',
            'action' => 'caregivers/viewMyCalendar'
        ],
        'new_review' => [
            'message' => '{reviewer_name} reviewed your profile.',
            'icon' => 'fa-solid fa-comment-dots',
            'action' => 'caregivers/rateandreview'
        ],
        'payment_received' => [
            'message' => '{payer_name} paid Rs.{amount}. View invoice.',
            'icon' => 'fa-regular fa-credit-card',
            'action' => 'caregivers/paymentHistory'
        ],
        'medical_update' => [
            'message' => '{doctor_name} added new medical instructions to your profile.',
            'icon' => 'fa-solid fa-file-medical',
            'action' => 'careseeker/viewElderProfile/{related_id}'
        ]
    ];
    
    public function __construct() {
        // Check if user is logged in for most methods
        if (!isset($_SESSION['user_id']) && 
            $_SERVER['REQUEST_URI'] != '/we4u/notifications/getUnreadCount' && 
            $_SERVER['REQUEST_URI'] != '/we4u/notifications/getNotifications') {
            redirect('users/login');
        }
        
        $this->notificationModel = $this->model('M_Notifications');
    }
    
    // Main notifications page
    public function index() {
        $data = [];
        $this->view('v_notifications', $data);
    }
    
    // API: Get notifications for the current user
    public function getNotifications() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Not logged in']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $rawNotifications = $this->notificationModel->getNotifications($userId);
        
        // Process notifications to include message, icon, and action URL
        $notifications = [];
        foreach ($rawNotifications as $notification) {  
            // Skip if notification type is not defined 
            if (!isset($this->notificationTypes[$notification->notification_type])) {
                continue;
            }
            
            $typeInfo = $this->notificationTypes[$notification->notification_type];
            
            // Get related entity details if needed
            $entityDetails = null;
            if ($notification->related_id) {
                $baseType = explode('_', $notification->notification_type)[0];
                $entityDetails = $this->notificationModel->getRelatedEntityDetails($baseType, $notification->related_id);
            }
            
            // Format message with dynamic parameters
            $message = $typeInfo['message'];
            if ($entityDetails) {
                foreach ((array)$entityDetails as $key => $value) {
                    $message = str_replace('{'.$key.'}', $value, $message);
                }
            }
            
            // Format action URL
            $action = $typeInfo['action'];
            if ($notification->related_id) {
                $action = str_replace('{related_id}', $notification->related_id, $action);
            }
            
            // Format time ago
            $createdAt = new DateTime($notification->created_at);
            $now = new DateTime();
            $interval = $createdAt->diff($now);
            
            $timeAgo = '';
            if ($interval->d > 0) {
                $timeAgo = $interval->d . ' days ago';
            } elseif ($interval->h > 0) {
                $timeAgo = $interval->h . ' hours ago';
            } elseif ($interval->i > 0) {
                $timeAgo = $interval->i . ' minutes ago';
            } else {
                $timeAgo = 'Just now';
            }
            
            // Build notification object
            $notifications[] = [
                'id' => $notification->id,
                'message' => $message,
                'icon' => $typeInfo['icon'],
                'action' => URLROOT . '/' . $action,
                'is_read' => (bool)$notification->is_read,
                'created_at' => $notification->created_at,
                'time_ago' => $timeAgo
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($notifications);
    }
    
    // API: Get unread count for the current user
    public function getUnreadCount() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['count' => 0]);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $count = $this->notificationModel->countUnread($userId);
        
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
    }
    
    // API: Mark a notification as read
    public function markAsRead($id = null) {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            if ($id) {
                $success = $this->notificationModel->markAsRead($id, $userId);
            } else {
                $success = $this->notificationModel->markAllAsRead($userId);
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
        }
    }
    
    // API: Delete a notification
    public function deleteNotification($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $success = $this->notificationModel->deleteNotification($id, $userId);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
        }
    }
    
    // Helper method to create a notification (to be called from other controllers)
    public function createNotification($userId, $type, $relatedId = null) {
        return $this->notificationModel->createNotification($userId, $type, $relatedId);
    }
    
    // Cron job to clean up expired notifications
    public function cleanup() {
        // This should be called via a cron job
        $this->notificationModel->cleanupExpiredNotifications();
        echo "Cleanup completed";
    }
}
