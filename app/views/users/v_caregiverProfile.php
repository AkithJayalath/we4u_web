
<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<?php 
    $required_styles = [
        'operator/caregiverProfile',
    ];
    echo loadCSS($required_styles);
?>


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
                            <span class="caregiver-email">CG#12456</span>
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
                <button class="caregiver-delete-button" onClick="openRejectModal()">
                    <i class="fas fa-user-minus"></i> Delete User
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
                        <i class="fas fa-phone icon"></i>
                        <div>
                            <h4>Contact Details</h4>
                            <p>0748529663</p>
                            <p>pawan@gmail.com</p>
                            <p>No.25 Flower Rd ,Colombo</p>
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-file icon"></i>
                        <div>
                            <h4>Submited Documents</h4>
                            <p><a href="#" >police_report.pdf</a></p>
                           
                        </div>
                    </div>
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-dollar-sign icon"></i>
                        <div>
                            <h4>Payment details</h4>
                            <p>Rs.2000 per visit</p>
                            <p>+ Rs.400 per session</p>
                            
                        </div>
                        <button class="caregiver-payment-button">
                                <i class="fas fa-credit-card"></i> Payment Method
                            </button>
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
                    <i class="fas fa-calendar"></i> View Calendar
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

            <div class="other-concern-section">
                <div class="other-concern-section-header">
                    <div class="header-with-icon">
                        <h3>Issues Reported</h3>
                    </div>
                </div>
                <div class="other-concern-section-content">
                    <div class="health-concern-item">
                        <i class="fas fa-user icon"></i>
                        <div>
                            <h4>Jerom Bell</h4>
                            <p>He didn't work on time</p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-user icon"></i>
                        <div>
                            <h4>Nadun Hasalanka</h4>
                            <p>Dfdfgdfhgfj</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div id="rejecttModal" class="r-modal">
        <div class="r-modal-content">
            <div class="modal-header">
            <img src="/we4u/public/images/oversight-rafiki.png" class="modal-img"/>         
                <h2>You are going to delete this user!</h2>
            </div>
           
            <textarea id="rejectReason" placeholder="Provide your reason to delete this user" rows="4" cols="50"></textarea>

            <div class="modal-buttons">
                <button class="btn-submit" onclick="submitRejection()">Delete User</button>
                <button class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
            </div>
        </div>
    </div>

</page-body-container>


<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<script src="<?php echo URLROOT; ?>/js/rating.js"></script>
<script src="<?php echo URLROOT; ?>/js/reviews.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>

<script>
    // Function to handle the Submit button
function submitRejection() {
    const review = document.getElementById('rejectReason').value.trim();

    
        // Show a success message if a review is provided
        alert(`User is deleted successfully!`);
        closeRejectModal(); // Close the modal after submission
    
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