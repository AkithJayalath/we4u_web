<?php
class M_Notifications {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Create a new notification
    public function createNotification($userId, $type, $relatedId = null) {
        $this->db->query('INSERT INTO notifications (user_id, notification_type, related_id) 
                          VALUES (:user_id, :notification_type, :related_id)');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':notification_type', $type);
        $this->db->bind(':related_id', $relatedId);
        
        return $this->db->execute();
    }
    
    // Get notifications for a user
    public function getNotifications($userId, $limit = 10) {
        $this->db->query('SELECT * FROM notifications 
                          WHERE user_id = :user_id 
                          ORDER BY created_at DESC 
                          LIMIT :limit');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Count unread notifications
    public function countUnread($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM notifications 
                          WHERE user_id = :user_id AND is_read = 0');
        
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        return $result->count;
    }
    
    // Mark notification as read
    public function markAsRead($notificationId, $userId) {
        $this->db->query('UPDATE notifications 
                          SET is_read = 1 
                          WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Mark all notifications as read
    public function markAllAsRead($userId) {
        $this->db->query('UPDATE notifications 
                          SET is_read = 1 
                          WHERE user_id = :user_id AND is_read = 0');
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Delete a notification
    public function deleteNotification($notificationId, $userId) {
        $this->db->query('DELETE FROM notifications 
                          WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Clean up expired notifications
    public function cleanupExpiredNotifications() {
        $this->db->query('DELETE FROM notifications WHERE expiry_date < NOW()');
        return $this->db->execute();
    }
    
    // Get related entity details based on notification type and related ID
    public function getRelatedEntityDetails($type, $relatedId) {
        switch ($type) {
            case 'care_request':
                return $this->getCareRequestDetails($relatedId);
            
            case 'payment':
                return $this->getPaymentDetails($relatedId);
            
            case 'review':
                return $this->getReviewDetails($relatedId);
            
            case 'appointment':
                return $this->getAppointmentDetails($relatedId);
            
            default:
                return null;
        }
    }
    
    // Get care request details
    private function getCareRequestDetails($requestId) {
        $this->db->query('SELECT cr.*, 
                          u_requester.username as requester_name, 
                          u_caregiver.username as caregiver_name
                          FROM carerequests cr
                          JOIN user u_requester ON cr.requester_id = u_requester.user_id
                          JOIN user u_caregiver ON cr.caregiver_id = u_caregiver.user_id
                          WHERE cr.request_id = :request_id');
        
        $this->db->bind(':request_id', $requestId);
        return $this->db->single();
    }
    
    // Get payment details
    private function getPaymentDetails($paymentId) {
        // Implement based on your payment table structure
        $this->db->query('SELECT * FROM payments WHERE payment_id = :payment_id');
        $this->db->bind(':payment_id', $paymentId);
        return $this->db->single();
    }
    
    // Get review details
    private function getReviewDetails($reviewId) {
        $this->db->query('SELECT r.*, u.username as reviewer_name
                          FROM review r
                          JOIN user u ON r.reviewer_id = u.user_id
                          WHERE r.review_id = :review_id');
        
        $this->db->bind(':review_id', $reviewId);
        return $this->db->single();
    }
    
    // Get appointment details
    private function getAppointmentDetails($appointmentId) {
        // Implement based on your appointment table structure
        // This is a placeholder
        $this->db->query('SELECT * FROM appointments WHERE appointment_id = :appointment_id');
        $this->db->bind(':appointment_id', $appointmentId);
        return $this->db->single();
    }
}