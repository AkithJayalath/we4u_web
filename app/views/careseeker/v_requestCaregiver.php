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
                <div class="request-header-left-left">
                    <div class="circle image1"><img src="https://media.istockphoto.com/id/1759448630/photo/happy-caucasian-young-student-female-looking-at-camera-enjoying-with-a-perfect-white-teeth.jpg?s=612x612&w=0&k=20&c=KbfDI3FjAdGYK5QxTx3PJdxFyx9ZNgvOBd0P7E3Ah38=" alt="Profile"  /></div>
                    <div class="circle image1"><img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Profile"  /></div>
                </div>
                <div class="request-header-left-right">
                    <div class="request-personal-info-profile">
                        <div class="request-personal-info-details">
                            <span class="request-personal-info-tag">Verfied</span>
                            <h2>Pawan Wickramarathne</h2>
                            <span class="request-email">pawanwick@gmail.com</span>
                            <p class="consultant-rating">
                                <span class="rating-stars" id="rating-stars"></span>
                            </p>
                            <p>29 years</p>
                            <p>Male</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="request-header-right">
                <button class="request-send-button">
                    <i class="fas fa-paper-plane"></i> Send Request
                </button>
                <button class="request-cancel-button" onclick="window.location.href='<?php echo URLROOT; ?>/careseeker/viewCaregiverProfile';">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
        <div class="request-info-section">
            <div class="request-other-concern-section">
                <div class="request-other-concern-section-content">
                    <form class="request-form" action="<?php echo URLROOT; ?>/careseeker/requestCaregiver" method="POST">>
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
                                    <input type="hidden" name="caregiver_id" value="<?php echo $caregiver_id; ?>" />
                                    <input type="hidden" name="elder_profile" value="<?php echo $data['edlers']; ?>" />
                                    <label for="long-term-radio">Long Term Care</label>
                                </div>
                                <div class="duration-type-option">
                                    <input type="radio" id="short-term-radio" name="duration-type" value="short-term" onchange="toggleDurationFields()">
                                    <label for="short-term-radio">Short Term (One Day)</label>
                                </div>
                            </div>
                        </div>

    
                        <!-- Long Term Fields - initially hidden -->
                        <div id="long-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Long Term Care Schedule</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="from-date">Start Date</label>
                                    <input type="date" id="from-date" />
                                </div>
                                <div class="form-group">
                                    <label for="to-date">End Date</label>
                                    <input type="date" id="to-date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="frequency">Frequency</label>
                                <select id="frequency" onchange="toggleFrequencyOptions()">
                                    <option value="" disabled selected>Select frequency</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                </select>
                            </div>
                        </div>

                        <!-- Weekly Options - initially hidden -->
                        <div id="weekly-options" class="animated-section form-section">
                            <div class="form-section-title">Weekly Schedule</div>
                            <div class="form-group">
                                <label>Select Days</label>
                                <div class="weekday-checkboxes">
                                    <label>
                                        <input type="checkbox" name="selected_days[]" value="monday">
                                        <span>Monday</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="selected_days[]" value="tuesday">
                                        <span>Tuesday</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="selected_days[]" value="wednesday">
                                        <span>Wednesday</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="selected_days[]" value="thursday">
                                        <span>Thursday</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="selected_days[]" value="friday">
                                        <span>Friday</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="selected_days[]" value="saturday">
                                        <span>Saturday</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="selected_days[]" value="sunday">
                                        <span>Sunday</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Short Term Fields - initially hidden -->
                        <div id="short-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Short Term Care Date</div>
                            <div class="form-group">
                                <label for="specific-date">Select Date</label>
                                <input type="date" id="specific-date" />
                            </div>
                        </div>

                        <!-- Time Slots - for both long and short term -->
                        <div id="time-slots-section" class="animated-section form-section">
                            <div class="form-section-title">Care Time Slots</div>
                            <div class="form-group">
                                <label>Select Time Slots (Choose one or more)</label>
                                <div class="time-slot-checkboxes">
                                    <label>
                                        <input type="checkbox" name="timeslot" value="full-day">
                                        <span>Full Day (8am-8pm)</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="timeslot" value="morning">
                                        <span>Morning (8am-12pm)</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="timeslot" value="evening">
                                        <span>Evening (12pm-6pm)</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="timeslot" value="night">
                                        <span>Night (6pm-10pm)</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="timeslot" value="overnight">
                                        <span>Overnight (10pm-8am)</span>
                                    </label>
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
                                    <textarea id="additional_notes" name="additional_notes"placeholder="Any additional information or special requirements..."></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit">Send Request</button>
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
</style>

<script>
// Initialize display states when page loads
document.addEventListener('DOMContentLoaded', function() {
    const longTermFields = document.getElementById('long-term-fields');
    const shortTermFields = document.getElementById('short-term-fields');
    const timeSlotsSection = document.getElementById('time-slots-section');
    const weeklyOptions = document.getElementById('weekly-options');
    
    // Set initial display state
    longTermFields.style.display = 'none';
    shortTermFields.style.display = 'none';
    timeSlotsSection.style.display = 'none';
    weeklyOptions.style.display = 'none';
    
    // Initialize time slot selection handling
    handleTimeSlotSelection();
});

function toggleDurationFields() {
    // Get the selected duration type
    const durationType = document.querySelector('input[name="duration-type"]:checked')?.value;
    
    // Get all relevant sections
    const longTermFields = document.getElementById('long-term-fields');
    const shortTermFields = document.getElementById('short-term-fields');
    const timeSlotsSection = document.getElementById('time-slots-section');
    const weeklyOptions = document.getElementById('weekly-options');
    
    // Hide all sections first
    longTermFields.classList.remove('show');
    shortTermFields.classList.remove('show');
    timeSlotsSection.classList.remove('show');
    weeklyOptions.classList.remove('show');
    
    // Add display:none after transition completes
    setTimeout(() => {
        if (!longTermFields.classList.contains('show')) longTermFields.style.display = 'none';
        if (!shortTermFields.classList.contains('show')) shortTermFields.style.display = 'none';
        if (!timeSlotsSection.classList.contains('show')) timeSlotsSection.style.display = 'none';
        if (!weeklyOptions.classList.contains('show')) weeklyOptions.style.display = 'none';
    }, 300); // Match transition duration
    
    // Show relevant sections based on selection with a small delay for animation
    if (durationType) {
        setTimeout(() => {
            if (durationType === 'long-term') {
                longTermFields.style.display = 'block';
                setTimeout(() => longTermFields.classList.add('show'), 10);
                // Reset frequency dropdown
                document.getElementById('frequency').selectedIndex = 0;
            } else if (durationType === 'short-term') {
                shortTermFields.style.display = 'block';
                timeSlotsSection.style.display = 'block';
                setTimeout(() => {
                    shortTermFields.classList.add('show');
                    timeSlotsSection.classList.add('show');
                }, 10);
            }
        }, 50);
    }
}

function toggleFrequencyOptions() {
    const frequency = document.getElementById('frequency').value;
    const weeklyOptions = document.getElementById('weekly-options');
    const timeSlotsSection = document.getElementById('time-slots-section');
    
    weeklyOptions.classList.remove('show');
    timeSlotsSection.classList.remove('show');
    
    // Add display:none after transition completes
    setTimeout(() => {
        if (!weeklyOptions.classList.contains('show')) weeklyOptions.style.display = 'none';
        if (!timeSlotsSection.classList.contains('show')) timeSlotsSection.style.display = 'none';
    }, 300); // Match transition duration
    
    if (frequency) {
        setTimeout(() => {
            if (frequency === 'daily') {
                timeSlotsSection.style.display = 'block';
                setTimeout(() => timeSlotsSection.classList.add('show'), 10);
            } else if (frequency === 'weekly') {
                weeklyOptions.style.display = 'block';
                timeSlotsSection.style.display = 'block';
                setTimeout(() => {
                    weeklyOptions.classList.add('show');
                    timeSlotsSection.classList.add('show');
                }, 10);
            }
        }, 50);
    }
}

function handleTimeSlotSelection() {
    const fullDayCheckbox = document.querySelector('input[name="timeslot"][value="full-day"]');
    const otherTimeSlots = document.querySelectorAll('input[name="timeslot"]:not([value="full-day"])');
    
    // When full day is selected, disable other options
    fullDayCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Disable and uncheck other options
            otherTimeSlots.forEach(slot => {
                slot.checked = false;
                slot.disabled = true;
                slot.parentElement.classList.add('disabled-option');
            });
        } else {
            // Re-enable other options
            otherTimeSlots.forEach(slot => {
                slot.disabled = false;
                slot.parentElement.classList.remove('disabled-option');
            });
        }
    });
    
    // When any other option is selected, check if we need to disable full day
    otherTimeSlots.forEach(slot => {
        slot.addEventListener('change', function() {
            // If any other slot is checked, disable full day
            const anyOtherChecked = Array.from(otherTimeSlots).some(s => s.checked);
            if (anyOtherChecked) {
                fullDayCheckbox.checked = false;
                fullDayCheckbox.disabled = true;
                fullDayCheckbox.parentElement.classList.add('disabled-option');
            } else {
                fullDayCheckbox.disabled = false;
                fullDayCheckbox.parentElement.classList.remove('disabled-option');
            }
        });
    });
}
</script>

<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>