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
        <!-- Gradient bar with heading -->
        <div class="gradient-bar-profile">
            <h1>Edit Elderly Profile</h1>
        </div>

        <div class="container-profile">
            <div class="form-section">
            <?php
// Assuming $data['elder_id'] is already set
$elder_id = $data['elder_id'];
?>
            <form action="<?php echo URLROOT; ?>/careseeker/editElderProfile/" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="elder_id" value="<?php echo htmlspecialchars($elder_id); ?>">

                    <!-- Profile Picture Section -->
                    <div class="profile-picture-section">
                    <div class="profile-pic-container" onclick="triggerUpload()">
                    <img src="<?= isset($data['profile_picture']) && $data['profile_picture'] 
            ?  URLROOT . '/images/profile_imgs/' . $data['profile_picture'] 
                        : URLROOT . '/images/def_profile_pic.jpg'; ?>" 
            alt="Profile Picture" class="profile-pic" id="profile_image_placeholder">


                    <div class="upload-overlay">
                        <span>Upload photo</span>
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
                        <input type="text" id="F_elderlyName" name="first_name" value="<?php echo $data['first_name']; ?>" required>
                        <span class="form-invalid"><?php echo $data['first_name_err']; ?></span>

                        <label for="M_elderlyName">Middle Name:</label>
                        <input type="text" id="M_elderlyName" name="middle_name"value="<?php echo $data['middle_name']; ?>">
                       

                        <label for="L_elderlyName">Last Name:</label>
                        <input type="text" id="L_elderlyName" name="last_name" value="<?php echo $data['last_name']; ?>" required>
                        <span class="form-invalid"><?php echo $data['last_name_err']; ?></span>

                        <label for="relationship">Relationship to the Careseeker:</label>
                        <input type="text" id="relationship" name="relationship_to_careseeker" value="<?php echo $data['relationship_to_careseeker']; ?>" required>
                        <span class="form-invalid"><?php echo $data['relationship_to_careseeker_err']; ?></span>
                       
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" value="<?php echo $data['age']; ?>" required>
                        <span class="form-invalid"><?php echo $data['age_err']; ?></span>
                        
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" value="<?php echo $data['gender']; ?>" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        
                        <label for="weight">Weight (kg):</label>
                        <input type="number" id="weight" name="weight" value="<?php echo $data['weight']; ?>" required>
                       
                        <label for="height">Height (cm):</label>
                        <input type="number" id="height" name="height" value="<?php echo $data['height']; ?>" required>
                       
                        <label for="bloodPressure">Blood Pressure:</label>
                        <input type="text" id="bloodPressure" name="blood_pressure" placeholder="e.g., 120/80 mmHg" value="<?php echo $data['blood_pressure']; ?>"required>
                        

                        <label for="emergencyContact">Emergency Contact:</label>
                        <input type="text" id="emergencyContact" name="emergency_contact" placeholder="Enter valid contact" value="<?php echo $data['emergency_contact']; ?>"required>
                        <span class="form-invalid"><?php echo $data['emergency_contact_err']; ?></span>

                        <label for="chronicDiseases">Chronic Diseases:</label>
                        <textarea id="chronicDiseases" name="chronic_disease" rows="3" placeholder="List chronic illnesses, if any..."><?php echo htmlspecialchars($data['chronic_disease']); ?></textarea>

                        <label for="allergies">Allergies:</label>
                        <textarea id="allergies" name="allergies" rows="3" placeholder="List allergies, if any..."><?php echo htmlspecialchars($data['allergies']); ?></textarea>

                        <label for="health_barriers">Health Barriers:</label>
                        <textarea id="health_barriers" name="health_barriers" rows="3" placeholder="List health barriers, if any..."><?php echo htmlspecialchars($data['health_barriers']); ?></textarea>
                    </div>

                    <!-- Health & Medical Details Section -->
                    <div class="form-column">
                        <h2>Health Details</h2>
                        <label for="currentHealthConcerns">Current Health Concerns:</label>
                        <textarea id="currentHealthConcerns" name="current_health_issues" rows="3" placeholder="List Current health issues, if any..."><?php echo htmlspecialchars($data['current_health_issues']); ?></textarea>
                        
                        <label for="surgeries">Past Surgeries:</label>
                        <textarea id="surgeries" name="surgical_history" rows="3" placeholder="Surgeries, if any..."><?php echo htmlspecialchars($data['surgical_history']); ?></textarea>

                        <label for="familyDiseases">Family Diseases:</label>
                        <textarea id="familyDiseases" name="family_diseases" rows="3" placeholder="Family diseases, if any..."><?php echo htmlspecialchars($data['family_diseases']); ?></textarea>

                        <label for="currentMedications">Current Medications:</label>
                        <textarea id="currentMedications" name="current_medications" rows="3" placeholder="Current medications,if any..."><?php echo htmlspecialchars($data['current_medications']); ?></textarea>
                    </div>

                    <!-- Special Care Requirements -->
                    <div class="form-column">
                        <h2>Special Care Details</h2>
                        <label for="specialNeeds">Special Needs:</label>
                        <textarea id="specialNeeds" name="special_needs" rows="3" placeholder="Special needs,if any..."><?php echo htmlspecialchars($data['special_needs']); ?></textarea>
                        
                        <label for="dietaryRestrictions">Dietary Restrictions:</label>
                        <textarea id="dietaryRestrictions" name="dietary_restrictions" rows="3" placeholder="Diatery restrictions,if any..."><?php echo htmlspecialchars($data['dietary_restrictions']); ?></textarea>
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="save-cancel">
                    <button type="submit" class="save-btn">Save</button>
                    <a href="<?php echo URLROOT; ?>/users/viewCareseekerProfile" class="cancel-button">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/profilePicUpload.js"></script>
<?php require APPROOT.'/views/includes/footer.php'; ?>
