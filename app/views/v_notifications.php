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
        
        <?php if(!empty($data['notifications'])): ?>
            <div class="notification-actions-all">
                <form action="<?php echo URLROOT; ?>/notifications/markAsRead" method="POST">
                    <button type="submit" class="mark-all-read-btn">Mark All as Read</button>
                </form>
            </div>
        <?php endif; ?>
        
        <div class="notification-list" id="notification-list">
            <?php if(empty($data['notifications'])): ?>
                <div class="no-notifications">You have no notifications</div>
            <?php else: ?>
                <?php foreach($data['notifications'] as $notification): ?>
                    <div class="notification-item <?php echo (!$notification->is_announcement && $notification->is_read) ? 'read' : 'unread'; ?>">
                        <div class="notification-icon">
                            <i class="<?php echo $notification->is_announcement ? 'fa-solid fa-bullhorn' : 'fa-solid fa-bell'; ?>"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-message">
                                <?php echo $notification->message; ?>
                                <?php if($notification->is_announcement): ?>
                                    <span class="announcement-badge">Announcement</span>
                                <?php endif; ?>
                            </div>
                            <div class="notification-time"><?php echo $notification->time_ago; ?></div>
                        </div>
                        <div class="notification-actions">
                            <?php if(!$notification->is_announcement): ?>
                                <!-- Regular notification actions -->
                                <form action="<?php echo URLROOT; ?>/notifications/markAsRead/<?php echo $notification->id; ?>" method="POST" style="display:inline;">
                                    <button class="mark-read-btn" title="Mark as <?php echo $notification->is_read ? 'unread' : 'read'; ?>">
                                        <i class="fa-solid <?php echo $notification->is_read ? 'fa-envelope' : 'fa-envelope-open'; ?>"></i>
                                    </button>
                                </form>
                                <form action="<?php echo URLROOT; ?>/notifications/deleteNotification/<?php echo $notification->id; ?>" method="POST" style="display:inline;"">
                                    <button class="delete-btn" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            <?php elseif(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Admin'): ?>
                                <!-- Only admins can delete announcements -->
                                <form action="<?php echo URLROOT; ?>/notifications/deleteNotification/<?php echo $notification->id; ?>" method="POST" style="display:inline;">
                                    <button class="delete-btn" title="Delete Announcement">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- For announcements, non-admin users see no action buttons -->
                                <div class="announcement-info" title="System Announcement">
                                    <i class="fa-solid fa-info-circle"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>
