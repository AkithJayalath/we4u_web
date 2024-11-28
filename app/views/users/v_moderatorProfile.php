
<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<?php 
    $required_styles = [
        'operator/moderatorProfile',
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
                            <span class="caregiver-personal-info-tag">Moderator</span>
                            <h2>Samantha Samarasingha</h2>
                            <span class="caregiver-email">M#12456</span>
                           
                            <p>29 years</p>
                            <p>Male</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="caregiver-personal-info-right">
            <button class="caregiver-chat-button" >
                    <i class="fas fa-user-plus"></i> Deactivate User
                </button>
                <button class="caregiver-delete-button" onClick="openRejectModal()">
                    <i class="fas fa-user-minus"></i> Delete User
                </button>
                
                
            </div>

        </div>

        <!-- other info section -->
        <div class="caregiver-other-info-section">
        
            <!-- Health concerns -->
            <div class="caregiver-health-concern-section">
                <div class="caregiver-health-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Profile</h3>
                    </div>
                </div>
                <div class="caregiver-health-concern-section-content">
                <div class="caregiver-health-concern-item">
                        <i class="fas fa-phone icon"></i>
                        <div>
                            <h4>Contact Details</h4>
                            <p>0748529663</p>
                            <p>samantha@gmail.com</p>
                            <p>No.25 Flower Rd ,Colombo</p>
                        </div>
                    </div>


                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-graduation-cap icon"></i>
                        <div>
                            <h4>Qualifications</h4>
                            <p>Certified Nursing Assistant (CNA)</p>
                            <p>Home Health Aide (HHA)</p>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- Other concerns -->
            <div class="caregiver-other-concern-section">
                <div class="caregiver-other-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Payments</h3>
                    </div>
                </div>
                    <div class="caregiver-other-concern-section-content">
                    <button class="caregiver-chat-button">
                    <i class="fas fa-money-check"></i> Payments Processed
                </button>
                        </div>
                </div>
                
            </div>

            <div class="caregiver-other-concern-section">
                <div class="caregiver-other-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Others</h3>
                    </div>
                </div>
                    <div class="caregiver-other-concern-section-content">
                    <button class="caregiver-chat-button">
                    <i class="fas fa-chart-line"></i> Activity Logs
                </button>
                        </div>
                </div>
                
            </div>
            

            
    </div>

    <div id="rejecttModal" class="r-modal">
        <div class="r-modal-content">
            <div class="modal-header">
            <img src="/we4u/public/images/oversight-rafiki.png" class="modal-img"/>         
                <h2>You are going to delete this Moderator!</h2>
            </div>
           
            <textarea id="rejectReason" placeholder="Provide your reason to delete this moderator" rows="4" cols="50"></textarea>

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