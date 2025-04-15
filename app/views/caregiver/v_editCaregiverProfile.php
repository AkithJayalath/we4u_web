<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/editCaregiverProfile.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>

    <div class="edit-profile-container">
        <div class="edit-profile-header">
            <h2>Edit Profile</h2>
        </div>

        <form id="editprofileForm" action="<?php echo URLROOT; ?>/caregivers/editmyProfile" method="POST" enctype="multipart/form-data">
            <div class="profile-picture-section" onclick="triggerUpload()">
                <img src="<?php echo !empty($data['profile']->profile_picture) ? URLROOT . '/images/profile_imgs/' . $data['profile']->profile_picture : URLROOT . '/images/def_profile_pic.jpg'; ?>" alt="Profile Picture" id="preview-image" >
                    
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(this)">

                    <label for="profile_picture" class="upload-btn">Change Profile Picture</label>
            </div>

            <div class="form-fields">
                <div class="form-group">
                    <label><span style="color: red">*</span> Username</label>
                    <input type="text" name="username" value="<?php echo $data['username']; ?>" required>
                    <span class="form-invalid"><?php echo isset($data['username_err']) ? $data['username_err'] : ''; ?></span>

                </div>

                <div class="form-group">
                    <label><span style="color: red">*</span> Contact Number</label>
                    <input type="tel"  name="contact_info" placeholder="0123456789" pattern="[0-9]{10}" value="<?php echo $data['contact_info']; ?>" required>
                    <span class="form-invalid"><?php echo isset($data['contact_info_err']) ? $data['contact_info_err'] : ''; ?></span>

                </div>

                <div class="form-group">
                    <label><span style="color: red">*</span> Address</label>
                    <textarea name="address" placeholder="Enter your address" required><?php echo $data['address']; ?></textarea>
                    <span class="form-invalid"><?php echo isset($data['address_err']) ? $data['address_err'] : ''; ?></span>
                    </div>

                <div class="form-group">
                    <label>Caregiver Type</label>
                    <div class="select-wrapper">
                        <select name="caregiver_type" id="caregiver-type-select" required>
                            <option value="short" <?php echo (isset($data['caregiver_type']) && $data['caregiver_type'] == 'short') ? 'selected' : ''; ?>>Short-term</option>
                            <option value="long" <?php echo (isset($data['caregiver_type']) && $data['caregiver_type'] == 'long') ? 'selected' : ''; ?>>Long-term</option>
                            <option value="both" <?php echo (isset($data['caregiver_type']) && $data['caregiver_type'] == 'both') ? 'selected' : ''; ?>>Both</option>
                        </select>
                        
                    </div>
                </div>

                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" id="bio" cols="30" rows="5"><?php echo $data['bio']; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Qualifications</label>
                    <textarea name="qualification" id="qualifications" cols="30" rows="5"><?php echo $data['qualification']; ?></textarea>
                </div>

                <div class="form-group">
    <label>Specializations</label>
    <div class="select-wrapper">
        <button type="button" class="dropbtn specializations-btn">Select Specializations</button>

        <?php
        // Fetch stored specializations from the database
        $selectedSpecializations = isset($data['specialty']) ? explode(',', $data['specialty']) : [];

        // Specialization options
        $specializationOptions = [
            'Dementia Care',
            'Wound Care',
            'Wheelchair Care',
            'Palliative Care',
            'Post-Surgery Care',
            'Diabetes Care',
            'Parkinson\'s Care',
            'Stroke Recovery Care',
            'Elderly Care',
            'Pediatric Care',
            'Physical Therapy Assistance',
            'Speech Therapy Assistance'
        ];
        ?>

        <div class="dropdown-content specializations-dropdown">
            <?php foreach ($specializationOptions as $specialization): ?>
                <label>
                    <input type="checkbox" class="specialization-checkbox" 
                           name="specializations[]" 
                           value="<?php echo $specialization; ?>"
                           <?php echo in_array($specialization, $selectedSpecializations) ? 'checked' : ''; ?>>
                    <?php echo $specialization; ?>
                </label>
            <?php endforeach; ?>
        </div>
        <small>Select multiple specializations</small>
    </div>
    <div id="selectedSpecializations"></div>
</div>

                <div class="form-group">
                    <label>Available Regions</label>
                    <div class="select-wrapper">
                        <button type="button" class="dropbtn regions-btn">Select Regions</button>

                        <?php
                        // Fetch stored regions from the database
                        $selectedRegions = isset($data['available_region']) ? explode(',', $data['available_region']) : [];

                        // Function to check if a region is selected
                        function isRegionChecked($value, $selectedRegions) {
                            return in_array($value, $selectedRegions) ? 'checked' : '';
                        }

                        // Sri Lankan regions
                        $sriLankanRegions = [
                            'Colombo',
                            'Gampaha',
                            'Kalutara',
                            'Kandy',
                            'Matale',
                            'Nuwara Eliya',
                            'Galle',
                            'Matara',
                            'Hambantota',
                            'Jaffna',
                            'Kilinochchi',
                            'Mannar',
                            'Vavuniya',
                            'Mullaitivu',
                            'Batticaloa',
                            'Ampara',
                            'Trincomalee',
                            'Kurunegala',
                            'Puttalam',
                            'Anuradhapura',
                            'Polonnaruwa',
                            'Badulla',
                            'Moneragala',
                            'Ratnapura',
                            'Kegalle'
                        ];
                        ?>

                        <div class="dropdown-content regions-dropdown">
                            <?php foreach ($sriLankanRegions as $region): ?>
                                <label>
                                    <input type="checkbox" class="region-checkbox" name="available_regions[]" 
                                        value="<?php echo $region; ?>" 
                                        <?php echo isRegionChecked($region, $selectedRegions); ?>>
                                    <?php echo $region; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <small>Select multiple regions</small>
                    </div>
                    <div id="selectedRegions"></div>
                </div>

                <div class="form-group">
                    <label>Special Skills</label>
                    <div class="select-wrapper">
                        <button type="button" class="dropbtn skills-btn">Select Skills</button>

                        <?php
                        // Fetch stored skills from the database
                        $selectedSkills = isset($data['skills']) ? explode(',', $data['skills']) : [];

                        // Function to check if a skill is selected
                        function isChecked($value, $selectedSkills) {
                            return in_array($value, $selectedSkills) ? 'checked' : '';
                        }

                        // Function to format skill names for display (replace underscores with spaces)
                        function formatSkillName($skill) {
                            return ucwords(str_replace('_', ' ', $skill));
                        }
                        ?>

                        <div class="dropdown-content skills-dropdown">
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="cooking_skills" <?php echo isChecked('cooking_skills', $selectedSkills); ?>> <?php echo formatSkillName('cooking_skills'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="feeding_assistance" <?php echo isChecked('feeding_assistance', $selectedSkills); ?>> <?php echo formatSkillName('feeding_assistance'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="meal_preparation" <?php echo isChecked('meal_preparation', $selectedSkills); ?>> <?php echo formatSkillName('meal_preparation'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="medication_management" <?php echo isChecked('medication_management', $selectedSkills); ?>> <?php echo formatSkillName('medication_management'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="personal_hygiene_care" <?php echo isChecked('personal_hygiene_care', $selectedSkills); ?>> <?php echo formatSkillName('personal_hygiene_care'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="wound_care" <?php echo isChecked('wound_care', $selectedSkills); ?>> <?php echo formatSkillName('wound_care'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="physical_therapy" <?php echo isChecked('physical_therapy', $selectedSkills); ?>> <?php echo formatSkillName('physical_therapy'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="speech_therapy" <?php echo isChecked('speech_therapy', $selectedSkills); ?>> <?php echo formatSkillName('speech_therapy'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="dementia_care" <?php echo isChecked('dementia_care', $selectedSkills); ?>> <?php echo formatSkillName('dementia_care'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="alzheimers_care" <?php echo isChecked('alzheimers_care', $selectedSkills); ?>> <?php echo formatSkillName('alzheimers_care'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="housekeeping" <?php echo isChecked('housekeeping', $selectedSkills); ?>> <?php echo formatSkillName('housekeeping'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="exercise_assistance" <?php echo isChecked('exercise_assistance', $selectedSkills); ?>> <?php echo formatSkillName('exercise_assistance'); ?></label>
                            <label><input type="checkbox" class="checkbox" name="skills[]" value="travel_assistance" <?php echo isChecked('travel_assistance', $selectedSkills); ?>> <?php echo formatSkillName('travel_assistance'); ?></label>
                        </div>
                        <small>Select multiple skills</small>
                    </div>
                    <div id="selectedSkills"></div>
                </div>
                
                <div class="form-group">
                    <label><span style="color: red">*</span> Payment Details</label>
                    <div class="payment-inputs">
                        <div class="payment-input-group">
                            <label>Per Session (Rs.)</label>
                            <input type="number" min="0"
                                name="payment_per_session" 
                                
                                placeholder="Enter amount">
                        </div>
                        <div class="payment-input-group">
                            <label>Per Visit (Rs.)</label>
                            <input type="number" min="0"
                                name="payment_per_visit" 
                                
                                placeholder="Enter amount">
                        </div>
                    </div>
                    
                </div>

            </div>

            <div class="button-group">
                <button  onclick="handleAdd(event)" class="savec-btn" >Save Changes</button>
                <a href="<?php echo URLROOT; ?>/caregivers/viewmyProfile" class="cancel-btn">Cancel</a>
            </div>
        </form>

    </div>
    
    <div id="addModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>CONFIRM CHANGES</h2>
            <p>Are you sure you want to save the changes to your profile?</p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="submitAdd()">Yes, Save</button>
                <button class="modal-cancel-btn" onclick="closeAddModal()">Cancel</button>
            </div>
        </div>
    </div>
</page-body-container>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Initialize all dropdown functionality
    function setupDropdowns() {
        // Skills dropdown
        const skillsBtn = document.querySelector('.skills-btn');
        if (skillsBtn) {
            skillsBtn.addEventListener('click', function() {
                this.parentElement.classList.toggle('active');
            });
        }

        // Regions dropdown
        const regionsBtn = document.querySelector('.regions-btn');
        if (regionsBtn) {
            regionsBtn.addEventListener('click', function() {
                this.parentElement.classList.toggle('active');
            });
        }

        // Specializations dropdown
        const specializationsBtn = document.querySelector('.specializations-btn');
        if (specializationsBtn) {
            specializationsBtn.addEventListener('click', function() {
                this.parentElement.classList.toggle('active');
            });
        }
    }

    // Initialize all checkbox functionality
    function setupCheckboxes() {
        const checkboxes = document.querySelectorAll('.checkbox');
        const regionCheckboxes = document.querySelectorAll('.region-checkbox');
        const specializationCheckboxes = document.querySelectorAll('.specialization-checkbox');
        const selectedSkills = document.getElementById('selectedSkills');
        const selectedRegions = document.getElementById('selectedRegions');
        const selectedSpecializations = document.getElementById('selectedSpecializations');

        // Update all selected items display
        function updateSelectedItems() {
            // Update skills display
            if (selectedSkills) {
                selectedSkills.innerHTML = '';
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const skill = document.createElement('div');
                        skill.className = 'selected-skill';
                        skill.innerHTML = `<span>${checkbox.parentElement.textContent.trim()}</span>`;
                        selectedSkills.appendChild(skill);
                    }
                });
            }

            // Update regions display
            if (selectedRegions) {
                selectedRegions.innerHTML = '';
                regionCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const region = document.createElement('div');
                        region.className = 'selected-region';
                        region.innerHTML = `<span>${checkbox.parentElement.textContent.trim()}</span>`;
                        selectedRegions.appendChild(region);
                    }
                });
            }

            // Update specializations display
            if (selectedSpecializations) {
                selectedSpecializations.innerHTML = '';
                specializationCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const specialization = document.createElement('div');
                        specialization.className = 'selected-specialization';
                        specialization.innerHTML = `<span>${checkbox.parentElement.textContent.trim()}</span>`;
                        selectedSpecializations.appendChild(specialization);
                    }
                });
            }
        }

        // Add event listeners to all checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedItems);
        });

        regionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedItems);
        });

        specializationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedItems);
        });

        // Initial update on page load
        updateSelectedItems();
    }

    // Modal functions
    function handleAdd(event) {
        event.preventDefault();
        const modal = document.getElementById("addModal");
        if (modal) {
            modal.style.display = "block";
            document.body.style.overflow = "hidden";
        }
    }

    function submitAdd() {
        document.getElementById('editprofileForm').submit();
        closeAddModal();
    }

    function closeAddModal() {
        const modal = document.getElementById("addModal");
        if (modal) {
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        }
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setupDropdowns();
        setupCheckboxes();
        
        // Outside click handler for modal
        window.addEventListener("click", function(event) {
            const addModal = document.getElementById("addModal");
            if (addModal && event.target === addModal) {
                closeAddModal();
            }
        });
    });
</script>

<?php require APPROOT . '/views/includes/footer.php' ?>