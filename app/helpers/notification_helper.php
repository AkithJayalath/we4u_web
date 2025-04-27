<?php
/**
 * Helper functions for notifications
 */

/**
 * Create a notification for a specific user
 * 
 *  int $userId The user ID to send the notification to
 *  string $message The notification message
 *  bool $isAnnouncement Whether this is an announcement (default: false)
 *  bool Success or failure
 */
function createNotification($userId, $message, $isAnnouncement = false) {
    // Get database instance
    $db = new Database;
    
    // Insert the notification
    $db->query('INSERT INTO notifications (user_id, message, is_announcement) 
                VALUES (:user_id, :message, :is_announcement)');
    
    $db->bind(':user_id', $userId);
    $db->bind(':message', $message);
    $db->bind(':is_announcement', $isAnnouncement);
    
    return $db->execute();
}

// For a regular user notification
// createNotification($userId, 'Your request has been approved', false);

// To create a notification for a specific user
// createNotification($userId, 'Your request has been approved');

// To create an announcement for all users
// if ($_SESSION['user_role'] == 'Admin') {
//     createNotification(0, 'System maintenance scheduled for tomorrow', true);
// }