<?php
class M_Notifications {
                  private $db;
    
                  public function __construct() {
                      $this->db = new Database;
                  }
    
                  // Create a new notification
                  public function createNotification($userId, $message, $isAnnouncement = false) {
                      $this->db->query('INSERT INTO notifications (user_id, message, is_announcement) 
                                        VALUES (:user_id, :message, :is_announcement)');
        
                      $this->db->bind(':user_id', $userId);
                      $this->db->bind(':message', $message);
                      $this->db->bind(':is_announcement', $isAnnouncement);
        
                      return $this->db->execute();
                  }
    
                  // Create an announcement (notification for all users)
                  public function createAnnouncement($message) {
                      $this->db->query('INSERT INTO notifications (user_id, message, is_announcement) 
                                        VALUES (0, :message, 1)');
        
                      $this->db->bind(':message', $message);
        
                      return $this->db->execute();
                  }
    
                  // Get notifications for a user (including announcements)
                  public function getNotifications($userId, $limit = 20) {
                      $this->db->query('(SELECT * FROM notifications 
                                        WHERE user_id = :user_id AND is_announcement = 0)
                                        UNION
                                        (SELECT * FROM notifications 
                                        WHERE is_announcement = 1)
                                        ORDER BY created_at DESC 
                                        LIMIT :limit');
        
                      $this->db->bind(':user_id', $userId);
                      $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
                      return $this->db->resultSet();
                  }
    
                  // Count unread notifications
                  public function countUnread($userId) {
                      $this->db->query('SELECT COUNT(*) as count FROM notifications 
                                        WHERE (user_id = :user_id OR is_announcement = 1)
                                        AND is_read = 0');
        
                      $this->db->bind(':user_id', $userId);
        
                      $result = $this->db->single();
                      return $result->count;
                  }
    
                  // Mark notification as read
                  public function markAsRead($notificationId, $userId) {
                      $this->db->query('UPDATE notifications 
                                        SET is_read = 1 
                                        WHERE id = :id AND (user_id = :user_id OR is_announcement = 1)');
        
                      $this->db->bind(':id', $notificationId);
                      $this->db->bind(':user_id', $userId);
        
                      return $this->db->execute();
                  }
    
                  // Mark all notifications as read
                  public function markAllAsRead($userId) {
                      $this->db->query('UPDATE notifications 
                                        SET is_read = 1 
                                        WHERE (user_id = :user_id OR is_announcement = 1) AND is_read = 0');
        
                      $this->db->bind(':user_id', $userId);
        
                      return $this->db->execute();
                  }
    
                  // Delete a notification
                  public function deleteNotification($notificationId, $userId) {
                      $this->db->query('DELETE FROM notifications 
                                        WHERE id = :id AND (user_id = :user_id OR (is_announcement = 1 AND user_id = 0))');
        
                      $this->db->bind(':id', $notificationId);
                      $this->db->bind(':user_id', $userId);
        
                      return $this->db->execute();
                  }
    
                  // Delete an announcement (admin only)
                  public function deleteAnnouncement($id) {
                      $this->db->query('DELETE FROM notifications WHERE id = :id AND is_announcement = 1');
                      $this->db->bind(':id', $id);
                      return $this->db->execute();
                  }
    
                  // Clean up old notifications
                  public function cleanupOldNotifications($daysToKeep = 30) {
                      $this->db->query('DELETE FROM notifications 
                                        WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)');
        
                      $this->db->bind(':days', $daysToKeep, PDO::PARAM_INT);
        
                      return $this->db->execute();
                  }
    
                  // Add this method to your M_Notifications model
                  public function getNotificationById($id) {
                      $this->db->query('SELECT * FROM notifications WHERE id = :id');
                      $this->db->bind(':id', $id);
                      return $this->db->single();
                  }
}
