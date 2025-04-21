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
                    <div class="circle image1"><img src="<?= $CareseekerprofilePic ?>" alt="Profile" /></div>
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
                                <select id="elder-profile" name="elder_profile" class="<?php echo (!empty($data['elder_profile_err'])) ? 'is-invalid' : ''; ?>">
                                    <option value="" disabled <?php echo empty($data['elder_profile']) ? 'selected' : ''; ?>>Select a profile</option>
                                    <?php foreach ($data['elders'] as $elder): ?>
                                        <option value="<?php echo $elder->elder_id; ?>" <?php echo ($data['elder_profile'] == $elder->elder_id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($elder->first_name . ' ' . $elder->last_name); ?>
                                            (<?php echo htmlspecialchars($elder->age); ?> years)
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if (empty($data['elders'])): ?>
                                        <option value="" disabled>No profiles available. Please create an elder profile first.</option>
                                    <?php endif; ?>
                                </select>
                                <?php if (!empty($data['error']) && strpos($data['error'], 'elder profile') !== false): ?>
                                    <div class="field-error">Please select an elder profile</div>
                                <?php endif; ?>
                            </div>

                            <?php if (empty($data['elders'])): ?>
                                <div class="create-profile-prompt">
                                    <p>You don't have any elder profiles yet. <a href="<?php echo URLROOT; ?>/careseeker/createElderProfile">Create a profile</a> to continue.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Add this after the "Elder Details" section -->
<div class="form-section" id="calendar-section">
    <div class="form-section-title">Caregiver Availability</div>
    <div class="calendar-container">
        <div class="mini-calendar">
            <div id="mini-calendar"></div>
        </div>
        <div class="time-slots-display" id="time-slots-display">
            <h3>Available Time Slots</h3>
            <p class="select-date-message">Please select a date to view available time slots</p>
            <div class="time-slot-list" id="time-slot-list" style="display: none;"></div>
        </div>
    </div>
</div>

<!-- FullCalendar 5.x Core and Required Packages -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<style>
    /* Calendar container with side-by-side layout */
    .calendar-container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    /* Mini calendar styles */
    .mini-calendar {
        flex: 1;
        max-width: 60%;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    
    /* Time slots display */
    .time-slots-display {
        flex: 1;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
    }
    
    .time-slots-display h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #333;
    }
    
    .select-date-message {
        color: #666;
        font-style: italic;
    }
    
    /* Time slot list */
    .time-slot-list {
        margin-top: 15px;
    }
    
    .time-slot-item {
        padding: 10px;
        margin-bottom: 8px;
        border-radius: 4px;
        background-color: #fff;
        border-left: 4px solid #4CAF50;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .time-slot-item.unavailable {
        border-left-color: #f44336;
        background-color:rgb(255, 3, 3);
        opacity: 0.7;
    }
    
    .time-slot-item.selected {
        border-left-color: #2196F3;
        background-color: #e3f2fd;
    }
    
    /* Style for unavailable dates */
    .fc-day-unavailable {
        background-color:rgb(0, 0, 0) !important;
        cursor: not-allowed !important;
    }
    
    /* Style for selected date */
    .fc-day-selected {
        background-color: #e3f2fd !important;
        border: 2px solid #2196F3 !important;
    }
    
    #calendar-section.show {
        opacity: 1;
        transform: translateY(0);
        height: auto;
        overflow: visible;
        margin-bottom: 20px;
        padding: 15px;
    }

    /* Style for partially unavailable dates */
    .fc-day-partially-unavailable {
    background-color:rgb(161, 191, 170) !important; /* Light orange color */
    cursor: pointer !important;
    }

    .fc-day-unavailable {
    background-color:rgb(255, 95, 95) !important; /* Light red color */
    cursor: not-allowed !important;
    }

    .fc-day-past, .fc-day-today {
    background-color: #f5f5f5 !important; /* Light gray color */
    cursor: not-allowed !important;
    opacity: 0.7;
    }

</style>

<script>
    // Initialize mini calendar for caregiver availability
    document.addEventListener('DOMContentLoaded', function() {
        // Get caregiver ID from the URL
        const caregiverId = <?php echo $data['caregiver_id']; ?>;
        
        // Fetch caregiver's schedule data via AJAX
        fetchCaregiverSchedule(caregiverId);
        
        // Make sure calendar section is visible
        document.getElementById('calendar-section').classList.add('show');
    });

    function fetchCaregiverSchedule(caregiverId) {
        // Define the URL with URLROOT
        const url = `<?php echo URLROOT; ?>/careseeker/getCaregiverSchedule/${caregiverId}`;
        
        // AJAX call to get caregiver's schedule
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Schedule data received:', data);
                initializeCalendar(data);
            })
            .catch(error => {
                console.error('Error fetching caregiver schedule:', error);
                // Initialize with empty data as fallback
                initializeCalendar({shortSchedules: [], longSchedules: []});
            });
    }
    function initializeCalendar(scheduleData) {
        const calendarEl = document.getElementById('mini-calendar');
        
        // Process the schedule data to mark unavailable dates
        const unavailableDates = processUnavailableDates(scheduleData);
        
        // Store the unavailable dates data for later use
        window.unavailableDatesData = unavailableDates;
        
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
                handleDateSelection(info, unavailableDates);
            },
            dayCellDidMount: function(info) {
                const date = info.date;
                const formattedDate = formatDate(date);
                
                // Check if date is in the past or today
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (date <= today) {
                    info.el.classList.add('fc-day-past');
                    return;
                }
                
                // Check if date is fully unavailable
                if (unavailableDates.fullDays.includes(formattedDate)) {
                    info.el.classList.add('fc-day-unavailable');
                    return;
                }
                
                // Check if date is partially unavailable
                if (unavailableDates.partialDays[formattedDate]) {
                    info.el.classList.add('fc-day-partially-unavailable');
                }
            }
        });
        
        calendar.render();
    }
    function processUnavailableDates(scheduleData) {
        const unavailableDates = {
            fullDays: [], // Completely unavailable days
            partialDays: {} // Partially unavailable days with specific time slots
        };
        
        // Process short schedules
        if (scheduleData.shortSchedules && scheduleData.shortSchedules.length) {
            scheduleData.shortSchedules.forEach(schedule => {
                // Get the date from the schedule
                const date = schedule.sheduled_date;
                
                // If it's a full day shift (oneday), mark the whole day as unavailable
                if (schedule.shift === 'fullday') {
                    unavailableDates.fullDays.push(date);
                } else {
                    // Otherwise, mark specific time slots as unavailable
                    if (!unavailableDates.partialDays[date]) {
                        unavailableDates.partialDays[date] = [];
                    }
                    
                    // Map database shift values to form time slot values
                    let timeSlot;
                    switch(schedule.shift) {
                        case 'morning': timeSlot = 'morning'; break;
                        case 'afternoon': timeSlot = 'evening'; break;
                        case 'overnight': timeSlot = 'overnight'; break;
                        default: timeSlot = schedule.shift;
                    }
                    
                    unavailableDates.partialDays[date].push(timeSlot);
                    
                    // Check if all time slots are taken for this date
                    const slots = unavailableDates.partialDays[date];
                    if (slots.includes('morning') && slots.includes('evening') && slots.includes('overnight')) {
                        // If all individual slots are taken, move this date to fullDays
                        unavailableDates.fullDays.push(date);
                        // Remove from partialDays to avoid duplicate processing
                        delete unavailableDates.partialDays[date];
                    }
                }
            });
        }
        
        // Process long schedules
        if (scheduleData.longSchedules && scheduleData.longSchedules.length) {
            scheduleData.longSchedules.forEach(schedule => {
                const startDate = new Date(schedule.start_date);
                const endDate = new Date(schedule.end_date);
                
                // Mark all days in the range as unavailable
                const currentDate = new Date(startDate);
                while (currentDate <= endDate) {
                    unavailableDates.fullDays.push(formatDate(currentDate));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            });
        }
        
        return unavailableDates;
}


    function handleDateSelection(info, unavailableDates) {
        const selectedDate = info.date;
        const formattedDate = formatDate(selectedDate);
        
        // Don't allow selection of past dates or today
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate <= today) {
            return;
        }
        
        // Don't allow selection of fully unavailable dates
        if (unavailableDates.fullDays.includes(formattedDate)) {
            return;
        }
        
        // Remove previous selection
        document.querySelectorAll('.fc-day-selected').forEach(el => {
            el.classList.remove('fc-day-selected');
        });
        
        // Add selected class
        info.dayEl.classList.add('fc-day-selected');
        
        // Update form fields based on selection
        updateFormFields(formattedDate, unavailableDates);
        
        // Show available time slots for the selected date
        showTimeSlots(formattedDate, unavailableDates);
    }

    function updateFormFields(selectedDate, unavailableDates) {
        // Get the duration type
        const durationType = document.querySelector('input[name="duration-type"]:checked')?.value;
        
        if (durationType === 'short-term') {
            // Update the short term date field
            document.getElementById('from_date_short').value = selectedDate;
            
            // Update available time slots based on the selected date
            updateAvailableTimeSlots(selectedDate, unavailableDates);
        } else if (durationType === 'long-term') {
            // If start date is not set, set it
            if (!document.getElementById('from-date').value) {
                document.getElementById('from-date').value = selectedDate;
            } else {
                // If start date is already set, set end date
                document.getElementById('to-date').value = selectedDate;
            }
        }
        
        // Recalculate payment
        calculatePayment();
    }
    function updateAvailableTimeSlots(selectedDate, unavailableDates) {
        // Get all time slot checkboxes
        const timeSlotCheckboxes = document.querySelectorAll('.time-slot-checkboxes input[type="checkbox"]');
        const longTermRadio = document.getElementById('long-term-radio');
        const fullDayCheckbox = document.getElementById('full-day-checkbox');
        
        // Reset all time slots to enabled
        timeSlotCheckboxes.forEach(checkbox => {
            checkbox.disabled = false;
            const label = checkbox.closest('label');
            if (label) {
                label.classList.remove('disabled-option');
            }
        });
        
        // If the date is partially unavailable, disable the unavailable time slots
        if (unavailableDates.partialDays[selectedDate]) {
            const unavailableSlots = unavailableDates.partialDays[selectedDate];
            
            timeSlotCheckboxes.forEach(checkbox => {
                // Check if this slot is in the unavailable list
                const slotValue = checkbox.value;
                if (unavailableSlots.includes(slotValue)) {
                    // Disable and uncheck the checkbox
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    
                    // Add disabled class to the label
                    const label = checkbox.closest('label');
                    if (label) {
                        label.classList.add('disabled-option');
                    }
                }
            });
            
            // Special handling for full-day checkbox
            // If any individual slot is unavailable, full-day should also be unavailable
            if (unavailableSlots.includes('morning') || unavailableSlots.includes('evening') || unavailableSlots.includes('overnight')) {
                fullDayCheckbox.disabled = true;
                fullDayCheckbox.checked = false;
                const label = fullDayCheckbox.closest('label');
                if (label) {
                    label.classList.add('disabled-option');
                }
            }
        }
        
        // Check if any time slots are selected
        const anyTimeSlotSelected = Array.from(timeSlotCheckboxes).some(checkbox => checkbox.checked);
        
        // If any time slot is selected, disable long-term radio
        if (longTermRadio) {
            longTermRadio.disabled = anyTimeSlotSelected;
            const label = longTermRadio.closest('label');
            if (label) {
                if (anyTimeSlotSelected) {
                    label.classList.add('disabled-option');
                } else {
                    label.classList.remove('disabled-option');
                }
            }
        }
    }
    // Add event listeners to time slot checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.time-slot-checkboxes input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Get the long-term radio button
                const longTermRadio = document.getElementById('long-term-radio');
                
                // Check if any time slot is selected
                const anyTimeSlotSelected = Array.from(
                    document.querySelectorAll('.time-slot-checkboxes input[type="checkbox"]')
                ).some(cb => cb.checked);
                
                // If any time slot is selected, disable long-term radio
                if (longTermRadio) {
                    longTermRadio.disabled = anyTimeSlotSelected;
                    const label = longTermRadio.closest('label');
                    if (label) {
                        if (anyTimeSlotSelected) {
                            label.classList.add('disabled-option');
                        } else {
                            label.classList.remove('disabled-option');
                        }
                    }
                }
                
                // If this is an individual time slot (not full-day)
                if (this.value !== 'full-day' && this.checked) {
                    // Disable full-day option when individual slots are selected
                    const fullDayCheckbox = document.getElementById('full-day-checkbox');
                    if (fullDayCheckbox) {
                        fullDayCheckbox.disabled = true;
                        const fullDayLabel = fullDayCheckbox.closest('label');
                        if (fullDayLabel) {
                            fullDayLabel.classList.add('disabled-option');
                        }
                    }
                } else if (!anyTimeSlotSelected) {
                    // If no time slots are selected, enable full-day option
                    const fullDayCheckbox = document.getElementById('full-day-checkbox');
                    if (fullDayCheckbox) {
                        fullDayCheckbox.disabled = false;
                        const fullDayLabel = fullDayCheckbox.closest('label');
                        if (fullDayLabel) {
                            fullDayLabel.classList.remove('disabled-option');
                        }
                    }
                }
            });
        });
    });
    function showTimeSlots(selectedDate, unavailableDates) {
        const timeSlotList = document.getElementById('time-slot-list');
        const selectDateMessage = document.querySelector('.select-date-message');
        
        // Hide the message and show the time slot list
        selectDateMessage.style.display = 'none';
        timeSlotList.style.display = 'block';
        
        // Clear previous time slots
        timeSlotList.innerHTML = '';
        
        // Define all possible time slots
        const timeSlots = [
            { id: 'full-day', name: 'Full Day', time: '8:00 AM - 8:00 PM', value: 'full-day' },
            { id: 'morning', name: 'Morning', time: '8:00 AM - 12:00 PM', value: 'morning' },
            { id: 'evening', name: 'Evening', time: '12:00 PM - 6:00 PM', value: 'evening' },
            { id: 'overnight', name: 'Overnight', time: '10:00 PM - 8:00 AM', value: 'overnight' }
        ];
        
        // Get unavailable slots for this date
        const unavailableSlots = unavailableDates.partialDays[selectedDate] || [];
        
        // Create time slot elements
        timeSlots.forEach(slot => {
            // Check if slot is unavailable
            let isUnavailable = unavailableSlots.includes(slot.value);
            
            // Special handling for full-day: if any individual slot is unavailable, full-day is also unavailable
            if (slot.id === 'full-day' && !isUnavailable) {
                if (unavailableSlots.includes('morning') || unavailableSlots.includes('evening') || unavailableSlots.includes('overnight')) {
                    isUnavailable = true;
                }
            }
            
            const slotElement = document.createElement('div');
            slotElement.className = `time-slot-item ${isUnavailable ? 'unavailable' : ''}`;
            slotElement.innerHTML = `
                <strong>${slot.name}</strong>
                <div>${slot.time}</div>
                <div class="slot-status">${isUnavailable ? 'Unavailable' : 'Available'}</div>
            `;
            
            // Add click handler for available slots
            if (!isUnavailable) {
                slotElement.addEventListener('click', function() {
                    selectTimeSlot(slot.id, slotElement);
                });
            }
            
            timeSlotList.appendChild(slotElement);
        });
    }

    function selectTimeSlot(slotId, slotElement) {
        // Remove selection from all slots
        document.querySelectorAll('.time-slot-item').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Add selection to clicked slot
        slotElement.classList.add('selected');
        
        // Check the corresponding checkbox in the form
        const checkbox = document.querySelector(`input[name="timeslot[]"][value="${slotId}"]`);
        if (checkbox) {
            // Uncheck all checkboxes first
            document.querySelectorAll('input[name="timeslot[]"]').forEach(cb => {
                cb.checked = false;
            });
            
            // Check the selected one
            checkbox.checked = true;
            
            // If it's the full day checkbox, handle special case
            if (slotId === 'full-day') {
                handleFullDaySelection();
            }
            
            // Recalculate payment
            calculatePayment();
        }
    }

    // Helper function to format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

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
                
                // Uncheck all time slot checkboxes
                document.querySelectorAll('.time-slot-checkboxes input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            } else if (selectedType === 'short-term') {
                shortTermFields.classList.add('show');
                timeSlotsSection.classList.add('show');
                
                // Clear long-term date fields
                document.getElementById('from-date').value = '';
                document.getElementById('to-date').value = '';
            }
            
            calculatePayment(); // Recalculate payment when changing duration type
        }, 300);

        const calendarSection = document.getElementById('calendar-section');
        if (calendarSection) {
            calendarSection.classList.add('show');
        }
        
        // Reset calendar selection when changing duration type
        document.querySelectorAll('.fc-day-selected').forEach(el => {
            el.classList.remove('fc-day-selected');
        });
    }

    function handleFullDaySelection() {
        const fullDayCheckbox = document.getElementById('full-day-checkbox');
        const otherTimeSlots = document.querySelectorAll('.other-slot');
        const otherTimeFrames = document.querySelectorAll('.other-timeframe');
        const longTermRadio = document.getElementById('long-term-radio');
        
        if (fullDayCheckbox.checked) {
            // If Full Day is selected, disable other options
            otherTimeSlots.forEach(slot => {
                slot.checked = false;
                slot.disabled = true;
            });
            
            otherTimeFrames.forEach(frame => {
                frame.classList.add('disabled-option');
            });
            
            // Also disable long-term radio
            if (longTermRadio) {
                longTermRadio.disabled = true;
                const label = longTermRadio.closest('label');
                if (label) {
                    label.classList.add('disabled-option');
                }
            }
        } else {
            // If Full Day is not selected, enable other options
            otherTimeSlots.forEach(slot => {
                slot.disabled = false;
            });
            
            otherTimeFrames.forEach(frame => {
                frame.classList.remove('disabled-option');
            });
            
            // Check if any other time slot is selected
            const anyOtherSelected = Array.from(otherTimeSlots).some(slot => slot.checked);
            
            // Only enable long-term radio if no other time slots are selected
            if (longTermRadio && !anyOtherSelected) {
                longTermRadio.disabled = false;
                const label = longTermRadio.closest('label');
                if (label) {
                    label.classList.remove('disabled-option');
                }
            }
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
</script>


                        <!-- Duration Type Selection -->
                        <div class="form-section">
                            <div class="form-section-title">Care Duration</div>
                            <div class="duration-type-options">
                                <div class="duration-type-option">
                                    <input type="radio" id="long-term-radio" name="duration-type" value="long-term"
                                        <?php echo ($data['caregiver']->caregiver_type == 'short') ? 'disabled' : ''; ?>
                                        <?php echo (isset($data['duration_type']) && $data['duration_type'] === 'long-term') ? 'checked' : ''; ?>
                                        onchange="toggleDurationFields()">
                                    <label for="long-term-radio" <?php echo ($data['caregiver']->caregiver_type == 'short') ? 'class="disabled-option"' : ''; ?>>
                                        Long Term Care
                                    </label>
                                </div>
                                <div class="duration-type-option">
                                    <input type="radio" id="short-term-radio" name="duration-type" value="short-term"
                                        <?php echo ($data['caregiver']->caregiver_type == 'long') ? 'disabled' : ''; ?>
                                        <?php echo (isset($data['duration_type']) && $data['duration_type'] === 'short-term') ? 'checked' : ''; ?>
                                        onchange="toggleDurationFields()">
                                    <label for="short-term-radio" <?php echo ($data['caregiver']->caregiver_type == 'long') ? 'class="disabled-option"' : ''; ?>>
                                        Short Term (One Day)
                                    </label>
                                </div>

                            </div>
                            <?php if (!empty($data['error']) && strpos($data['error'], 'duration type') !== false): ?>
                                <div class="field-error">Please select a duration type</div>
                            <?php endif; ?>

                        </div>

                        <!-- Long Term Fields -->
                        <div id="long-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Long Term Care Schedule</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="from-date">Start Date</label>
                                    <input type="date" id="from-date" name="from_date" value="<?php echo $data['from_date'] ?? ''; ?>" onchange="calculatePayment()" />
                                    <?php if (!empty($data['error']) && strpos($data['error'], 'start date') !== false): ?>
                                        <div class="field-error">Please select a start date</div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="to-date">End Date</label>
                                    <input type="date" id="to-date" name="to_date" value="<?php echo $data['to_date'] ?? ''; ?>" onchange="calculatePayment()" />
                                    <?php if (!empty($data['error']) && strpos($data['error'], 'end date') !== false): ?>
                                        <div class="field-error">Please select an end date</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Short Term Fields -->
                        <div id="short-term-fields" class="animated-section form-section">
                            <div class="form-section-title">Short Term Care Date</div>
                            <div class="form-group">
                                <label for="from_date_short">Select Date</label>
                                <input type="date" id="from_date_short" name="from_date_short" value="<?php echo $data['from_date_short'] ?? ''; ?>" />
                                <?php if (!empty($data['error']) && strpos($data['error'], 'date for short-term') !== false): ?>
                                    <div class="field-error">Please select a date for short-term care</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div id="time-slots-section" class="animated-section form-section">
                            <div class="form-section-title">Care Time Slots</div>
                            <div class="form-group">
                                <label>Select Time Slots (Choose one or more)</label>
                                <div class="time-slot-checkboxes">
                                    <?php
                                    $timeSlots = $data['time_slots'] ?? [];
                                    $fullDayChecked = in_array('full-day', $timeSlots) ? 'checked' : '';
                                    $morningChecked = in_array('morning', $timeSlots) ? 'checked' : '';
                                    $eveningChecked = in_array('evening', $timeSlots) ? 'checked' : '';
                                    $overnightChecked = in_array('overnight', $timeSlots) ? 'checked' : '';
                                    ?>
                                    <label><input type="checkbox" name="timeslot[]" value="full-day" id="full-day-checkbox" <?php echo $fullDayChecked; ?> onchange="handleFullDaySelection(); calculatePayment();"><span>Full Day (8am-8am)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="morning" class="other-slot" <?php echo $morningChecked; ?> onchange="calculatePayment()"><span>Morning (8am-12pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="evening" class="other-slot" <?php echo $eveningChecked; ?> onchange="calculatePayment()"><span>Evening (1pm-5pm)</span></label>
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="overnight" class="other-slot" <?php echo $overnightChecked; ?> onchange="calculatePayment()"><span>Overnight (6pm-8am)</span></label>
                                </div>
                                <?php if (!empty($data['error']) && strpos($data['error'], 'time slot') !== false): ?>
                                    <div class="field-error">Please select at least one time slot</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Service Details -->
                        <div class="form-section">
                            <div class="form-section-title">Service Information</div>
                            <div class="form-row two-columns">
                                <div class="form-group">
                                    <label for="service">Service Type</label>
                                    <input type="text" id="service" placeholder="Caregiving" readonly />
                                </div>
                                <div class="form-group">
                                    <label for="payment-details">Payment Details</label>
                                    <input type="text" id="payment-details" placeholder="Calculating..." readonly />
                                    <input type="hidden" id="total-payment" name="total_payment" value="<?php echo $data['total_payment'] ?? 0; ?>" />
                                    <?php if (!empty($data['error']) && strpos($data['error'], 'payment') !== false): ?>
                                        <div class="field-error">Invalid payment amount</div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="service">Service Address</label>
                                    <input type="text" id="service-address" name="service_address" value="<?php echo $data['service_address'] ?? ''; ?>" />
                                    <?php if (!empty($data['error']) && strpos($data['error'], 'service address') !== false): ?>
                                        <div class="field-error">Please enter service address</div>
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
                            <button class="request-cancel-button" type="button" onclick="location.href='<?php echo URLROOT; ?>/careseeker/viewCaregiverProfile/<?php echo $data['caregiver_id']; ?>'">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<style>
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

    /* Rest of the styles remain the same */
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

    .time-slot-checkboxes input[type="checkbox"]:disabled+span {
        color: #999;
    }

    #long-term-fields,
    #short-term-fields,
    #time-slots-section {
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

<!-- The rest of your JavaScript remains the same -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set default selection based on caregiver type
        const caregiverType = '<?php echo $data['caregiver']->caregiver_type; ?>';

        if (caregiverType === 'long') {
            document.getElementById('long-term-radio').checked = true;
        } else if (caregiverType === 'short') {
            document.getElementById('short-term-radio').checked = true;
        }

        // Set min date for all date inputs to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];

        document.getElementById('from-date').min = tomorrowFormatted;
        document.getElementById('to-date').min = tomorrowFormatted;
        document.getElementById('from_date_short').min = tomorrowFormatted;

        // Add event listeners for the duration type radio buttons
        document.getElementById('long-term-radio').addEventListener('change', function() {
            if (this.checked) {
                // Make sure short term date is cleared
                document.getElementById('from_date_short').value = '';
                // Clear any time slot selections
                document.querySelectorAll('input[name="timeslot[]"]:checked').forEach(checkbox => {
                    checkbox.checked = false;
                });
                // Clear error messages
                clearErrorMessages();
            }
        });

        document.getElementById('short-term-radio').addEventListener('change', function() {
            if (this.checked) {
                // Make sure both long term dates are cleared
                document.getElementById('from-date').value = '';
                document.getElementById('to-date').value = '';
                // Clear error messages
                clearErrorMessages();
            }
        });

        toggleDurationFields(); // Handle pre-selected option
        handleFullDaySelection(); // Check for any pre-selected checkboxes
        calculatePayment(); // Calculate initial payment

        // Add event listeners for date inputs
        document.getElementById('from-date').addEventListener('change', function() {
            // When start date changes, set the min date of the end date
            const startDate = new Date(this.value);

            if (!isNaN(startDate.getTime())) {
                // Set minimum end date to the same as start date
                const minEndDate = startDate.toISOString().split('T')[0];

                // Calculate maximum end date (start date + 4 days = 5 days total)
                const maxEndDate = new Date(startDate);
                maxEndDate.setDate(startDate.getDate() + 4);
                const maxEndDateFormatted = maxEndDate.toISOString().split('T')[0];

                const toDateInput = document.getElementById('to-date');
                toDateInput.min = minEndDate;
                toDateInput.max = maxEndDateFormatted;

                // If current end date is beyond the new max, adjust it
                if (toDateInput.value) {
                    const currentEndDate = new Date(toDateInput.value);
                    if (currentEndDate > maxEndDate) {
                        toDateInput.value = maxEndDateFormatted;
                    }
                }
            }

            // Clear any error messages related to date fields
            clearErrorMessageFor('start date');
            calculatePayment();
        });

        document.getElementById('to-date').addEventListener('change', function() {
            enforceDateLimits();
            // Clear any error messages related to date fields
            clearErrorMessageFor('end date');
            calculatePayment();
        });

        document.getElementById('from_date_short').addEventListener('change', function() {
            // Clear any error messages related to short-term date field
            clearErrorMessageFor('date for short-term');
            calculatePayment();
        });

        // Add event listener for elder profile selection
        document.getElementById('elder-profile').addEventListener('change', function() {
            clearErrorMessageFor('elder profile');
        });

        // Add event listeners for time slot checkboxes
        document.querySelectorAll('input[name="timeslot[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                clearErrorMessageFor('time slot');
                calculatePayment();
            });
        });

        // Create a MutationObserver to watch for attribute changes on the to-date input
        const toDateInput = document.getElementById('to-date');
        const observer = new MutationObserver(function() {
            setupDateInputConstraints();
        });

        observer.observe(toDateInput, {
            attributes: true
        });

        // Setup initial date constraints if needed
        setupDateInputConstraints();
    });

    // Function to setup date input constraints
    function setupDateInputConstraints() {
        const fromDateInput = document.getElementById('from-date');
        const toDateInput = document.getElementById('to-date');

        if (fromDateInput.value) {
            const startDate = new Date(fromDateInput.value);
            if (!isNaN(startDate.getTime())) {
                const maxEndDate = new Date(startDate);
                maxEndDate.setDate(startDate.getDate() + 4);

                // Create a date range of allowed dates (start date to start date + 4)
                const allowedDates = [];
                for (let i = 0; i <= 4; i++) {
                    const date = new Date(startDate);
                    date.setDate(startDate.getDate() + i);
                    allowedDates.push(date.toISOString().split('T')[0]);
                }

                // Override the date input's onchange handler temporarily
                const originalOnChange = toDateInput.onchange;

                // Apply a custom validation function to the end date input
                toDateInput.addEventListener('input', function(e) {
                    const selectedDate = e.target.value;
                    if (selectedDate && !allowedDates.includes(selectedDate)) {
                        // If selected date is not in allowed range, reset to empty
                        e.target.value = '';
                        alert('Please select a date within the 5-day range from the start date.');
                    }
                });
            }
        }
    }

    function toggleDurationFields() {
        const longTermFields = document.getElementById('long-term-fields');
        const shortTermFields = document.getElementById('short-term-fields');
        const timeSlotsSection = document.getElementById('time-slots-section');
        const selectedType = document.querySelector('input[name="duration-type"]:checked')?.value;

        // First hide all sections with a smooth transition
        longTermFields.classList.remove('show');
        shortTermFields.classList.remove('show');
        timeSlotsSection.classList.remove('show');

        // Clear date values when switching between duration types
        if (selectedType === 'long-term') {
            // Clear short term date
            document.getElementById('from_date_short').value = '';
            // Clear any time slot selections
            document.querySelectorAll('input[name="timeslot[]"]:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            handleFullDaySelection(); // Update UI for time slots

            // Only clear error if a selection is made
            clearErrorMessageFor('duration type');
        } else if (selectedType === 'short-term') {
            // Clear long term dates - ensure both are cleared
            document.getElementById('from-date').value = '';
            document.getElementById('to-date').value = '';

            // Only clear error if a selection is made
            clearErrorMessageFor('duration type');
        }

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

    function enforceDateLimits() {
        const fromDate = document.getElementById('from-date').value;
        const toDate = document.getElementById('to-date').value;

        if (fromDate && toDate) {
            const startDate = new Date(fromDate);
            const endDate = new Date(toDate);

            // Calculate the difference in days
            const diffTime = endDate - startDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Include both start and end day

            if (diffDays > 5) {
                // Reset the end date to be 5 days from start date
                const maxEndDate = new Date(startDate);
                maxEndDate.setDate(startDate.getDate() + 4); // 4 more days after start date = 5 days total

                // Format maxEndDate to YYYY-MM-DD for input value
                const maxEndDateFormatted = maxEndDate.toISOString().split('T')[0];
                document.getElementById('to-date').value = maxEndDateFormatted;

                // Optionally, show a notification to the user
                alert('Maximum care duration is 5 days. End date has been adjusted accordingly.');
            }
        }
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