
<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/careseekerProfile.css"> 

 
<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="view-elder-profile">
        <!-- Personal info section -->
        <div class="personal-info-section">
            <div class="personal-info-left">
                <div class="personal-info-profile">
                    <img src="data:image/jpeg;base64,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" alt="Profile" class="personal-info-pic" />

                    <div class="personal-info-details">
                        <h2>Ahmed Ali Hussain</h2>
                        <span class="personal-info-tag">Grand Father</span>
                        
                        <p>
                            <i class="fas fa-mars"></i> Male
                            <i class="fas fa-map-marker-alt"></i> El Sheikh Zayed, Giza
                        </p>
                        <p><i class="fas fa-birthday-cake"></i> 78 years</p>
                    </div>
                </div>
            </div>

            <div class="personal-info-right">
                <!-- Stats Section -->
                <div class="personal-info-right-left">
                    <div class="personal-info-stat">
                        <p>BMI</p>
                        <h3>22.4</h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Weight</p>
                        <h3>92 kg</h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Height</p>
                        <h3>175 cm</h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Blood Pressure</p>
                        <h3>124/80</h3>
                    </div>
                </div>

                <!-- Diagnosis Section -->
                <div class="personal-info-right-right">
                    <div class="personal-info-diagnosis">
                        <p><strong>Own Diagnosis:</strong> Obesity, Uncontrolled Type 2</p>
                    </div>
                    <div class="personal-info-diagnosis">
                        <p><strong>Health Barriers:</strong> Fear of medication, Fear of insulin</p>
                    </div>
                    <button class="caregiver-chat-button">
                    <i class="fas fa-comments"></i> Chat
                </button>
                </div>
            </div>
        </div>

<!-- other info section -->
        <div class="other-info-section">
            <!-- Health concerns -->
            <div class="health-concern-section">
                <div class="health-concern-section-header">
                    <div class="header-with-icon">
                        <i class="fas fa-notes-medical header-icon"></i>
                        <h3>Medical History</h3>
                    </div>
                </div>
                <div class="health-concern-section-content">
                    <div class="health-concern-item">
                        <i class="fas fa-heartbeat icon"></i>
                        <div>
                            <h4>Chronic disease</h4>
                            <p>IHD, Obesity, Chronic thyroid disorder</p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-tint icon"></i>
                        <div>
                            <h4>Diabetes Emergencies</h4>
                            <p>Diabetic Ketoacidosis</p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-user-md icon"></i>
                        <div>
                            <h4>Surgery</h4>
                            <p>Liposuction</p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-users icon"></i>
                        <div>
                            <h4>Family disease</h4>
                            <p>Obesity (Father)</p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-exclamation-circle icon"></i>
                        <div>
                            <h4>Diabetes-related complications</h4>
                            <p>Nephropathy, Neuropathy, Retinopathy, Diabetic foot, Sexual dysfunction</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Other concerns -->
            <div class="other-concern-section">
                <div class="other-concern-section-header">
                    <div class="header-with-icon">
                        <i class="fas fa-hand-holding-heart header-icon"></i>
                        <h3>Other Concerns</h3>
                    </div>
                </div>
                <div class="other-concern-section-content">
                    <div class="health-concern-item">
                        <i class="fas fa-wheelchair icon"></i>
                        <div>
                            <h4>Special Needs</h4>
                            <p>Wheelchair assistance, Vision impairment support</p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-utensils icon"></i>
                        <div>
                            <h4>Dietary Restrictions</h4>
                            <p>Low sodium diet, Gluten-free meals</p>
                        </div>
                    </div>
                </div>
            </div>

                <!-- caregiving history -->
            <div class="caregiving-history-section">
                <div class="caregiving-history-section-header">
                    <div class="header-with-icon">
                        <i class="fas fa-history header-icon"></i>
                        <h3>Caregiving History</h3>
                    </div>
                </div>
                <div class="caregiving-history-section-content">
                    <div class="caregiving-history-item">
                    <i class="fas fa-check icon"></i>
                        <div>
                            <h4>Caregiving Visits</h4>
                            <p>Completed caregiving session on 14th Jan 2024 at 6.00PM</p>
                        </div>
                    </div>
                    <div class="caregiving-history-item">
                    <i class="fas fa-check icon"></i>
                        <div>
                            <h4>Caregiving Visits</h4>
                            <p>Completed caregiving session on 18th Oct 2023 at 6.00PM</p>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="reviews-section">
                <div class="reviews-section-header">
                    <div class="header-with-icon">
                    <i class="fas fa-comment header-icon"></i>
                        <h3>Reviews</h3>
                    </div>
                </div>
                <div class="reviews-section-content">
                    <div class="reviews-item">
                    <i class="fas fa-user icon"></i>
                        <div>
                            <h4>Jerom Bell</h4>
                            <p>He is very kind person</p>
                            <div class="date">13.02.2023</div>
                        </div>
                        
                    </div>
                    <div class="reviews-item">
                    <i class="fas fa-user icon"></i>
                        <div>
                            <h4>Nethmi Vithanage</h4>
                            <p>He didn't pay my full payment</p>
                            <div class="date">13.02.2023</div>
                        </div>
                    </div>
                    
                </div>
            </div>


        </div>

    </div>

</page-body-container>
<?php require APPROOT . '/views/includes/footer.php' ?>