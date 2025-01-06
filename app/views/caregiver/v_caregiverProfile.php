
<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/caregiverProfile.css"> 


<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?> 
    <!-- Container -->
    <div class="view-caregiver-profile">
        <!-- Personal info section -->
        <div class="caregiver-personal-info-section">
            <div class="caregiver-personal-info-left">
                <div class="caregiver-personal-info-left-left">
                    <img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Profile" class="caregiver-personal-info-pic" />
                </div>
                <div class="caregiver-personal-info-left-right">
                    <div class="caregiver-personal-info-profile">

                        <div class="caregiver-personal-info-details">
                            <span class="caregiver-personal-info-tag">Verfied</span>
                            <h2>Pawan Wickramarathne</h2>
                            <span class="caregiver-email">pawanwick@gmail.com</span>
                            <p class="consultant-rating">
                                <span class="rating-stars" id="rating-stars"></span>
                            </p>
                            <p>29 years</p>
                            <p>Male</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="caregiver-personal-info-right">
                <div class="caregiver-personal-info-right-top">
                    <div class="personal-info-badge">I am a Caregiver</div>
                    <div class="personal-info-badge">Work Type Long-term</div>
                    <div class="personal-info-badge">Work Type Short-term</div>
                </div>
                <button class="caregiver-edit-button">
                    <i class="fas fa-edit"></i> Edit Profile
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
                    <p>I am a passionate and experienced caregiver with 2 years of expertise in providing personalized care to individuals of all ages, including seniors, individuals with disabilities, and those with chronic illnesses. My approach to caregiving is rooted in empathy, patience, and respect, ensuring that each person I assist feels valued, comfortable, and supported</p>
                   
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
                            <p>Certified Nursing Assistant (CNA)</p>
                            <p>Home Health Aide (HHA)</p>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-user-md icon"></i>
                        <div>
                            <h4>Specializations</h4>
                            <p>Dementia and Alzheimer's care</p>
                            <p>Companionship and emotional support for seniors</p>
                            <p>Fall prevention and mobility assistance</p>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-map-marker-alt icon"></i>
                        <div>
                            <h4>Regions Available</h4>
                            <p>Colombo Suburbs</p>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-hands-helping icon"></i>
                        <div>
                            <h4>Special Skills</h4>
                            <p>Cooking Skills</p>
                            <p>Feeding Assistance</p>
                            <p>Wound Care</p>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-dollar-sign icon"></i>
                        <div>
                            <h4>Payment details</h4>
                            <p>Rs.2000 per visit</p>
                            <p>+ Rs.400 per session</p>
                            
                        </div>
                        <a href="<?php echo URLROOT; ?>/Caregivers/paymentMethod" class="caregiver-payment-button">
                        <i class="fas fa-credit-card"></i> Payment Method
                        </a>

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
                    <button class="caregiver-chat-button">
                    <i class="fas fa-calendar"></i> View My Calendar
                </button>
                        </div>
                </div>
                
            </div>
            <div class="caregiver-other-info-section">
            <div class="caregiver-health-concern-section">
            <div class="caregiver-health-concern-section-header">
                    <div class="caregiver-header-with-icon">
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