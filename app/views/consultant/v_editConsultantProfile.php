<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/editCaregiverProfile.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>

    <div class="request-info">


<!-- Container -->
<div class="view-requests-m-c-r-container">

  <div class="view-requests-m-c-r-table-container">
            <h2>Edit Profile</h2>
        </div>

        <form id="editprofileForm" action="<?php echo URLROOT; ?>/consultant/editmyProfile" method="POST" enctype="multipart/form-data">
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
                    <label><span style="color: red">*</span> Payment Details</label>
                    <div class="payment-inputs">
                        <div class="payment-input-group">
                            <label>Per Hour (Rs.)</label>
                            <input type="number" min="0"
                                name="payment_per_hour" 
                                
                                placeholder="Enter amount">
                        </div>
                    </div>
                    
                </div>

            </div>

            <div class="button-group">
                <button  onclick="handleAdd(event)" class="savec-btn" >Save Changes</button>
                <a href="<?php echo URLROOT; ?>/consultant/viewmyProfile" class="cancel-btn">Cancel</a>
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
        const selectedRegions = document.getElementById('selectedRegions');
        const selectedSpecializations = document.getElementById('selectedSpecializations');

        // Update all selected items display
        function updateSelectedItems() {
        

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