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
<!-- Add this after the "Elder Details" section -->
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
        console.log('Fetching schedule from:', url);
        
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
                right: '' // Remove 'today' button
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
        console.log('Processing schedule data:', scheduleData);
        
        const unavailableDates = {
            fullDays: [], // Completely unavailable days
            partialDays: {} // Partially unavailable days with specific time slots
        };
        
        // Process short schedules
        if (scheduleData.shortSchedules && scheduleData.shortSchedules.length) {
            scheduleData.shortSchedules.forEach(schedule => {
                // Get the date from the schedule
                const date = schedule.sheduled_date;
                
                // If it's a full day shift, mark the whole day as unavailable
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
                        case 'day': timeSlot = 'morning'; break;
                        case 'night': timeSlot = 'night'; break;
                        default: timeSlot = schedule.shift;
                    }
                    
                    unavailableDates.partialDays[date].push(timeSlot);
                }
            });
        }
        
        // Process long schedules
        if (scheduleData.longSchedules && scheduleData.longSchedules.length) {
            scheduleData.longSchedules.forEach(schedule => {
                const startDate = new Date(schedule.start_date_time);
                const endDate = new Date(schedule.end_date_time);
                
                // Mark all days in the range as unavailable
                const currentDate = new Date(startDate);
                while (currentDate <= endDate) {
                    unavailableDates.fullDays.push(formatDate(currentDate));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            });
        }
        
        console.log('Processed unavailable dates:', unavailableDates);
        return unavailableDates;
    }


    function isDateUnavailable(date, unavailableDates) {
        const formattedDate = formatDate(date);
        
        // Check if the date is fully unavailable
        if (unavailableDates.fullDays.includes(formattedDate)) {
            return true;
        }
        
        // For partial days, we'll handle this in the time slot selection
        return false;
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
            { id: 'afternoon', name: 'Afternoon', time: '12:00 PM - 6:00 PM', value: 'afternoon' },
            { id: 'night', name: 'Night', time: '6:00 PM - 10:00 PM', value: 'night' },
            { id: 'overnight', name: 'Overnight', time: '10:00 PM - 8:00 AM', value: 'overnight' }
        ];
        
        // Get unavailable slots for this date
        const unavailableSlots = unavailableDates.partialDays[selectedDate] || [];
        
        // Create time slot elements
        timeSlots.forEach(slot => {
            const isUnavailable = unavailableSlots.includes(slot.value);
            
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

    // Update the toggleDurationFields function to show/hide the calendar
    // Update the toggleDurationFields function to show/hide the calendar
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


    // Update the handleFullDaySelection function to work with the new UI
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
                                    <label class="other-timeframe"><input type="checkbox" name="timeslot[]" value="afternoon" class="other-slot" onchange="calculatePayment()"><span>Afternoon (12pm-6pm)</span></label>
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

// function toggleDurationFields() {
//     const longTermFields = document.getElementById('long-term-fields');
//     const shortTermFields = document.getElementById('short-term-fields');
//     const timeSlotsSection = document.getElementById('time-slots-section');
//     const selectedType = document.querySelector('input[name="duration-type"]:checked')?.value;

//     // First hide all sections with a smooth transition
//     longTermFields.classList.remove('show');
//     shortTermFields.classList.remove('show');
//     timeSlotsSection.classList.remove('show');

//     // After a short delay, show the appropriate sections
//     setTimeout(() => {
//         if (selectedType === 'long-term') {
//             longTermFields.classList.add('show');
//             // Time slots are only available for short term
//             timeSlotsSection.classList.remove('show');
//         } else if (selectedType === 'short-term') {
//             shortTermFields.classList.add('show');
//             timeSlotsSection.classList.add('show');
//         }
        
//         calculatePayment(); // Recalculate payment when changing duration type
//     }, 300);

//     const calendarSection = document.getElementById('calendar-section');
//     calendarSection.classList.add('show');
    
//     // Reset calendar selection when changing duration type
//     document.querySelectorAll('.fc-day-selected').forEach(el => {
//         el.classList.remove('fc-day-selected');
//     });
// }

function toggleDurationFields() {
    const longTermFields = document.getElementById('long-term-fields');
    const shortTermFields = document.getElementById('short-term-fields');
    const timeSlotsSection = document.getElementById('time-slots-section');
    const calendarSection = document.getElementById('calendar-section');
    const selectedType = document.querySelector('input[name="duration-type"]:checked')?.value;

    console.log('Toggle duration fields called, selected type:', selectedType);

    // First hide all sections with a smooth transition
    longTermFields.classList.remove('show');
    shortTermFields.classList.remove('show');
    timeSlotsSection.classList.remove('show');

    // After a short delay, show the appropriate sections
    setTimeout(() => {
        if (selectedType === 'long-term') {
            console.log('Showing long term fields');
            longTermFields.classList.add('show');
            // Time slots are only available for short term
            timeSlotsSection.classList.remove('show');
            
            // Make sure calendar is visible for both types
            if (calendarSection) {
                calendarSection.classList.add('show');
            }
        } else if (selectedType === 'short-term') {
            console.log('Showing short term fields');
            shortTermFields.classList.add('show');
            timeSlotsSection.classList.add('show');
            
            // Make sure calendar is visible for both types
            if (calendarSection) {
                calendarSection.classList.add('show');
            }
        }
        
        // Reset calendar selection when changing duration type
        document.querySelectorAll('.fc-day-selected').forEach(el => {
            el.classList.remove('fc-day-selected');
        });
        
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

