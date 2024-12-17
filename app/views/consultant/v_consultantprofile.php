<?php
$required_styles = [
    'consultant/viewConsultantProfile',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="view-consultant-profile">
        <!-- Personal info section -->
        <div class="consultant-personal-info-section">
            <div class="consultant-personal-info-left">
                <div class="consultant-personal-info-left-left">
                    <img src="https://media.istockphoto.com/id/1371009338/photo/portrait-of-confident-a-young-dentist-working-in-his-consulting-room.jpg?s=612x612&w=0&k=20&c=I212vN7lPpAOwGKRoEY9kYWunJaMj9vH2g-8YBGc2MI=" alt="Profile" class="consultant-personal-info-pic" />
                </div>
                <div class="consultant-personal-info-left-right">
                    <div class="consultant-personal-info-profile">

                        <div class="consultant-personal-info-details">
                            <span class="consultant-personal-info-tag">Verfied</span>
                            <h2>Dr.Amal Perera</h2>
                            <span class="consultant-email">consultant@gmail.com</span>
                            <p class="consultant-rating">
                                <span class="rating-stars" id="rating-stars"></span>
                            </p>
                            <p>38 years</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="consultant-personal-info-right">
            <button class="consultant-rate-button" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/rateandreview'">
                Ratings and Reviews
            </button>
                <button class="consultant-send-button">
                    <!-- send=delete red -->
                     Delete Profile
                </button>
                <button class="consultant-chat-button"> 
                    <!-- chat=edit green  -->
                     Edit Profile
                </button>
            </div>

        </div>

        <!-- other info section -->
        <div class="consultant-other-info-section">
            <!-- Health concerns -->
            <div class="consultant-health-concern-section">
                <div class="consultant-health-concern-section-header">
                    <div class="consultant-header-with-icon">
                        <h3>Profile</h3>
                    </div>
                </div>
                <div class="consultant-health-concern-section-content">
                    <div class="consultant-health-concern-item">
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            <h4>Qualifications</h4>
                            <p>Bachelor of Medicine, Bachelor of Surgery (MBBS/MBChB)</p>
                            <p>MS (Master of Surgery)</p>
                        </div>
                    </div>
                    <div class="consultant-health-concern-item">
                        <i class="fas fa-user-md icon"></i>
                        <div>
                            <h4>Specializations</h4>
                            <p>Cardiology</p>
                            <p> Pulmonology</p>
                        </div>
                    </div>
                    <div class="consultant-health-concern-item">
                        <i class="fas fa-map-marker-alt icon"></i>
                        <div>
                            <h4>Regions Available</h4>
                            <p>Colombo Suburbs</p>
                        </div>
                    </div>
                    <div class="consultant-health-concern-item">
                        <i class="fas fa-wheelchair icon"></i>
                        <div>
                            <h4>Hospitals</h4>
                            <p>Colombo General Hospital</p>
                            <p>Health care Clinic,Colombo</p>
                        </div>
                    </div>
                    <div class="consultant-health-concern-item">
                        <i class="fas fa-dollar-sign icon"></i>
                        <div>
                            <h4>Payment details</h4>
                            <p>Rs.2000 per visit</p>
                            <p>+ Rs.400 per session</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Other concerns -->
            <div class="consultant-other-concern-section">
                <div class="consultant-other-concern-section-header">
                    <div class="consultant-header-with-icon">
                        <h3>Availability</h3>
                    </div>
                </div>
                <div class="consultant-other-concern-section-content">
                   
                    </div>
                </div>
            </div>
            <div class="consultant-other-info-section">
            <div class="consultant-health-concern-section">
            <div class="consultant-health-concern-section-header">
                    <div class="consultant-header-with-icon">
                        <h3>Rating & reviews</h3>
                    </div>
                </div>
                <div class="rating-section-content">
                    
                </div>
                <div class="reviews-section-content">

                </div>
            </div>
            </div>
    </div>

</page-body-container>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<script src="<?php echo URLROOT; ?>/js/rating.js"></script>
<script src="<?php echo URLROOT; ?>/js/reviews.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>