<?php
$required_styles = [
    'user/view_announcement',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="view-announcement-container">
        <div class="announcement-header">
            <h2><?php echo $data['announcement']->title; ?></h2>
            <div class="announcement-meta">
                <span class="announcement-date">
                    <i class="fa-solid fa-calendar"></i>
                    <?php echo date('F j, Y', strtotime($data['announcement']->publish_date)); ?>
                </span>
                <div class="announcement-content">
            <?php echo nl2br($data['announcement']->content); ?>
        </div>

            </div>
        </div>

        <div class="back-button">
            <a href="<?php echo URLROOT; ?>/users/announcements" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Announcements
            </a>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?> 