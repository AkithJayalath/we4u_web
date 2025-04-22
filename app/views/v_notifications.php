<?php
$required_styles = [
    'notifications',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="notification-container">
        <h2>Notifications</h2>
        <div class="notification-list" id="notification-list">
            <!-- Notifications will be loaded here -->
            <div class="loading-indicator">Loading notifications...</div>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    function loadNotifications() {
        const notificationList = document.getElementById('notification-list');
        
        // Show loading indicator
        notificationList.innerHTML = '<div class="loading-indicator">Loading notifications...</div>';
        
        // Fetch notifications from the server
        fetch('<?php echo URLROOT; ?>/notifications/getNotifications')
            .then(response => response.json())
            .then(notifications => {
                if (notifications.length === 0) {
                    notificationList.innerHTML = '<div class="no-notifications">You have no notifications</div>';
                    return;
                }
                
                // Clear loading indicator
                notificationList.innerHTML = '';
                
                // Add each notification to the list
                notifications.forEach(notification => {
                    const notificationItem = document.createElement('div');
                    notificationItem.className = `notification-item ${notification.is_read ? 'read' : 'unread'}`;
                    notificationItem.dataset.id = notification.id;
                    
                    notificationItem.innerHTML = `
                        <div class="notification-icon">
                            <i class="${notification.icon}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${notification.time_ago}</div>
                        </div>
                        <div class="notification-actions">
                            <button class="mark-read-btn" title="Mark as ${notification.is_read ? 'unread' : 'read'}">
                                <i class="fa-solid ${notification.is_read ? 'fa-envelope' : 'fa-envelope-open'}"></i>
                            </button>
                            <button class="delete-btn" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `;
                    
                    // Add click event to navigate to the related item
                    notificationItem.addEventListener('click', function(e) {
                        // Don't navigate if clicking on a button
                        if (e.target.closest('.notification-actions')) {
                            return;
                        }
                        
                        // Mark as read
                        if (!notification.is_read) {
                            markAsRead(notification.id);
                        }
                        
                        // Navigate to the action URL
                        window.location.href = notification.action;
                    });
                    
                    // Add click event for mark as read button
                    const markReadBtn = notificationItem.querySelector('.mark-read-btn');
                    markReadBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        markAsRead(notification.id);
                        
                        // Toggle read/unread state in UI
                        notificationItem.classList.toggle('read');
                        notificationItem.classList.toggle('unread');
                        
                        // Update button icon
                        const icon = this.querySelector('i');
                        if (icon.classList.contains('fa-envelope-open')) {
                            icon.classList.remove('fa-envelope-open');
                            icon.classList.add('fa-envelope');
                            this.title = 'Mark as unread';
                        } else {
                            icon.classList.remove('fa-envelope');
                            icon.classList.add('fa-envelope-open');
                            this.title = 'Mark as read';
                        }
                    });
                    
                    // Add click event for delete button
                    const deleteBtn = notificationItem.querySelector('.delete-btn');
                    deleteBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        deleteNotification(notification.id, notificationItem);
                    });
                    
                    notificationList.appendChild(notificationItem);
                });
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = '<div class="error-message">Failed to load notifications. Please try again later.</div>';
            });
    }
    
    function markAsRead(notificationId) {
        fetch(`<?php echo URLROOT; ?>/notifications/markAsRead/${notificationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to mark notification as read');
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }
    
    function deleteNotification(notificationId, notificationElement) {
        if (!confirm('Are you sure you want to delete this notification?')) {
            return;
        }
        
        fetch(`<?php echo URLROOT; ?>/notifications/deleteNotification/${notificationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the notification from the UI
                notificationElement.remove();
                
                // If no notifications left, show message
                if (document.querySelectorAll('.notification-item').length === 0) {
                    document.getElementById('notification-list').innerHTML = 
                        '<div class="no-notifications">You have no notifications</div>';
                }
            } else {
                console.error('Failed to delete notification');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
        });
    }
});
</script>
