<?php
class Notifications extends Controller {
    private $notificationModel;
    
    public function __construct() {
        // Check if user is logged in for most methods
        if (!isset($_SESSION['user_id']) && 
            $_SERVER['REQUEST_URI'] != '/we4u/notifications/getUnreadCount') {
            redirect('users/login');
        }
        
        $this->notificationModel = $this->model('M_Notifications');
    }
    
    // Main notifications page
    public function index() {
        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getNotifications($userId);
        
        // Format time ago for each notification
        foreach ($notifications as &$notification) {
            $notification->time_ago = $this->getTimeAgo($notification->created_at);
        }
        
        $data = [
            'notifications' => $notifications
        ];
        
        $this->view('v_notifications', $data);
    }
    
    // Helper method to format time ago
    private function getTimeAgo($datetime) {
        $createdAt = new DateTime($datetime);
        $now = new DateTime();
        $interval = $createdAt->diff($now);
        
        if ($interval->d > 0) {
            return $interval->d . ' days ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hours ago';
        } elseif ($interval->i > 0) {
            return $interval->i . ' minutes ago';
        } else {
            return 'Just now';
        }
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
            redirect('users/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            if ($id) {
                // Check if this is an announcement - if so, don't mark it as read
                $notification = $this->notificationModel->getNotificationById($id);
                if ($notification && $notification->is_announcement) {
                    flash('notification_error', 'Announcements cannot be marked as read');
                    redirect('notifications');
                    return;
                }
                
                $success = $this->notificationModel->markAsRead($id, $userId);
            } else {
                // Mark all as read except announcements
                $success = $this->notificationModel->markAllAsRead($userId);
            }
            
            redirect('notifications');
        } else {
            redirect('notifications');
        }
    }
    
    // Delete a notification
    public function deleteNotification($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Get the notification
            $notification = $this->notificationModel->getNotificationById($id);
            
            // If notification doesn't exist or doesn't belong to this user, redirect
            if (!$notification || ($notification->user_id != $userId && !$notification->is_announcement)) {
                flash('notification_error', 'Notification not found');
                redirect('notifications');
                return;
            }
            
            // Check if this is an announcement - only admins can delete
            if ($notification->is_announcement) {
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'Admin') {
                    flash('notification_error', 'You do not have permission to delete announcements');
                    redirect('notifications');
                    return;
                }
                
                // Admin is deleting an announcement - delete it for all users
                $this->notificationModel->deleteAnnouncement($id);
                flash('notification_success', 'Announcement deleted successfully');
            } else {
                // Regular user deleting their own notification
                $this->notificationModel->deleteNotification($id, $userId);
                flash('notification_success', 'Notification deleted successfully');
            }
            
            redirect('notifications');
        } else {
            redirect('notifications');
        }
    }
    
    // Helper method to create a notification (to be called from other controllers)
    public function createNotification($userId, $message, $isAnnouncement = false) {
        return $this->notificationModel->createNotification($userId, $message, $isAnnouncement);
    }
    
    // Create an announcement (admin only)
    public function createAnnouncement() {
        // Check if user is admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'Admin') {
            redirect('pages/permissionerror');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $message = trim($_POST['message']);
            
            if (empty($message)) {
                flash('notification_error', 'Announcement message cannot be empty');
                redirect('admin/notifications');
            }
            
            if ($this->notificationModel->createAnnouncement($message)) {
                flash('notification_success', 'Announcement created successfully');
            } else {
                flash('notification_error', 'Failed to create announcement');
            }
            
            redirect('admin/notifications');
        } else {
            redirect('admin/notifications');
        }
    }
    
}
