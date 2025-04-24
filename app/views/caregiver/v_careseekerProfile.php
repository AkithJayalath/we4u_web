
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
                <?php
                $elderProfilePic = !empty($data['elderProfile']->profile_picture)
                        ? URLROOT . '/public/images/profile_imgs/' . $data['elderProfile']->profile_picture
                        : URLROOT . '/public/images/def_profile_pic2.jpg';
                ?>

                    <img src= "<?= $elderProfilePic ?>" alt="Profile" class="personal-info-pic" />

                    <div class="personal-info-details">
                        <h2><?php echo $data['elderProfile']->full_name?></h2>
                        <span class="personal-info-tag"><?php echo $data['elderProfile']->relationship_to_careseeker?></span>
                        
                        <p>
                            <i class="fas fa-mars"></i> <?php echo $data['elderProfile']->gender?>
                            
                        </p>
                        <p><i class="fas fa-birthday-cake"></i> <?php echo $data['elderProfile']->age?></p>
                    </div>
                </div>
            </div>

            <div class="personal-info-right">
                <!-- Stats Section -->
                <div class="personal-info-right-left">
                    <div class="personal-info-stat">
                        <p>BMI</p>
                        <h3><?php echo calculateBMI($data['elderProfile']->weight,$data['elderProfile']->height) ?></h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Weight</p>
                        <h3><?php echo $data['elderProfile']->weight?></h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Height</p>
                        <h3><?php echo $data['elderProfile']->height?></h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Blood Pressure</p>
                        <h3><?php echo $data['elderProfile']->blood_pressure?></h3>
                    </div>
                </div>

                <!-- Diagnosis Section -->
                <div class="personal-info-right-right">
                    <div class="personal-info-diagnosis">
                        <p><strong>Allergies:</strong><?php echo $data['elderProfile']->allergies?></p>
                    </div>
                    <div class="personal-info-diagnosis">
                        <p><strong>Health Barriers:</strong> <?php echo $data['elderProfile']->health_barriers?></p>
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
                            <p><?php echo $data['elderProfile']->chronic_disease?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-tint icon"></i>
                        <div>
                            <h4>Current Medications</h4>
                            <p><?php echo $data['elderProfile']->current_medications?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-user-md icon"></i>
                        <div>
                            <h4>Surgery</h4>
                            <p><?php echo $data['elderProfile']->surgical_history?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-users icon"></i>
                        <div>
                            <h4>Family disease</h4>
                            <p><?php echo $data['elderProfile']->family_diseases?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-exclamation-circle icon"></i>
                        <div>
                            <h4>Current Health Issues</h4>
                            <p><?php echo $data['elderProfile']->current_health_issues?></p>
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
                            <p><?php echo $data['elderProfile']->special_needs?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-utensils icon"></i>
                        <div>
                            <h4>Dietary Restrictions</h4>
                            <p><?php echo $data['elderProfile']->dietary_restrictions?></p>
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

    <div id="rejecttModal" class="r-modal">
        <div class="r-modal-content">
            <div class="modal-header">
                <img src="/we4u/public/images/Online Review-rafiki.png" class="modal-img"/>
                <h2>Leave a Review</h2>
            </div>
            <p>Your Review</p>
            <textarea id="rejectReason" placeholder="Write a review for this careseeker" rows="4" cols="50"></textarea>

            <div class="modal-buttons">
                <button class="btn-submit" onclick="submitRejection()">Submit</button>
                <button class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
            </div>
        </div>
    </div>

</page-body-container>
<?php require APPROOT . '/views/includes/footer.php' ?>

<script>
    // Function to handle the Submit button
function submitRejection() {
    const review = document.getElementById('rejectReason').value.trim();

    if (review === '') {
        // Alert if the textarea is empty
        alert('Please write a review before submitting.');
    } else {
        // Show a success message if a review is provided
        alert(`Thank you for your review: "${review}"`);
        closeRejectModal(); // Close the modal after submission
    }
}

// Function to handle the Cancel button
function closeRejectModal() {
    const modal = document.getElementById('rejecttModal');
    modal.style.display = 'none'; // Hide the modal
}

// Function to open the modal (you can call this when you want to display the modal)
function openRejectModal() {
    const modal = document.getElementById('rejecttModal');
    modal.style.display = 'block'; // Show the modal
}

</script>