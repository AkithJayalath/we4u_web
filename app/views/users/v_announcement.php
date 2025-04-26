<?php
$required_styles = [
    'user/announcements',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="announcement-container">
        
        <div class="announcement-list" id="announcement-list">
        <h2>Announcements</h2>
            <?php if(empty($data['announcements'])): ?>
                <div class="no-announcements">No announcements available</div>
            <?php else: ?>
                <?php foreach($data['announcements'] as $announcement): ?>
                    <div class="announcement-item">
                        <div class="announcement-icon">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div class="announcement-content">
                            <div class="announcement-title">
                                <a href="<?php echo URLROOT; ?>/users/viewannouncement/<?php echo $announcement->announcement_id; ?>">
                                    <?php echo $announcement->title; ?>
                                </a>
                            </div>
                            <div class="announcement-preview">
                                <?php echo substr($announcement->content, 0, 150) . '...'; ?>
                            </div>
                            <div class="announcement-meta">
                                <span class="announcement-date">
                                    <i class="fa-solid fa-calendar"></i>
                                    <?php echo date('F j, Y', strtotime($announcement->publish_date)); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?> 