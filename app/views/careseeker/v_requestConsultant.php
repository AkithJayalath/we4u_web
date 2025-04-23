<?php
$required_styles = [
    'careseeker/requestConsultant',
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
            <!-- <div><?php echo $data['error'] ?></div> -->
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

                        <!-- Add this after the Elder Details section and before the current Date & Time Selection section -->
<div class="form-section" id="calendar-section">
    <div class="form-section-title">Consultant Availability</div>
    <?php if (!empty($data['error']) && strpos($data['error'], 'Please select a different time slot') !== false): ?>
        <div class="calander-field-error"><?php echo $data['error']?></div>
    <?php endif; ?>
    <div class="calendar-container">
        <div class="mini-calendar">
            <div id="mini-calendar"></div>
        </div>
        <div class="time-slots-display" id="time-slots-display">
            <h3>Available Time Slots</h3>
            <p class="select-date-message">Please select a date to view available time slots</p>
            <p class="select-date-message">Available Time Slots are indicated in the calendar. Make sure you are selecting an available date. Select an available date to view times that are available.</p>
            <div class="time-slot-list" id="time-slot-list" style="display: none;"></div>
        </div>
    </div>
</div>

<!-- FullCalendar 5.x Core and Required Packages -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    // Initialize mini calendar for consultant availability
    document.addEventListener('DOMContentLoaded', function() {
        // Get consultant ID from the URL
        const consultantId = <?php echo $data['consultant_id']; ?>;
        
        // Fetch consultant's availability data via AJAX
        fetchConsultantAvailability(consultantId);
        
        // Make sure calendar section is visible
        document.getElementById('calendar-section').classList.add('show');
    });

    function fetchConsultantAvailability(consultantId) {
        // Define the URL with URLROOT
        const url = `<?php echo URLROOT; ?>/careseeker/getConsultantAvailability/${consultantId}`;
        
        // AJAX call to get consultant's availability
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                initializeCalendar(data);
            })
            .catch(error => {
                // Initialize with empty data as fallback
                initializeCalendar({availabilityPatterns: [], availabilityInstances: []});
            });
    }

    function initializeCalendar(availabilityData) {
        const calendarEl = document.getElementById('mini-calendar');
        
        // Process the availability data to mark available dates
        const processedDates = processAvailabilityDates(availabilityData);
        
        // Store the processed dates data for later use
        window.processedDatesData = processedDates;
        
        // Initialize the calendar
        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: ''
            },
            initialView: 'dayGridMonth',
            height: 'auto',
            selectable: true,
            unselectAuto: false,
            dateClick: function(info) {
                handleDateSelection(info, processedDates);
            },
            dayCellDidMount: function(info) {
                const date = info.date;
                const formattedDate = formatDate(date);
                
                // Check if date is in the past
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (date < today) {
                    info.el.classList.add('fc-day-past');
                    return;
                }
                
                // Check if date is available - make available days grey colored
                if (processedDates.availableDays.includes(formattedDate)) {
                    info.el.classList.add('fc-day-available');
                    // Add grey color to available days
                    info.el.style.backgroundColor = '#f0f0f0';
                }
            }
        });
        
        calendar.render();
    }

    function processAvailabilityDates(availabilityData) {
        const processedDates = {
            availableDays: [], // Days with availability
            availableTimeSlots: {} // Available time slots for each day
        };
        
        // Get today's date for comparison
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Process availability patterns (recurring availability)
        if (availabilityData.availabilityPatterns && availabilityData.availabilityPatterns.length) {
            availabilityData.availabilityPatterns.forEach(pattern => {
                // Get pattern details
                const dayOfWeek = parseInt(pattern.day_of_week);
                const startDate = new Date(pattern.start_date);
                const endDate = new Date(pattern.end_date);
                
                // Generate all dates that match this pattern's day of week within the date range
                const currentDate = new Date(startDate);
                while (currentDate <= endDate) {
                    if (currentDate.getDay() === dayOfWeek && currentDate >= today) {
                        const formattedDate = formatDate(currentDate);
                        
                        // Add to available days if not already there
                        if (!processedDates.availableDays.includes(formattedDate)) {
                            processedDates.availableDays.push(formattedDate);
                        }
                        
                        // Initialize time slots for this date if not already done
                        if (!processedDates.availableTimeSlots[formattedDate]) {
                            processedDates.availableTimeSlots[formattedDate] = [];
                        }
                        
                        // Add the time slot
                        processedDates.availableTimeSlots[formattedDate].push({
                            start: pattern.start_time,
                            end: pattern.end_time
                        });
                    }
                    
                    // Move to next day
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            });
        }
        
        // Process specific availability instances
        if (availabilityData.availabilityInstances && availabilityData.availabilityInstances.length) {
            availabilityData.availabilityInstances.forEach(instance => {
                const instanceDate = new Date(instance.available_date);
                
                // Skip past dates
                if (instanceDate < today) return;
                
                const formattedDate = formatDate(instanceDate);
                
                // Add to available days if not already there
                if (!processedDates.availableDays.includes(formattedDate)) {
                    processedDates.availableDays.push(formattedDate);
                }
                
                // Initialize time slots for this date if not already done
                if (!processedDates.availableTimeSlots[formattedDate]) {
                    processedDates.availableTimeSlots[formattedDate] = [];
                }
                
                // Add the time slot
                processedDates.availableTimeSlots[formattedDate].push({
                    start: instance.start_time,
                    end: instance.end_time
                });
            });
        }
        
        return processedDates;
    }

    function handleDateSelection(info, processedDates) {
        const selectedDate = info.date;
        const formattedDate = formatDate(selectedDate);
        
        // Don't allow selection of past dates
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            return;
        }
        
        // Don't allow selection of unavailable dates
        if (!processedDates.availableDays.includes(formattedDate)) {
            return;
        }
        
        // Remove previous selection
        document.querySelectorAll('.fc-day-selected').forEach(el => {
            el.classList.remove('fc-day-selected');
        });
        
        // Add selected class
        info.dayEl.classList.add('fc-day-selected');
        
        // Update form fields based on selection
        document.getElementById('appointment-date').value = formattedDate;
        
        // Show available time slots for the selected date
        showAvailableTimeSlots(formattedDate, processedDates);
        
        // Update the from-time dropdown options - show all available hours
        updateAllTimeOptions(formattedDate, processedDates);
    }

    function showAvailableTimeSlots(selectedDate, processedDates) {
        const timeSlotList = document.getElementById('time-slot-list');
        const selectDateMessage = document.querySelector('.select-date-message');
        
        // Hide the message and show the time slot list
        selectDateMessage.style.display = 'none';
        timeSlotList.style.display = 'block';
        
        // Clear previous time slots
        timeSlotList.innerHTML = '';
        
        // Get available slots for this date
        const availableSlots = processedDates.availableTimeSlots[selectedDate];
        
        if (!availableSlots || availableSlots.length === 0) {
            // No available slots for this date
            timeSlotList.innerHTML = '<div class="no-slots-message">No available time slots for this date.</div>';
            return;
        }
        
        // Create time slot elements for each available slot
        availableSlots.forEach(slot => {
            // Format times for display (e.g., "8:00 AM - 12:00 PM")
            const startTime = formatTimeForDisplay(slot.start);
            const endTime = formatTimeForDisplay(slot.end);
            
            const slotElement = document.createElement('div');
            slotElement.className = 'time-slot-item';
            slotElement.innerHTML = `
                <strong>${startTime} - ${endTime}</strong>
                <div class="slot-status">Available</div>
            `;
            
            // Add click handler
            slotElement.addEventListener('click', function() {
                selectTimeSlot(slot, slotElement);
            });
            
            timeSlotList.appendChild(slotElement);
        });
    }

    // New function to update both from-time and to-time dropdowns with all available hours
    function updateAllTimeOptions(selectedDate, processedDates) {
        const fromTimeSelect = document.getElementById('from-time');
        const toTimeSelect = document.getElementById('to-time');
        const availableSlots = processedDates.availableTimeSlots[selectedDate];
        
        // Clear existing options
        fromTimeSelect.innerHTML = '<option value="" disabled selected>Select start time</option>';
        toTimeSelect.innerHTML = '<option value="" disabled selected>Select end time</option>';
        
        if (!availableSlots || availableSlots.length === 0) {
            return;
        }
        
        // Get all available hours from the available slots
        const availableHours = new Set();
        
        availableSlots.forEach(slot => {
            // Get all hours between start and end time
            const startHour = parseInt(slot.start.split(':')[0]);
            const endHour = parseInt(slot.end.split(':')[0]);
            
            for (let hour = startHour; hour <= endHour; hour++) {
                availableHours.add(hour);
            }
        });
        
        // Sort the available hours
        const sortedHours = Array.from(availableHours).sort((a, b) => a - b);
        
        // Add options for each available hour to both dropdowns
        sortedHours.forEach(hour => {
            const displayHour = hour > 12 ? hour - 12 : hour;
            const ampm = hour >= 12 ? 'PM' : 'AM';
            
            // Add to from-time dropdown (exclude the last hour)
            if (hour < sortedHours[sortedHours.length - 1]) {
                const fromOption = document.createElement('option');
                fromOption.value = hour;
                fromOption.textContent = `${displayHour}:00 ${ampm}`;
                fromTimeSelect.appendChild(fromOption);
            }
            
            // Add to to-time dropdown (exclude the first hour)
            if (hour > sortedHours[0]) {
                const toOption = document.createElement('option');
                toOption.value = hour;
                toOption.textContent = `${displayHour}:00 ${ampm}`;
                toTimeSelect.appendChild(toOption);
            }
        });
        
        // Add event listener to from-time to update to-time options
        fromTimeSelect.addEventListener('change', function() {
            updateToTimeBasedOnFrom(sortedHours);
        });
    }
    
    // Helper function to update to-time options based on selected from-time
    function updateToTimeBasedOnFrom(availableHours) {
        const fromTime = parseInt(document.getElementById('from-time').value);
        const toTimeSelect = document.getElementById('to-time');
        
        // Clear existing options
        toTimeSelect.innerHTML = '<option value="" disabled selected>Select end time</option>';
        
        // Add options for hours after the selected from-time
        availableHours.forEach(hour => {
            if (hour > fromTime) {
                const displayHour = hour > 12 ? hour - 12 : hour;
                const ampm = hour >= 12 ? 'PM' : 'AM';
                
                const option = document.createElement('option');
                option.value = hour;
                option.textContent = `${displayHour}:00 ${ampm}`;
                toTimeSelect.appendChild(option);
            }
        });
        
        // Recalculate payment
        calculatePayment();
    }

    function selectTimeSlot(slot, slotElement) {
        // Remove selection from all slots
        document.querySelectorAll('.time-slot-item').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Add selection to clicked slot
        slotElement.classList.add('selected');
        
        // Update the from-time and to-time dropdowns
        const startHour = parseInt(slot.start.split(':')[0]);
        const endHour = parseInt(slot.end.split(':')[0]);
        
        const fromTimeSelect = document.getElementById('from-time');
        const toTimeSelect = document.getElementById('to-time');
        
        // Set the from-time value
        fromTimeSelect.value = startHour;
        
        // Update to-time options based on from-time
        updateToTimeBasedOnFrom(Array.from({length: 24}, (_, i) => i));
        
        // Set the to-time value if it's within the available options
        if (endHour > startHour) {
            // Find the option with the end hour value
            const endOption = Array.from(toTimeSelect.options).find(option => parseInt(option.value) === endHour);
            if (endOption) {
                endOption.selected = true;
                
                // Trigger change event to update payment calculation
                toTimeSelect.dispatchEvent(new Event('change'));
            }
        }
    }

    // Helper function to format time for display (e.g., "8:00 AM")
    function formatTimeForDisplay(timeStr) {
        const [hours, minutes] = timeStr.split(':').map(Number);
        const hour = hours % 12 || 12; // Convert 0 to 12 for 12 AM
        const ampm = hours >= 12 ? 'PM' : 'AM';
        return `${hour}:${minutes.toString().padStart(2, '0')} ${ampm}`;
    }

    // Helper function to format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Function to update to-time options based on from-time
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
        
        // Recalculate payment
        calculatePayment();
    }
</script>


    


   


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