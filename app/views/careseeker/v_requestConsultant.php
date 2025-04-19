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

                        <!-- Date and Time Selection -->
                        <div class="form-section">
                            <div class="form-section-title">Appointment Date & Time</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="appointment-date">Select Date</label>
                                    <input type="date" id="appointment-date" name="appointment_date" required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Select Time Slot</label>
                                    <div class="time-selector">
                                        <div class="time-selector-row">
                                            <div class="time-selection">
                                                <label for="from-time">From</label>
                                                <select id="from-time" name="from_time" required onchange="updateToTimeOptions(); calculatePayment();">
                                                    <option value="" disabled selected>Select start time</option>
                                                    <option value="8">8:00 AM</option>
                                                    <option value="9">9:00 AM</option>
                                                    <option value="10">10:00 AM</option>
                                                    <option value="11">11:00 AM</option>
                                                    <option value="12">12:00 PM</option>
                                                    <option value="13">1:00 PM</option>
                                                    <option value="14">2:00 PM</option>
                                                    <option value="15">3:00 PM</option>
                                                    <option value="16">4:00 PM</option>
                                                    <option value="17">5:00 PM</option>
                                                    <option value="18">6:00 PM</option>
                                                    <option value="19">7:00 PM</option>
                                                    <option value="20">8:00 PM</option>
                                                    <option value="21">9:00 PM</option>
                                                </select>
                                            </div>
                                            <div class="time-selection">
                                                <label for="to-time">To</label>
                                                <select id="to-time" name="to_time" required onchange="calculatePayment();">
                                                    <option value="" disabled selected>Select end time</option>
                                                    <!-- Options will be populated by JavaScript -->
                                                </select>
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
                                    <input type="hidden" id="total-amount" name="total_amount" value="0" />
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
});

function updateToTimeOptions() {
    const fromTime = parseInt(document.getElementById('from-time').value) || 8;
    const toTimeSelect = document.getElementById('to-time');
    
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
</script>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>