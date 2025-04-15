<?php
$required_styles = [
    'careseeker/requestCaregiver',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="request-caregiver">
        <div class="request-heading">
            <p>Send Care Request</p>
        </div>
        
        <!-- Personal info section -->
        <div class="request-header">
            <div class="request-header-left">
            <?php 
    
    // Determine which image to display
    $CareseekerprofilePic = !empty($data['careseeker']->profile_picture) 
        ? URLROOT . '/public/images/profile_imgs/' . $data['careseeker']->profile_picture
        : URLROOT . '/public/images/def_profile_pic2.jpg';
    ?>
                <div class="request-header-left-left">
                    <div class="circle image1"><img src="<?= $CareseekerprofilePic ?>" alt="Profile"  /></div>
                    <div class="circle image1">
    <?php 
    
    // Determine which image to display
    $CaregiverprofilePic = !empty($data['caregiver']->profile_picture) 
        ? URLROOT . '/public/images/profile_imgs/' . $data['caregiver']->profile_picture
        : URLROOT . '/public/images/def_profile_pic2.jpg';
    ?>
    
    <img src="<?= $CaregiverprofilePic ?>" alt="Profile Picture" />
</div>
                </div>
                <div class="request-header-left-right">
                    <div class="request-personal-info-profile">
                        <div class="request-personal-info-details">
                            <span class="request-personal-info-tag">Verfied</span>
                            <h2><?php echo $data['caregiver']->username; ?></h2>
                            <span class="request-email"><?php echo $data['caregiver']->email; ?></span>
                            <p class="consultant-rating">
                                <span class="rating-stars" id="rating-stars"></span>
                            </p>
                            <p><?php echo $data['age']; ?></p>
                            <p><?php echo $data['caregiver']->gender; ?></p>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="request-info-section">
            <div class="request-other-concern-section">
                <div class="request-other-concern-section-content">
                <form class="request-form" action="<?php echo URLROOT; ?>/careseeker/requestCaregiver/<?php echo $data['caregiver_id']; ?>" method="POST">
                        <!-- Elder Profile -->
                        <div class="form-section">
                            <div class="form-section-title">Elder Details</div>
                            <div class="form-group">
                                <label for="elder-profile">Select Elder Profile</label>
                                <select id="elder-profile" name="elder_profile">
                                    <option value="" disabled selected>Select a profile</option>
                                    <?php foreach($data['elders'] as $elder): ?>
                                        <option value="<?php echo $elder->elder_id; ?>">
                                            <?php echo htmlspecialchars($elder->first_name . ' ' . $elder->last_name); ?> 
                                            (<?php echo htmlspecialchars($elder->age); ?> years)
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if(empty($data['elders'])): ?>
                                        <option value="" disabled>No profiles available. Please create an elder profile first.</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <?php if(empty($data['elders'])): ?>
                                <div class="create-profile-prompt">
                                    <p>You don't have any elder profiles yet. <a href="<?php echo URLROOT; ?>/careseeker/createElderProfile">Create a profile</a> to continue.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Duration Type Selection -->
                        <div class="form-section">
                            <div class="form-section-title">Care Duration</div>
                            <div class="duration-type-options">
                                <div class="duration-type-option">
                                    <input type="radio" id="long-term-radio" name="duration-type" value="long-term" onchange="toggleDurationFields()">
                                    <label for="long-term-radio">Long Term Care</label>
                                </div>
                                <div class="duration-type-option">
                                    <input type="radio" id="short-term-radio" name="duration-type" value="short-term" onchange="toggleDurationFields()">
                                    <label for="short-term-radio">Short Term (One Day)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Long Term Fields -->
                        <div id="long-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Long Term Care Schedule</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="from-date">Start Date</label>
                                    <input type="date" id="from-date"  name="from_date"/>
                                </div>
                                <div class="form-group">
                                    <label for="to-date">End Date</label>
                                    <input type="date" id="to-date" name="to_date"/>
                                </div>
                            </div>
                        </div>

                        <!-- Short Term Fields -->
                        <div id="short-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Short Term Care Date</div>
                            <div class="form-group">
                                <label for="from_date">Select Date</label>
                                <input type="date" id="from_date_short" name="from_date_short"/>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div id="time-slots-section" class="animated-section form-section">
                            <div class="form-section-title">Care Time Slots</div>
                            <div class="form-group">
                                <label>Select Time Slots (Choose one or more)</label>
                                <div class="time-slot-checkboxes">
                                    <label><input type="checkbox" name="timeslot[]" value="full-day" id="full-day-checkbox" onchange="handleFullDaySelection()"><span>Full Day (8am-8pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="morning" class="other-slot"><span>Morning (8am-12pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="evening" class="other-slot"><span>Evening (12pm-6pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="night" class="other-slot"><span>Night (6pm-10pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="overnight" class="other-slot"><span>Overnight (10pm-8am)</span></label>
                                </div>
                            </div>
                        </div>

                        <!-- Service Details -->
                        <div class="form-section">
                            <div class="form-section-title">Service Information</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="service">Service Type</label>
                                    <input type="text" id="service" placeholder="Consultation" readonly/>
                                </div>
                                <div class="form-group">
                                    <label for="payment-details">Payment Details</label>
                                    <input type="text" id="payment-details" placeholder="Rs.4000 per visit + Rs.400 per session" readonly />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-section">
                            <div class="form-section-title">Additional Information</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="expected_services" >Expected Services</label>
                                    <textarea id="expected_services" name="expected_services" placeholder="Services you expect from the provider"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="additional_notes">Additional Notes</label>
                                    <textarea id="additional_notes" name="additional_notes" placeholder="Any additional information or special requirements..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="request-header-right">
                            <button class="request-send-button">
                                <i class="fas fa-paper-plane"></i> Send Request
                            </button>
                            <button class="request-cancel-button" type="button" onclick="window.history.back()">
    <i class="fas fa-times"></i> Cancel
</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<style>
/* Add styles for disabled time slot options */
.time-slot-checkboxes label.disabled-option {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #f0f0f0;
    border-color: #ddd;
}

.time-slot-checkboxes label.disabled-option:hover {
    background-color: #f0f0f0;
    border-color: #ddd;
}

.time-slot-checkboxes input[type="checkbox"]:disabled + span {
    color: #999;
}

#long-term-fields, #short-term-fields, #time-slots-section {
    transition: all 0.4s ease-in-out;
    opacity: 0;
    transform: translateY(-20px);
    height: 0;
    overflow: hidden;
    margin: 0;
    padding: 0;
}

#long-term-fields.show,
#short-term-fields.show,
#time-slots-section.show {
    opacity: 1;
    transform: translateY(0);
    height: auto;
    overflow: visible;
    margin-bottom: 20px;
    padding: 15px;
}

/* Style for the time slot checkboxes to make them stand out more */
.time-slot-checkboxes label {
    display: block;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.time-slot-checkboxes label:hover {
    background-color: #f5f5f5;
    border-color: #ccc;
}

.time-slot-checkboxes label.selected {
    background-color: #e6f7ff;
    border-color: #1890ff;
}

.time-slot-checkboxes label.disabled-option {
    opacity: 0.5;
    pointer-events: none;
    background-color: #f0f0f0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    toggleDurationFields(); // Handle pre-selected option (e.g., during form edit)
    
    // Check for any pre-selected checkboxes
    handleFullDaySelection();
});

function toggleDurationFields() {
    const longTermFields = document.getElementById('long-term-fields');
    const shortTermFields = document.getElementById('short-term-fields');
    const timeSlotsSection = document.getElementById('time-slots-section');
    const selectedType = document.querySelector('input[name="duration-type"]:checked')?.value;

    // First hide all sections with a smooth transition
    longTermFields.classList.remove('show');
    shortTermFields.classList.remove('show');
    timeSlotsSection.classList.remove('show');

    // After a short delay, show the appropriate sections
    setTimeout(() => {
        if (selectedType === 'long-term') {
            longTermFields.classList.add('show');
            timeSlotsSection.classList.add('show');
        } else if (selectedType === 'short-term') {
            shortTermFields.classList.add('show');
            timeSlotsSection.classList.add('show');
        }
    }, 300);
}

function handleFullDaySelection() {
    const fullDayCheckbox = document.getElementById('full-day-checkbox');
    const otherTimeSlots = document.querySelectorAll('.other-slot');
    const otherTimeFrames = document.querySelectorAll('.other-timeframe');
    
    if (fullDayCheckbox.checked) {
        // If Full Day is selected, disable other options
        otherTimeSlots.forEach(slot => {
            slot.checked = false;
            slot.disabled = true;
        });
        
        otherTimeFrames.forEach(frame => {
            frame.classList.add('disabled-option');
        });
    } else {
        // If Full Day is not selected, enable other options
        otherTimeSlots.forEach(slot => {
            slot.disabled = false;
        });
        
        otherTimeFrames.forEach(frame => {
            frame.classList.remove('disabled-option');
        });
    }
    
    // Highlight selected options
    document.querySelectorAll('.time-slot-checkboxes input[type="checkbox"]').forEach(checkbox => {
        const label = checkbox.closest('label');
        if (checkbox.checked && !checkbox.disabled) {
            label.classList.add('selected');
        } else {
            label.classList.remove('selected');
        }
    });
}
</script>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>