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
            <?php echo $data['error'] ?? ''; ?>
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
                            <p><?php echo $data['caregiver']->caregiver_type; ?> Term Care</p>
                           
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

                        <!-- Add this after the "Care Duration" section -->
<div class="form-section" id="calendar-section">
    <div class="form-section-title">Caregiver Availability</div>
    <div class="mini-calendar-container">
        <div id="mini-calendar"></div>
    </div>
</div>

<!-- FullCalendar 5.x Core and Required Packages -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<style>
    /* Mini calendar styles */
    .mini-calendar-container {
        height: 350px;
        margin-bottom: 20px;
    }
    
    /* Style for unavailable dates */
    .fc-day-unavailable {
        background-color: #ffeeee !important;
        cursor: not-allowed !important;
    }
    
    /* Style for selected date */
    .fc-day-selected {
        background-color: #e6f7ff !important;
        border: 2px solid #1890ff !important;
    }
</style>



                        <!-- Duration Type Selection -->
                        <div class="form-section">
                            <div class="form-section-title">Care Duration</div>
                            <div class="duration-type-options">
                                <div class="duration-type-option">
                                    <input type="radio" id="long-term-radio" name="duration-type" value="long-term" 
                                        <?php echo ($data['caregiver']->caregiver_type == 'short') ? 'disabled' : ''; ?>
                                        onchange="toggleDurationFields()">
                                    <label for="long-term-radio" <?php echo ($data['caregiver']->caregiver_type == 'short') ? 'class="disabled-option"' : ''; ?>>
                                        Long Term Care
                                    </label>
                                </div>
                                <div class="duration-type-option">
                                    <input type="radio" id="short-term-radio" name="duration-type" value="short-term" 
                                        <?php echo ($data['caregiver']->caregiver_type == 'long') ? 'disabled' : ''; ?>
                                        onchange="toggleDurationFields()">
                                    <label for="short-term-radio" <?php echo ($data['caregiver']->caregiver_type == 'long') ? 'class="disabled-option"' : ''; ?>>
                                        Short Term (One Day)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Long Term Fields -->
                        <div id="long-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Long Term Care Schedule</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="from-date">Start Date</label>
                                    <input type="date" id="from-date" name="from_date" onchange="calculatePayment()"/>
                                </div>
                                <div class="form-group">
                                    <label for="to-date">End Date</label>
                                    <input type="date" id="to-date" name="to_date" onchange="calculatePayment()"/>
                                </div>
                            </div>
                        </div>

                        <!-- Short Term Fields -->
                        <div id="short-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Short Term Care Date</div>
                            <div class="form-group">
                                <label for="from_date_short">Select Date</label>
                                <input type="date" id="from_date_short" name="from_date_short"/>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div id="time-slots-section" class="animated-section form-section">
                            <div class="form-section-title">Care Time Slots</div>
                            <div class="form-group">
                                <label>Select Time Slots (Choose one or more)</label>
                                <div class="time-slot-checkboxes">
                                    <label><input type="checkbox" name="timeslot[]" value="full-day" id="full-day-checkbox" onchange="handleFullDaySelection(); calculatePayment();"><span>Full Day (8am-8pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="morning" class="other-slot" onchange="calculatePayment()"><span>Morning (8am-12pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="evening" class="other-slot" onchange="calculatePayment()"><span>Evening (12pm-6pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="night" class="other-slot" onchange="calculatePayment()"><span>Night (6pm-10pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="overnight" class="other-slot" onchange="calculatePayment()"><span>Overnight (10pm-8am)</span></label>
                                </div>
                            </div>
                        </div>

                        <!-- Service Details -->
                        <div class="form-section">
                            <div class="form-section-title">Service Information</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="service">Service Type</label>
                                    <input type="text" id="service" placeholder="Caregiving" readonly/>
                                </div>
                                <div class="form-group">
                                    <label for="payment-details">Payment Details</label>
                                    <input type="text" id="payment-details" placeholder="Calculating..." readonly />
                                    <input type="hidden" id="total-payment" name="total_payment" value="0" />
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

.duration-type-option label.disabled-option {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Set default selection based on caregiver type
    const caregiverType = '<?php echo $data['caregiver']->caregiver_type; ?>';
    
    if (caregiverType === 'long') {
        document.getElementById('long-term-radio').checked = true;
    } else if (caregiverType === 'short') {
        document.getElementById('short-term-radio').checked = true;
    }
    
    toggleDurationFields(); // Handle pre-selected option
    handleFullDaySelection(); // Check for any pre-selected checkboxes
    calculatePayment(); // Calculate initial payment
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
            // Time slots are only available for short term
            timeSlotsSection.classList.remove('show');
        } else if (selectedType === 'short-term') {
            shortTermFields.classList.add('show');
            timeSlotsSection.classList.add('show');
        }
        
        calculatePayment(); // Recalculate payment when changing duration type
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
    
    calculatePayment(); // Recalculate payment when changing time slots
}

function calculatePayment() {
    const selectedType = document.querySelector('input[name="duration-type"]:checked')?.value;
    const paymentDetailsElement = document.getElementById('payment-details');
    const totalPaymentElement = document.getElementById('total-payment');
    let totalPayment = 0;
    
    // Get caregiver price info
    const pricePerDay = <?php echo isset($data['caregiver']->price_per_day) ? $data['caregiver']->price_per_day : 0; ?>;
    const pricePerSession = <?php echo isset($data['caregiver']->price_per_session) ? $data['caregiver']->price_per_session : 0; ?>;
    
    if (selectedType === 'long-term') {
        const fromDate = document.getElementById('from-date').value;
        const toDate = document.getElementById('to-date').value;
        
        if (fromDate && toDate) {
            // Calculate number of days
            const startDate = new Date(fromDate);
            const endDate = new Date(toDate);
            
            // Check if dates are valid
            if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime())) {
                // Calculate the difference in days
                const diffTime = endDate - startDate;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Include both start and end day
                
                if (diffDays > 0) {
                    totalPayment = diffDays * pricePerDay;
                    paymentDetailsElement.value = `Rs.${totalPayment} (${diffDays} days at Rs.${pricePerDay} per day)`;
                } else {
                    paymentDetailsElement.value = "Please select valid date range";
                }
            } else {
                paymentDetailsElement.value = "Please select valid dates";
            }
        } else {
            paymentDetailsElement.value = "Select dates to calculate payment";
        }
        
    } else if (selectedType === 'short-term') {
        // Count selected time slots
        const selectedSlots = document.querySelectorAll('input[name="timeslot[]"]:checked');
        
        if (selectedSlots.length > 0) {
            // Calculate based on number of slots selected
            let slotCount = 0;
            let slotNames = [];
            
            selectedSlots.forEach(slot => {
                if (slot.value === 'full-day') {
                    slotCount = 4; // Full day counts as 4 sessions
                    slotNames.push("Full Day");
                } else {
                    slotCount += 1;
                    slotNames.push(slot.value.charAt(0).toUpperCase() + slot.value.slice(1));
                }
            });
            
            totalPayment = slotCount * pricePerSession;
            paymentDetailsElement.value = `Rs.${totalPayment} (${slotNames.join(', ')} - ${slotCount} sessions at Rs.${pricePerSession} per session)`;
        } else {
            paymentDetailsElement.value = "Select time slots to calculate payment";
        }
    } else {
        paymentDetailsElement.value = "Select duration type to calculate payment";
    }
    
    // Update hidden field with calculated total
    totalPaymentElement.value = totalPayment;
}



//Edler profile selection error
// Add this to your existing script section at the bottom of the file
document.addEventListener('DOMContentLoaded', function() {
    // Get the form element
    const requestForm = document.querySelector('.request-form');
    
    // Add event listener for form submission
    requestForm.addEventListener('submit', function(event) {
        // Get the elder profile select element
        const elderProfileSelect = document.getElementById('elder-profile');
        
        // Check if a profile is selected
        if (!elderProfileSelect.value) {
            // Prevent form submission
            event.preventDefault();
            
            // Create error message if it doesn't exist
            let errorMsg = document.getElementById('elder-profile-error');
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.id = 'elder-profile-error';
                errorMsg.className = 'form-error';
                errorMsg.style.color = 'red';
                errorMsg.style.fontSize = '14px';
                errorMsg.style.marginTop = '5px';
                errorMsg.textContent = 'Please select an elder profile';
                
                // Insert error message after the select element
                elderProfileSelect.parentNode.appendChild(errorMsg);
            }
            
            // Highlight the select field
            elderProfileSelect.style.borderColor = 'red';
            
            // Scroll to the error
            elderProfileSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            // If valid, remove any existing error message
            const errorMsg = document.getElementById('elder-profile-error');
            if (errorMsg) {
                errorMsg.remove();
            }
            
            // Reset border color
            elderProfileSelect.style.borderColor = '';
        }
    });
    
    // Add change event listener to clear error when user selects a profile
    document.getElementById('elder-profile').addEventListener('change', function() {
        // Remove error message if it exists
        const errorMsg = document.getElementById('elder-profile-error');
        if (errorMsg) {
            errorMsg.remove();
        }
        
        // Reset border color
        this.style.borderColor = '';
    });
});
</script>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>

<!-- mini calander ekata -->
<script>


</script>