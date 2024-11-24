<?php 
    $required_styles = [
        'careseeker/create',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="total-container">
        <!-- Gradient bar with heading inside -->
        <div class="gradient-bar-profile">
            <h1>Create Elderly Profile</h1>
        </div>
        <div class="container-profile">
        <div class="section-container">
        
            <form action="your_php_backend.php" method="POST" enctype="multipart/form-data">
                <!-- Profile Picture Section -->
                <div class="upload-column">
                        <div class="profile-pic-container" onclick="triggerUpload()">
                            <img src="<?= URLROOT . '/images/def_profile_pic.jpg'; ?>" 
                                 alt="Profile Picture" class="profile-pic" id="profile_image_placeholder">
                            <div class="upload-overlay">
                                <span>Upload Photo</span>
                            </div>
                            <input type="file" id="profileImageUpload" name="profile_picture" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        </div>
                        <div class="form-validation">
                    <div class="profile-image-validation">
                        <img src="<?php echo URLROOT;?>/images/green_tick.png" alt="green_tick" width="25px" height="40px" style="padding-top: 15px">
                        Upload image
                    </div>
                </div>
                        <span class="form-invalid"><?php echo $data['profile_picture_err'] ?? ''; ?></span>
                    </div>

                <!-- Personal Details Section -->
                <div class="form-column">
                    <h2>Personal Details</h2>
                    <label for="F_elderlyName">First Name:</label>
                    <input type="text" id="F_elderlyName" name="F_elderlyName" required>

                    <label for="M_elderlyName">Middle Name:</label>
                    <input type="text" id="M_elderlyName" name="M_elderlyName" >

                    <label for="L_elderlyName">Last Name:</label>
                    <input type="text" id="L_elderlyName" name="L_elderlyName" required>

                    <label for="relationship">Relationship to the careseeker:</label>
                    <input type="text" id="elderlyName" name="elderlyName" required>
                    
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required>
                    
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    
                    <label for="weight">Weight (kg):</label>
                    <input type="number" id="weight" name="weight" required>
                    
                    <label for="height">Height (cm):</label>
                    <input type="number" id="height" name="height" required>
                    
                    <label for="bloodPressure">Blood Pressure:</label>
                    <input type="text" id="bloodPressure" name="bloodPressure" placeholder="e.g., 120/80 mmHg" required>

                    <label for="emergencyContact">Emergency Contact:</label>
                    <input type="text" id="emergencyContact" name="emergencyContact" placeholder="Enter valid contact" required>

                    <label for="chronicDiseases">Chronic Diseases:</label>
                    <textarea id="chronicDiseases" name="chronicDiseases" rows="3" placeholder="List chronic illnesses, if any..." ></textarea>

                    <label for="currentHealthConcerns">Current Health Concerns:</label>
                    <textarea id="currentHealthConcerns" name="currentHealthConcerns" rows="3" placeholder="Describe ongoing issues..." ></textarea>

                    <label for="currentHealthConcerns">Allergies:</label>
                    <textarea id="currentHealthConcerns" name="currentHealthConcerns" rows="3" placeholder="Describe ongoing issues..." ></textarea>


                </div>

                <!-- For consultant -->
                <div class="form-column">
                    <h2>For Consultant</h2>
                   
                    <label for="surgeries">Past Surgeries:</label>
                    <textarea id="surgeries" name="surgeries" rows="3" placeholder="List surgeries, if any..."></textarea>
                    
                    <label for="familyDiseases">Family Diseases:</label>
                    <textarea id="familyDiseases" name="familyDiseases" rows="3" placeholder="List hereditary diseases, if any..."></textarea>

                    <label for="currentMedications">Current Medications:</label>
                    <textarea id="currentMedications" name="currentMedications" rows="3" placeholder="medication - disease, if any..."></textarea>
                    
                </div>


                <div class="form-column">
                    <h2>For Caregiver</h2>
                   
                    <label for="specialNeeds">Special Needs:</label>
                    <textarea id="specialNeeds" name="specialNeeds" rows="3" placeholder="List special needs, if any..."></textarea>
                    
                    <label for="dietaryRestrictions">Dietary restrictions:</label>
                    <textarea id="dietaryRestrictions" name="dietaryRestrictions" rows="3" placeholder="List dietary restrictions, if any..."></textarea>
                    
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="profile-create-btn">Create Profile</button>
                    <button class="profile-cancel-btn"   onclick="window.location.href='<?php echo URLROOT; ?>/pages/index'">Cancel</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</page-body-container>
<script src="<?php echo URLROOT; ?>/js/profilePicUpload.js"></script>
<?php require APPROOT.'/views/includes/footer.php' ?>
