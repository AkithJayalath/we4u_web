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
    
    <div class="view-elder-profile">
        <form action="<?php echo URLROOT; ?>/consultant/saveElderProfile" method="POST" enctype="multipart/form-data">
            <!-- Personal info section -->
            <div class="personal-info-section">
                <div class="personal-info-left">
                    <div class="personal-info-profile">
                        <div class="profile-image-wrapper">
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                            <img id="profile_preview" src="<?php echo URLROOT; ?>/images/default_profile.jpg" alt="Profile" class="personal-info-pic">
                        </div>

                        <div class="personal-info-details">
                            <select name="relationship" class="form-control">
                                <option value="">Select Relationship</option>
                                <option value="grandfather">Grand Father</option>
                                <option value="grandmother">Grand Mother</option>
                                <option value="father">Father</option>
                                <option value="mother">Mother</option>
                            </select>

                            <input type="text" name="full_name" placeholder="Full Name" required>
                            
                            <select name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>

                            <input type="text" name="address" placeholder="Address" required>
                            <input type="number" name="age" placeholder="Age" required>
                        </div>
                    </div>
                </div>

                <div class="personal-info-right">
                    <div class="personal-info-right-left">
                        <div class="personal-info-stat">
                            <p>BMI</p>
                            <input type="number" step="0.1" name="bmi" placeholder="BMI">
                        </div>
                        <div class="personal-info-stat">
                            <p>Weight</p>
                            <input type="number" name="weight" placeholder="Weight in kg">
                        </div>
                        <div class="personal-info-stat">
                            <p>Height</p>
                            <input type="number" name="height" placeholder="Height in cm">
                        </div>
                        <div class="personal-info-stat">
                            <p>Blood Pressure</p>
                            <input type="text" name="blood_pressure" placeholder="e.g. 120/80">
                        </div>
                    </div>

                    <div class="personal-info-right-right">
                        <div class="personal-info-diagnosis">
                            <p><strong>Own Diagnosis:</strong></p>
                            <textarea name="own_diagnosis" placeholder="Enter diagnosis details"></textarea>
                        </div>
                        <div class="personal-info-diagnosis">
                            <p><strong>Health Barriers:</strong></p>
                            <textarea name="health_barriers" placeholder="Enter health barriers"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical History Section -->
            <div class="other-info-section">
                <div class="health-concern-section">
                    <div class="health-concern-section-header">
                        <div class="header-with-icon">
                            <i class="fas fa-notes-medical header-icon"></i>
                            <h3>Medical History</h3>
                        </div>
                    </div>
                    <div class="health-concern-section-content">
                        <div class="form-group">
                            <label>Chronic Diseases:</label>
                            <textarea name="chronic_diseases" placeholder="Enter chronic diseases"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Diabetes Emergencies:</label>
                            <textarea name="diabetes_emergencies" placeholder="Enter diabetes emergencies"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Surgery History:</label>
                            <textarea name="surgery_history" placeholder="Enter surgery history"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Family Diseases:</label>
                            <textarea name="family_diseases" placeholder="Enter family diseases"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Diabetes-related Complications:</label>
                            <textarea name="diabetes_complications" placeholder="Enter diabetes-related complications"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Other Concerns Section -->
                <div class="other-concern-section">
                    <div class="other-concern-section-header">
                        <div class="header-with-icon">
                            <i class="fas fa-hand-holding-heart header-icon"></i>
                            <h3>Other Concerns</h3>
                        </div>
                    </div>
                    <div class="other-concern-section-content">
                        <div class="form-group">
                            <label>Special Needs:</label>
                            <textarea name="special_needs" placeholder="Enter special needs"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Dietary Restrictions:</label>
                            <textarea name="dietary_restrictions" placeholder="Enter dietary restrictions"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Profile</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>
</page-body-container>

<?php require APPROOT . '/views/includes/footer.php' ?>
