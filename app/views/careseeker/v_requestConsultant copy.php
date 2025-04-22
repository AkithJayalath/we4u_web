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
    $ConsultantprofilePic = !empty($data['consultant']->profile_picture) 
        ? URLROOT . '/public/images/profile_imgs/' . $data['consultant']->profile_picture
        : URLROOT . '/public/images/def_profile_pic2.jpg';
    ?>
    
    <img src="<?= $ConsultantprofilePic ?>" alt="Profile Picture" />
</div>
                </div>
                <div class="request-header-left-right">
                    <div class="request-personal-info-profile">
                        <div class="request-personal-info-details">
                            <span class="request-personal-info-tag">Verfied</span>
                            <h2><?php echo $data['consultant']->username; ?></h2>
                            <span class="request-email"><?php echo $data['consultant']->email; ?></span>
                            <p class="consultant-rating">
                                <span class="rating-stars" id="rating-stars"></span>
                            </p>
                            <p><?php echo $data['age']; ?></p>
                            <p><?php echo $data['consultant']->gender; ?></p>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="request-info-section">
            <div class="request-other-concern-section">
                <div class="request-other-concern-section-content">
                <form class="request-form" action="<?php echo URLROOT; ?>/careseeker/requestConsultant/<?php echo $data['consultant_id']; ?>" method="POST">
                        <!-- Elder Profile -->
                        <div class="form-section">
                            <div class="form-section-title">Elder Details</div>
                            <div class="form-group">
                                <label for="elder-profile">Select Elder Profile</label>
                                <select id="elder-profile" name="elder_profile" class="<?php echo (!empty($data['elder_profile_err'])) ? 'is-invalid' : ''; ?>">
                                    <option value="" disabled <?php echo empty($data['elder_profile']) ? 'selected' : ''; ?>>Select a profile</option>
                                    <?php foreach($data['elders'] as $elder): ?>
                                        <option value="<?php echo $elder->elder_id; ?>" <?php echo (isset($data['elder_profile']) && $data['elder_profile'] == $elder->elder_id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($elder->first_name . ' ' . $elder->last_name); ?> 
                                            (<?php echo htmlspecialchars($elder->age); ?> years)
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if(empty($data['elders'])): ?>
                                        <option value="" disabled>No profiles available. Please create an elder profile first.</option>
                                    <?php endif; ?>
                                </select>
                                <?php if(!empty($data['error']) && strpos($data['error'], 'elder profile') !== false): ?>
                                    <div class="field-error">Please select an elder profile</div>
                                <?php endif; ?>
                            </div>

                            <?php if(empty($data['elders'])): ?>
                                <div class="create-profile-prompt">
                                    <p>You don't have any elder profiles yet. <a href="<?php echo URLROOT; ?>/careseeker/createElderProfile">Create a profile</a> to continue.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Date and Time Selection -->
                        <div class="form-section">
                            <div class="form-section-title">Appointment Date & Time</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="appointment-date">Select Date</label>
                                    <input type="date" id="appointment-date" name="appointment_date" class="<?php echo (!empty($data['appointment_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['appointment_date'] ?? ''; ?>" required/>
                                    <?php if(!empty($data['error']) && strpos($data['error'], 'date') !== false): ?>
                                        <div class="field-error">Please select a date</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Select Time Slot</label>
                                    <div class="time-selector">
                                        <div class="time-selector-row">
                                            <div class="time-selection">
                                                <label for="from-time">From</label>
                                                <select id="from-time" name="from_time" class="<?php echo (!empty($data['from_time_err'])) ? 'is-invalid' : ''; ?>" required onchange="updateToTimeOptions(); calculatePayment();">
                                                    <option value="" disabled <?php echo empty($data['from_time']) ? 'selected' : ''; ?>>Select start time</option>
                                                    <?php 
                                                        for ($i = 8; $i <= 21; $i++) {
                                                            $selected = (isset($data['from_time']) && $data['from_time'] == $i) ? 'selected' : '';
                                                            $displayHour = $i > 12 ? $i - 12 : $i;
                                                            $ampm = $i >= 12 ? 'PM' : 'AM';
                                                            echo "<option value=\"$i\" $selected>$displayHour:00 $ampm</option>";
                                                        }
                                                    ?>
                                                </select>
                                                <?php if(!empty($data['error']) && strpos($data['error'], 'start time') !== false): ?>
                                                    <div class="field-error">Please select a start time</div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="time-selection">
                                                <label for="to-time">To</label>
                                                <select id="to-time" name="to_time" class="<?php echo (!empty($data['to_time_err'])) ? 'is-invalid' : ''; ?>" required onchange="calculatePayment();">
                                                    <option value="" disabled <?php echo empty($data['to_time']) ? 'selected' : ''; ?>>Select end time</option>
                                                    <!-- Options will be populated by JavaScript -->
                                                </select>
                                                <?php if(!empty($data['error']) && strpos($data['error'], 'end time') !== false): ?>
                                                    <div class="field-error">Please select an end time</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
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
                                    <input type="text" id="payment-details" readonly />
                                    <input type="hidden" id="total-amount" name="total_amount" value="<?php echo $data['total_amount'] ?? 0; ?>" />
                                    <?php if(!empty($data['error']) && strpos($data['error'], 'payment') !== false): ?>
                                        <div class="field-error">Invalid payment amount</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-section">
                            <div class="form-section-title">Additional Information</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="expected_services">Expected Services</label>
                                    <textarea id="expected_services" name="expected_services" placeholder="Services you expect from the provider"><?php echo $data['expected_services'] ?? ''; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="additional_notes">Additional Notes</label>
                                    <textarea id="additional_notes" name="additional_notes" placeholder="Any additional information or special requirements..."><?php echo $data['additional_notes'] ?? ''; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="request-header-right">
                            <button class="request-send-button">
                                <i class="fas fa-paper-plane"></i> Send Request
                            </button>
                            <button class="request-cancel-button" type="button" onclick="location.href='<?php echo URLROOT; ?>/careseeker/viewConsultantProfile/<?php echo $data['consultant_id']; ?>'">
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
/* Time selector styles */
.time-selector {
    width: 100%;
}

.time-selector-row {
    display: flex;
    gap: 20px;
}

.time-selection {
    flex: 1;
}

.time-selection select {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    background-color: #fff;
}

.time-selection label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

/* Highlight selected time ranges */
.selected-time-range {
    background-color: #e6f7ff;
    border-color: #1890ff;
}

/* Price information styling */
#payment-details {
    font-weight: 600;
    color: #333;
    background-color: #f9f9f9;
}

/* Add styles for the error message display */
.error-alert {
    background-color: #f8d7da;
    color: #721c24;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
    display: flex;
    align-items: center;
    font-size: 14px;
}

.error-alert i {
    margin-right: 10px;
    font-size: 16px;
}

.field-error {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    margin-bottom: 5px;
}

select.is-invalid, 
input.is-invalid {
    border-color: #dc3545;
}
</style>

<script>
// Define price per hour constant (this would normally come from the database)
const PRICE_PER_HOUR = <?= (int) ($data['consultant']->payment_details ?? 0) ?>;

document.addEventListener('DOMContentLoaded', function () {
    // Set minimum date to today
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    
    const formattedToday = `${yyyy}-${mm}-${dd}`;
    document.getElementById('appointment-date').min = formattedToday;
    
    // Initialize the to-time dropdown based on from-time
    updateToTimeOptions();
    
    // Set initial payment details
    document.getElementById('payment-details').value = `Please select time to see cost`;
    
    // Add event listeners for form fields to clear errors
    document.getElementById('elder-profile').addEventListener('change', function() {
        clearErrorMessageFor('elder profile');
    });
    
    document.getElementById('appointment-date').addEventListener('change', function() {
        clearErrorMessageFor('date');
        calculatePayment();
    });
    
    document.getElementById('from-time').addEventListener('change', function() {
        clearErrorMessageFor('start time');
        updateToTimeOptions();
        calculatePayment();
    });
    
    document.getElementById('to-time').addEventListener('change', function() {
        clearErrorMessageFor('end time');
        calculatePayment();
    });

    // Pre-select to-time option if we have a saved value
    const savedToTime = '<?php echo $data["to_time"] ?? ""; ?>';
    if (savedToTime) {
        setTimeout(() => {
            const toTimeSelect = document.getElementById('to-time');
            if (toTimeSelect && toTimeSelect.options.length > 0) {
                for (let i = 0; i < toTimeSelect.options.length; i++) {
                    if (toTimeSelect.options[i].value === savedToTime) {
                        toTimeSelect.options[i].selected = true;
                        break;
                    }
                }
                calculatePayment();
            }
        }, 100); // Small delay to ensure options are populated
    }
});

function updateToTimeOptions() {
    const fromTime = parseInt(document.getElementById('from-time').value) || 8;
    const toTimeSelect = document.getElementById('to-time');
    const savedToTime = '<?php echo $data["to_time"] ?? ""; ?>';
    
    // Clear existing options
    toTimeSelect.innerHTML = '<option value="" disabled selected>Select end time</option>';
    
    // Add options for hours after the start time
    for (let i = fromTime + 1; i <= 22; i++) {
        const hour = i;
        const displayHour = hour > 12 ? hour - 12 : hour;
        const ampm = hour >= 12 ? 'PM' : 'AM';
        
        const option = document.createElement('option');
        option.value = hour;
        option.textContent = `${displayHour}:00 ${ampm}`;
        
        // If we have a saved to_time value and it's valid, select it
        if (savedToTime && parseInt(savedToTime) === hour) {
            option.selected = true;
        }
        
        toTimeSelect.appendChild(option);
    }
    
    // Reset payment details if from-time changes
    calculatePayment();
}

function calculatePayment() {
    const fromTime = parseInt(document.getElementById('from-time').value);
    const toTime = parseInt(document.getElementById('to-time').value);
    const paymentDetails = document.getElementById('payment-details');
    const totalAmountField = document.getElementById('total-amount');
    
    // Only calculate if both times are selected
    if (!isNaN(fromTime) && !isNaN(toTime)) {
        const hours = toTime - fromTime;
        const totalAmount = hours * PRICE_PER_HOUR;
        
        // Format the payment details
        paymentDetails.value = `Rs.${PRICE_PER_HOUR} × ${hours} hours = Rs.${totalAmount}`;
        
        // Store the total amount in the hidden field
        totalAmountField.value = totalAmount;
    } else {
        // If times aren't selected yet, show a message
        paymentDetails.value = `Please select time to see cost`;
        totalAmountField.value = 0;
    }
}

// Function to clear all error messages
function clearErrorMessages() {
    const errorElements = document.querySelectorAll('.field-error');
    errorElements.forEach(element => {
        element.style.display = 'none';
    });
}

// Function to clear specific error message containing the given text
function clearErrorMessageFor(fieldType) {
    const errorElements = document.querySelectorAll('.field-error');
    errorElements.forEach(element => {
        if (element.textContent.toLowerCase().includes(fieldType.toLowerCase())) {
            element.style.display = 'none';
        }
    });
}
</script>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>