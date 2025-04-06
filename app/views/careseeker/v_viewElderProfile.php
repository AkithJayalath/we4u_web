<?php
$required_styles = [
    'careseeker/viewElderProfile',
];
echo loadCSS($required_styles); 
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="view-elder-profile">
        <!-- Personal info section -->
        <div class="personal-info-section">
            <div class="personal-info-left">
                <div class="personal-info-profile">
                    <img src="<?php echo !empty($data['elderData']->profile_picture) ? URLROOT . '/images/profile_imgs/' . $data['elderData']->profile_picture 
                        : URLROOT . '/images/def_profile_pic.jpg'; ?>"  alt="Profile" class="personal-info-pic" />

                    <div class="personal-info-details">
                        <span class="personal-info-tag"><?php echo $data['elderData']->relationship_to_careseeker ?></span>
                        <h2><?php echo $data['elderData']->first_name ?></h2>
                        <p>
                            <i class="fas fa-mars"></i> <?php echo $data['elderData']->gender ?>
                            <i class="fas fa-map-marker-alt"></i> El Sheikh Zayed, Giza
                        </p>
                        <p><i class="fas fa-birthday-cake"></i><?php echo $data['elderData']->age ?></p>
                    </div>
                </div>
            </div>

            <div class="personal-info-right">
                <!-- Stats Section -->
                <div class="personal-info-right-left">
                    <div class="personal-info-stat">
                        <p>BMI</p>
                        <h3><?php echo calculateBMI($data['elderData']->weight,$data['elderData']->height) ?></h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Weight</p>
                        <h3><?php echo $data['elderData']->weight ?>kg</h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Height</p>
                        <h3><?php echo $data['elderData']->height ?> cm</h3>
                    </div>
                    <div class="personal-info-stat">
                        <p>Blood Pressure</p>
                        <h3><?php echo $data['elderData']->blood_pressure ?></h3> 
                    </div>
                </div>

                <!-- Diagnosis Section -->
                <div class="personal-info-right-right">
                    <div class="personal-info-diagnosis">
                        <p><strong>Emergency Contact:</strong><?php echo $data['elderData']->emergency_contact ?> </p>
                    </div>
                    <div class="personal-info-diagnosis">
                        <p><strong>Health Barriers:</strong><?php echo $data['elderData']->health_barriers ?></p>
                    </div>
                    <form action="<?php echo URLROOT; ?>/careseeker/editElderProfile" method="GET">
    <input type="hidden" name="elder_id" value="<?php echo $data['elderData']->elder_id; ?>">
    <button type="submit" class="personal-info-edit-btn">
        <i class="fas fa-edit"></i> Edit
    </button>
</form>

</form>

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
                        <h3>Medical history</h3>
                    </div>
                </div>
                <div class="health-concern-section-content">
                    <div class="health-concern-item">
                        <i class="fas fa-heartbeat icon"></i>
                        <div>
                            <h4>Chronic disease</h4>
                            <p><?php echo $data['elderData']->chronic_disease ?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-tint icon"></i>
                        <div>
                            <h4>Current Health Issues</h4>
                            <p><?php echo $data['elderData']->current_health_issues ?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-user-md icon"></i>
                        <div>
                            <h4>Surgeries</h4>
                            <p><?php echo $data['elderData']->surgical_history ?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-users icon"></i>
                        <div>
                            <h4>Family disease</h4>
                            <p><?php echo $data['elderData']->family_diseases ?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-exclamation-circle icon"></i>
                        <div>
                            <h4>Allergies</h4>
                            <p><?php echo $data['elderData']->allergies ?></p>
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
                            <p><?php echo $data['elderData']->special_needs ?></p>
                        </div>
                    </div>
                    <div class="health-concern-item">
                        <i class="fas fa-utensils icon"></i>
                        <div>
                            <h4>Dietary Restrictions</h4>
                            <p><?php echo $data['elderData']->dietary_restrictions ?></p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

</page-body-container>
<?php require APPROOT . '/views/includes/footer.php' ?>