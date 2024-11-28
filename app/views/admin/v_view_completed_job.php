<?php
$required_styles = [
    'admin/viewCompletedJob',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <div class="job-info">
        <div class="job-info-heading">
            <p>Completed Job Details</p>
        </div>

        <div class="job-info-header">
            <div class="job-info-header-left">
                <div class="job-info-header-left-left">
                    <div class="job-info-circle image1"><img src="<?php echo URLROOT; ?>/public/images/def_profile_pic.jpg" alt="Service Provider" /></div>
                    <div class="job-info-circle image1"><img src="<?php echo URLROOT; ?>/public/images/image1.jpg" alt="Careseeker" /></div>
                </div>
                <div class="job-info-header-left-right">
                    <div class="job-info-profile">
                        <div class="job-info-details">
                            <h2>Job ID: #<?php echo $data['job_id']; ?></h2>
                            <h3>Service Type: <?php echo $data['service_type']; ?></h3>
                            <span class="tag completed">Completed</span>
                        </div>
                        <div class="job-info-details">
                            <h3>Service Provider: <?php echo $data['provider_name']; ?></h3>
                            <p>Provider ID: #<?php echo $data['provider_id']; ?></p>
                            <h3>Careseeker: <?php echo $data['careseeker_name']; ?></h3>
                            <p>Careseeker ID: #<?php echo $data['careseeker_id']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="job-infos-section">
            <div class="info-category">
                <h3>Job Timeline</h3>
                <div class="timeline-info">
                    <p>Started: <?php echo $data['start_date']; ?></p>
                    <p>Completed: <?php echo $data['end_date']; ?></p>
                    <p>Duration: <?php echo $data['duration']; ?></p>
                </div>
            </div>

            <div class="info-category">
                <h3>Service Details</h3>
                <div class="service-details">
                    <p>Service Type: <?php echo $data['service_type']; ?></p>
                    <p>Location: <?php echo $data['location']; ?></p>
                    <p>Payment Status: <?php echo $data['payment_status']; ?></p>
                </div>
            </div>

            <div class="info-category">
                <h3>Comments and Feedback</h3>
                <div class="comments-section">
                    <div class="comment-item">
                        <p class="comment-author">Service Provider Comment:</p>
                        <p class="comment-text"><?php echo $data['provider_comment']; ?></p>
                    </div>
                    <div class="comment-item">
                        <p class="comment-author">Careseeker Comment:</p>
                        <p class="comment-text"><?php echo $data['careseeker_comment']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<?php require APPROOT . '/views/includes/footer.php' ?>
