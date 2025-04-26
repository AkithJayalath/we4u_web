<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<?php 
    $required_styles = [
        'admin/viewProfile',
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
                    <?php if(!empty($data['user_details']->profile_picture)): ?>
                        <img src="<?php echo URLROOT; ?>/public/images/profile_imgs/<?php echo $data['user_details']->profile_picture; ?>" alt="Profile" class="caregiver-personal-info-pic" />
                    <?php else: ?>
                        <img src="<?php echo URLROOT; ?>/public/images/def_profile_pic2.jpg" alt="Profile" class="caregiver-personal-info-pic" />
                    <?php endif; ?>
                </div>
                <div class="caregiver-personal-info-left-right">
                    <div class="caregiver-personal-info-profile">
                        <div class="caregiver-personal-info-details">
                        
                            <span class="tag completed"><?php echo $data['user_details']->role; ?></span>
                            <h2><?php echo $data['user_details']->username; ?></h2>
                            
                            <span class="caregiver-email"><?php echo $data['user_details']->email; ?></span>
                            <p>ID: <?php echo $data['user_details']->user_id; ?></p>
                            <?php if(isset($data['user_details']->gender)): ?>
                                <p><?php echo $data['user_details']->gender; ?></p>
                            <?php endif; ?>
                            <?php if($data['user_details']->is_deactive == 0): ?>
                            <span class="tag accepted">Active</span>
                <?php else: ?>
                    <span class="tag rejected">deactive</span>
                <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="caregiver-personal-info-right">
                <?php if($data['user_details']->is_deactive == 0): ?>
                    <button class="caregiver-chat-button" onclick="deactivateUser()">
                        <i class="fas fa-user-slash"></i> Deactivate User
                    </button>
                <?php else: ?>
                    <button class="caregiver-chat-button" onclick="activateUser()">
                        <i class="fas fa-user-check"></i> Activate User
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- other info section -->
        <div class="caregiver-other-info-section">
            <!-- Profile Section -->
            <div class="caregiver-health-concern-section">
                <div class="caregiver-health-concern-section-header">
                    <div class="caregiver-header-with-icon">
                        <h3>Profile</h3>
                    </div>
                </div>
                <div class="caregiver-health-concern-section-content">
                    <!-- Contact Details - common for all users -->
                    <div class="caregiver-health-concern-item">
                        <i class="fas fa-phone icon"></i>
                        <div>
                            <h4>Contact Details</h4>
                            <?php if(isset($data['user_details']->phone_number)): ?>
                                <p><?php echo $data['user_details']->phone_number; ?></p>
                            <?php endif; ?>
                            <p><?php echo $data['user_details']->email; ?></p>
                            <?php if(isset($data['user_details']->address)): ?>
                                <p><?php echo $data['user_details']->address; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($data['user_details']->role == 'Caregiver'): ?>
                        <!-- Caregiver Specific Information -->
                        <div class="caregiver-health-concern-item">
                            <i class="fas fa-money-bill icon"></i>
                            <div>
                                <h4>Pricing</h4>
                                <p>Price per session: Rs.<?php echo $data['user_details']->price_per_session; ?></p>
                                <p>Price per day: Rs.<?php echo $data['user_details']->price_per_day; ?></p>
                            </div>
                        </div>
                        <div class="caregiver-health-concern-item">
                            <i class="fas fa-info-circle icon"></i>
                            <div>
                                <h4>Additional Info</h4>
                                <p>Caregiver Type: <?php echo $data['user_details']->caregiver_type; ?></p>
                                <p>Approval Status: <?php echo ($data['user_details']->is_approved) ? 'Approved' : 'Not Approved'; ?></p>
                                <p>Cancellation Flags: <?php echo $data['user_details']->cancellation_flags; ?></p>
                            </div>
                        </div>
                    <?php elseif($data['user_details']->role == 'Consultant'): ?>
                        <!-- Consultant Specific Information -->
                        <div class="caregiver-health-concern-item">
                            <i class="fas fa-user-md icon"></i>
                            <div>
                                <h4>Professional Details</h4>
                                <p>SLMC Registration: <?php echo $data['user_details']->slmc_reg_no; ?></p>
                                <p>Approval Status: <?php echo ($data['user_details']->is_approved) ? 'Approved' : 'Not Approved'; ?></p>
                            </div>
                        </div>
                        <div class="caregiver-health-concern-item">
                            <i class="fas fa-credit-card icon"></i>
                            <div>
                                <h4>Payment Details</h4>
                                <p><?php echo $data['user_details']->payment_details; ?></p>
                            </div>
                        </div>
                    <?php elseif($data['user_details']->role == 'Careseeker'): ?>
                        <!-- Careseeker Specific Information if needed -->
                    <?php elseif($data['user_details']->role == 'Admin' || $data['user_details']->role == 'Moderator'): ?>
                        <!-- Admin/Moderator Specific Information if needed -->
                        <div class="caregiver-health-concern-item">
                            <i class="fas fa-graduation-cap icon"></i>
                            <div>
                                <h4>Qualifications</h4>
                                <p>System Administrator</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Deactivation modal popup -->
    <div id="deactivateModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Deactivation</h2>
            <p>Are you sure you want to deactivate this user?</p>
            <div class="modal-buttons">
                <form action="<?php echo URLROOT; ?>/admin/deactivateUser/<?php echo $data['user_details']->user_id; ?>" method="post">
                    <input type="hidden" name="email" value="<?php echo $data['user_details']->email; ?>">
                    <button type="submit" class="modal-btn confirm-btn">Yes, Deactivate</button>
                </form>
                <button class="modal-btn cancel-btn" onclick="closeDeactivateModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Activation modal popup -->
    <div id="activateModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Activation</h2>
            <p>Are you sure you want to activate this user?</p>
            <div class="modal-buttons">
                <form action="<?php echo URLROOT; ?>/admin/activateUser/<?php echo $data['user_details']->user_id; ?>" method="post">
                    <input type="hidden" name="email" value="<?php echo $data['user_details']->email; ?>">
                    <button type="submit" class="modal-btn confirm-btn" style="background-color: #4caf50;">Yes, Activate</button>
                </form>
                <button class="modal-btn cancel-btn" onclick="closeActivateModal()">Cancel</button>
            </div>
        </div>
    </div>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<script src="<?php echo URLROOT; ?>/js/rating.js"></script>
<script src="<?php echo URLROOT; ?>/js/reviews.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>

<script>
function deactivateUser() {
    document.getElementById('deactivateModal').style.display = 'block';
}

function closeDeactivateModal() {
    document.getElementById('deactivateModal').style.display = 'none';
}

function activateUser() {
    document.getElementById('activateModal').style.display = 'block';
}

function closeActivateModal() {
    document.getElementById('activateModal').style.display = 'none';
}
</script>
