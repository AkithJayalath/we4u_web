

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/caregiverProfile.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <i class="fa-solid fa-star <?php echo ($i <= $data['rating']) ? 'active' : ''; ?>"></i>
                                <?php endfor; ?>
                            </p>
                            <p><?php echo $data['age']; ?> Years</p>
                            <p><?php echo $data['profile']->gender; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="caregiver-personal-info-right">
                <div class="caregiver-personal-info-right-top">
                    <div class="personal-info-badge">I am a Caregiver</div>
                    <?php if($data['profile']->caregiver_type == 'both' || $data['profile']->caregiver_type == 'long') : ?>
                        <div class="personal-info-badge">Work Type Long-term</div>
                    <?php endif; ?>
                    <?php if($data['profile']->caregiver_type == 'both' || $data['profile']->caregiver_type == 'short') : ?>
                        <div class="personal-info-badge">Work Type Short-term</div>
                    <?php endif; ?>
                </div>
              
                <button class="caregiver-edit-button" onClick="navigateToDetails()"><i class="fas fa-edit"></i> Edit Profile</button>     
               
                
                
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
                                <p>Per Session (Short Term): Rs. <?= htmlspecialchars($data['profile']->price_per_session) ?></p>
                                <p>Per Day (Long Term): Rs. <?= htmlspecialchars($data['profile']->price_per_day) ?></p>
                            <?php elseif ($data['profile']->caregiver_type === 'short') : ?>
                                <p>Per Session (Short Term): Rs. <?= htmlspecialchars($data['profile']->price_per_session) ?></p>
                            <?php elseif ($data['profile']->caregiver_type === 'long') : ?>
                                <p>Per Day (Long Term): Rs. <?= htmlspecialchars($data['profile']->price_per_day) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other concerns -->
            
        </div>

        <div class="caregiver-other-info-section">
            <div class="caregiver-health-concern-section">
                <div class="caregiver-health-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Rating & reviews</h3>
                    </div>
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
    <!-- Container for the chat popup -->
<div id="chat-popup-container" class="hidden"></div>

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

<script>
    function navigateToDetails() {
        window.location.href = '<?php echo URLROOT; ?>/caregivers/editmyProfile';
    }

</script>

<?php require APPROOT . '/views/includes/footer.php' ?>