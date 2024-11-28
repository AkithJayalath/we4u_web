<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/notifications.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="notification-container">
    <h2>Notifications</h2>
    <div class="notification-list">
        <div class="notification">
            <div class="notification-icon">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="notification-content">
                <p><strong>John Doe</strong> sent a caregiver request to you.</p>
                <span class="notification-date">Today, 10:30 AM</span>
            </div>
            <div class="notification-actions">
                <button class="btn-view" onclick="navigateToReq()">View</button>
                <div class="delete"><i class="fa-solid fa-trash-can"></i></div>
                
            </div>
            
        </div>

        <div class="notification">
            <div class="notification-icon">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="notification-content">
                <p><strong>Mary Smith</strong> requested a schedule change.</p>
                <span class="notification-date">Yesterday, 4:15 PM</span>
            </div>
            <div class="notification-actions">
                <button class="btn-view">View</button>
                <div class="delete"><i class="fa-solid fa-trash-can"></i></div>
            </div>
        </div>

        <div class="notification">
            <div class="notification-icon">
                <i class="fa-solid fa-comment-dots"></i>
            </div>
            <div class="notification-content">
                <p><strong>Alex Johnson</strong> review your profile.</p>
                <span class="notification-date">2 days ago</span>
            </div>
            <div class="notification-actions">
                <button class="btn-view">View</button>
                <div class="delete"><i class="fa-solid fa-trash-can"></i></div>
            </div>
        </div>

        <div class="notification">
            <div class="notification-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="notification-content">
                <p><strong>Nadun Hasalanka</strong> accepted your cargiving request.</p>
                <span class="notification-date">2 days ago</span>
            </div>
            <div class="notification-actions">
                <button class="btn-view">View</button>
                <div class="delete"><i class="fa-solid fa-trash-can"></i></div>
            </div>
        </div>

        <div class="notification">
            <div class="notification-icon">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div class="notification-content">
                <p><strong>Nadun Hasalanka</strong> rejected your cargiving request.</p>
                <span class="notification-date">3 days ago</span>
            </div>
            <div class="notification-actions">
                <button class="btn-view">View</button>
                <div class="delete"><i class="fa-solid fa-trash-can"></i></div>
            </div>
        </div>

        <div class="notification">
            <div class="notification-icon">
            <i class="fa-solid fa-file-medical"></i>
            </div>
            <div class="notification-content">
                <p><strong>Dr. Safran Zahim</strong> added new medical instructions to your profile.</p>
                <span class="notification-date">3 days ago</span>
            </div>
            <div class="notification-actions">
                <button class="btn-view">View</button>
                <div class="delete"><i class="fa-solid fa-trash-can"></i></div>
            </div>
        </div>

        <div class="notification">
            <div class="notification-icon">
            <i class="fa-regular fa-credit-card"></i>
            </div>
            <div class="notification-content">
                <p><strong>Nadun Kasalanka</strong> paid Rs.2000.View invoice.</p>
                <span class="notification-date">4 days ago</span>
            </div>
            <div class="notification-actions">
                <button class="btn-view">View</button>
                <div class="delete"><i class="fa-solid fa-trash-can"></i></div>
            </div>
        </div>
    </div>
</div>

    
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>

<script>
    function navigateToReq() {
    window.location.href = '<?php echo URLROOT; ?>/caregivers/viewreqinfo';
}


</script>
