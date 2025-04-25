<?php
$required_styles = [
    'careseeker/viewCaregiverProfile',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="view-caregiver-profile">
        <!-- Personal info section -->
        <div class="caregiver-personal-info-section">
            <div class="caregiver-personal-info-left">
                <div class="caregiver-personal-info-left-left">
                    <img src="<?php echo !empty($data['profile']->profile_picture) ? URLROOT . '/images/profile_imgs/' . $data['profile']->profile_picture : URLROOT . '/images/def_profile_pic.jpg'; ?>" alt="Profile Image" class="pro-img" />
                </div>
                <div class="caregiver-personal-info-left-right">
                    <div class="caregiver-personal-info-profile">
                        <div class="caregiver-personal-info-details">
                            <span class="caregiver-personal-info-tag">Verified</span>
                            <h2><?php echo $data['profile']->username; ?></h2>
                            <span class="caregiver-email"><?php echo $data['profile']->email; ?></span>
                            <p class="consultant-rating">
                                <?php 
                                $rating = $data['rating'];
                                for ($i = 1; $i <= 5; $i++) : 
                                    if ($i <= floor($rating)) {
                                        echo '<i class="fa-solid fa-star active"></i>';
                                    } elseif ($i - 0.5 <= $rating) {
                                        echo '<i class="fa-solid fa-star-half-stroke active"></i>';
                                    } else {
                                        echo '<i class="fa-regular fa-star"></i>';
                                    }
                                endfor; 
                                ?>
                                <span class="rating-value">(<?php echo number_format($data['rating'], 1); ?>)</span>
                            </p>
                            <p><?php echo $data['age']; ?> Years</p>
                            <p><?php echo $data['profile']->gender; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="caregiver-personal-info-right">
                <button class="caregiver-chat-button">
                    <i class="fas fa-comments"></i> Chat
                </button>
                <button class="caregiver-send-button" onclick="window.location.href='<?= URLROOT ?>/careseeker/showCaregiverRequestForm/<?= $data['profile']->caregiver_id ?>'">
                    <i class="fas fa-paper-plane"></i> Send Request
                </button>

            </div>
        </div>

        <!-- other info section -->
        <div class="caregiver-other-info-section">
            <div class="caregiver-other-concern-section">
                <div class="caregiver-other-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Bio</h3>
                    </div>
                </div>
                <div class="caregiver-other-concern-section-content">
                    <p><?php echo $data['profile']->bio; ?></p>
                </div>
            </div>

            <!-- Health concerns -->
            <div class="caregiver-health-concern-section">
                <div class="caregiver-health-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Profile</h3>
                    </div>
                </div>
                <div class="caregiver-health-concern-section-content">
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-graduation-cap icon"></i>
                        <div>
                            <h4>Qualifications</h4>
                            <?php
                            $qualification = explode(',', $data['profile']->qualification);
                            foreach ($qualification as $qualification) : ?>
                                <p><?php echo trim($qualification); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-user-md icon"></i>
                        <div>
                            <h4>Specializations</h4>
                            <?php
                            $specialty = explode(',', $data['profile']->specialty);
                            foreach ($specialty as $specialty) : ?>
                                <p><?php echo trim($specialty); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-map-marker-alt icon"></i>
                        <div>
                            <h4>Regions Available</h4>
                            <?php
                            $available_region = explode(',', $data['profile']->available_region);
                            foreach ($available_region as $available_region) : ?>
                                <p><?php echo trim($available_region); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-hands-helping icon"></i>
                        <div>
                            <h4>Special Skills</h4>
                            <?php
                            function formatSkillName($skill)
                            {
                                return ucwords(str_replace('_', ' ', $skill));
                            }

                            $skills = explode(',', $data['profile']->skills);
                            foreach ($skills as $skill) : ?>
                                <p><?php echo trim(formatSkillName($skill)); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-dollar-sign icon"></i>
                        <div>
                            <h4>Payment details</h4>
                            <?php if ($data['profile']->caregiver_type === 'both') : ?>
                                <p>Per Session (Short Term): Rs. <?= isset($data['profile']->price_per_session) ? htmlspecialchars($data['profile']->price_per_session) : 'Not specified' ?></p>
                                <p>Per Day (Long Term): Rs. <?= isset($data['profile']->price_per_day) ? htmlspecialchars($data['profile']->price_per_day) : 'Not specified' ?></p>
                            <?php elseif ($data['profile']->caregiver_type === 'short') : ?>
                                <p>Per Session (Short Term): Rs. <?= isset($data['profile']->price_per_session) ? htmlspecialchars($data['profile']->price_per_session) : 'Not specified' ?></p>
                            <?php elseif ($data['profile']->caregiver_type === 'long') : ?>
                                <p>Per Day (Long Term): Rs. <?= isset($data['profile']->price_per_day) ? htmlspecialchars($data['profile']->price_per_day) : 'Not specified' ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other concerns -->
            <div class="caregiver-other-concern-section">
                <div class="caregiver-other-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Availability</h3>
                    </div>
                </div>
                <div class="caregiver-other-concern-section-content">
                    <!-- Availability content here -->
                </div>
            </div>
        </div>

        <div class="caregiver-other-info-section">
            <div class="caregiver-health-concern-section">
                <div class="caregiver-health-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Rating & reviews</h3>
                    </div>
                    <button class="caregiver-review-button" onclick="window.location.href='<?= URLROOT ?>/careseeker/viewCaregiverReviews/<?= $data['profile']->caregiver_id ?>'">
                        Review
                    </button>
                </div>
                <div class="rating-section-content">
                    <!-- ratings.js -->
                </div>
                <div class="reviews-section-content">
                    <!-- reviews.js -->
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<script src="<?php echo URLROOT; ?>/js/rating.js"></script>
<script src="<?php echo URLROOT; ?>/js/reviews.js"></script>

<script>
    const reviewData = {
        rating: <?php echo json_encode($data['rating']); ?>,
        reviews: <?php echo json_encode($data['reviews']); ?>
    };

    addRatingsAndReviews(reviewData);
</script>

<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const reviewsData = <?php echo json_encode($data['reviews']); ?>;
    addReviewsForConsultant(reviewsData);
</script>

<?php require APPROOT . '/views/includes/footer.php' ?>