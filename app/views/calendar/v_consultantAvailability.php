<?php
$required_styles = [
    'calendar/calendar',
    'calendar/consultant_calander',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<!-- FullCalendar 5.x Core and Required Packages -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>

    <div class="container">
        <div class="header">
            <h2>My Availability</h2>
            <button id="add-availability-btn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Availability
            </button>
        </div>
        <div class="calendar-wrapper">
            <!-- Calendar on the left -->
            <div class="calendar-container">
                <div id="calendar"></div>
            </div>
            
            <!-- Availability management panel -->
            <div class="event-details-container">
                <div class="event-details-header">
                    <h3>Availability Details</h3>

                    <div class="status-legend">
                        <div class="status-item">
                            <span class="status-label">Recurring</span>
                            <span class="status-dot confirmed-dot"></span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Date</span>
                            <span class="status-dot pending-dot"></span>
                        </div>
                    </div>
                </div>

                <div class="empty-state">
                <p>
                    Make Sure that you are selecting the correct availability because it canot changed once set. And you can only set availability for future dates.
                </p>
                </div>
                

                <!-- Error container for displaying validation errors -->
                <div class="event-details-content" id="error-container" style="display:<?php echo isset($data['error']) ? 'block' : 'none'; ?>;">
                    <?php if(isset($data['error'])): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $data['error']; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Default message when no date is selected -->
                <div class="event-details-content" id="no-date-selected">
                    <div class="empty-state">
                        <i class="fas fa-calendar-day"></i>
                        <p>Select an event to view details or click "Add Availability" to set your available times</p>
                    </div>
                </div>
                
                <!-- Form for setting availability - initially hidden -->
                <div class="event-details-content" id="set-availability-form" style="display:none;">
                    <div class="form-group">
                        <label>Availability Type:</label>
                        <div class="toggle-buttons">
                            <button type="button" id="pattern-btn" class="toggle-btn active" onclick="toggleAvailabilityType('pattern')">Recurring</button>
                            <button type="button" id="instance-btn" class="toggle-btn" onclick="toggleAvailabilityType('instance')">Specific Date</button>
                        </div>
                    </div>
                    
                    <!-- Recurring availability pattern form -->
                    <form id="pattern-form" action="<?php echo URLROOT; ?>/consultant/editAvailability" method="POST">
                        <input type="hidden" name="availability_type" value="pattern">
                        
                        <div class="form-group">
                            <label for="day_of_week">Day of Week:</label>
                            <select id="day_of_week" name="day_of_week" class="form-control">
                                <option value="">Select a day</option>
                                <option value="0">Sunday</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                            </select>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="start_time">Start Hour:</label>
                                <select id="start_time" name="start_time" class="form-control">
                                    <?php for($i = 8; $i <= 21; $i++): ?>
                                        <option value="<?php echo sprintf('%02d:00:00', $i); ?>">
                                            <?php echo date('h:00 A', strtotime(sprintf('%02d:00:00', $i))); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group half">
                                <label for="end_time">End Hour:</label>
                                <select id="end_time" name="end_time" class="form-control">
                                    <?php for($i = 9; $i <= 22; $i++): ?>
                                        <option value="<?php echo sprintf('%02d:00:00', $i); ?>">
                                            <?php echo date('h:00 A', strtotime(sprintf('%02d:00:00', $i))); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="pattern_start_date">Start Date:</label>
                            <input type="date" id="pattern_start_date" name="pattern_start_date" class="form-control" onchange="updateEndDate()">
                            <!-- <small class="form-text text-muted">When this pattern begins</small> -->
                        </div>
                        
                        <input type="hidden" id="pattern_end_date" name="pattern_end_date">
                        <div class="form-group">
                            <label>End Date:</label>
                            <div id="end_date_display" class="form-control-static">
                                (Will be set to one month after start date)
                            </div>
                            <!-- <small class="form-text text-muted">Automatically set to one month after start date</small> -->
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Add Recurring Availability
                            </button>
                        </div>
                    </form>
                    
                    <!-- Specific date availability form -->
                    <form id="instance-form" action="<?php echo URLROOT; ?>/consultant/editAvailability" method="POST" style="display:none;">
                        <input type="hidden" name="availability_type" value="instance">
                        
                        <div class="form-group">
                            <label for="instance_date">Select Date:</label>
                            <input type="date" id="instance_date" name="instance_date" class="form-control">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="instance_start_time">Start Hour:</label>
                                <select id="instance_start_time" name="instance_start_time" class="form-control">
                                    <?php for($i = 8; $i <= 21; $i++): ?>
                                        <option value="<?php echo sprintf('%02d:00:00', $i); ?>">
                                            <?php echo date('h:00 A', strtotime(sprintf('%02d:00:00', $i))); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group half">
                                <label for="instance_end_time">End Hour:</label>
                                <select id="instance_end_time" name="instance_end_time" class="form-control">
                                    <?php for($i = 9; $i <= 22; $i++): ?>
                                        <option value="<?php echo sprintf('%02d:00:00', $i); ?>">
                                            <?php echo date('h:00 A', strtotime(sprintf('%02d:00:00', $i))); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Add Specific Availability
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Event details when clicking on an existing event -->
                <div class="event-details-content" id="event-details" style="display:none;">
                    <div class="detail-card">
                        <div class="detail-row">
                            <span class="detail-label">Availability Type:</span>
                            <span id="event-type" class="detail-value"></span>
                        </div>
                        <div class="detail-row" id="day-of-week-row" style="display:none;">
                            <span class="detail-label">Day of Week:</span>
                            <span id="event-day" class="detail-value"></span>
                        </div>
                        <div class="detail-row" id="date-row" style="display:none;">
                            <span class="detail-label">Date:</span>
                            <span id="event-date" class="detail-value"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Time:</span>
                            <span id="event-time" class="detail-value"></span>
                        </div>
                        <div class="detail-row" id="date-range-row" style="display:none;">
                            <span class="detail-label">Valid Period:</span>
                            <span id="event-date-range" class="detail-value"></span>
                        </div>
                    </div>
                    
                    <!-- Note about availability -->
                    <div class="info-message">
                        <i class="fas fa-info-circle"></i>
                        <span>Availabilities cannot be removed once set.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<?php require APPROOT . '/views/includes/footer.php'; ?>

<script>
    // Helper function to format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Format date as "Month Day, Year"
    function formatDateLong(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }

    // Format time as "h:mm AM/PM"
    function formatTime12Hour(timeString) {
        // Parse the time string (HH:MM:SS)
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours, 10);
        const minute = parseInt(minutes, 10);
        
        // Convert to 12-hour format
        const period = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12; // Convert 0 to 12 for 12 AM
        
        return `${hour12}:${String(minute).padStart(2, '0')} ${period}`;
    }

    // Function to toggle between pattern and instance forms
    window.toggleAvailabilityType = function(type) {
        const patternBtn = document.getElementById('pattern-btn');
        const instanceBtn = document.getElementById('instance-btn');
        const patternForm = document.getElementById('pattern-form');
        const instanceForm = document.getElementById('instance-form');
        
        if (type === 'pattern') {
            patternBtn.classList.add('active');
            instanceBtn.classList.remove('active');
            patternForm.style.display = 'block';
            instanceForm.style.display = 'none';
            
            // Set minimum dates for pattern form
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const formattedToday = formatDate(tomorrow);
            document.getElementById('pattern_start_date').min = formattedToday;
            
            // Update end date
            updateEndDate();
        } else {
            patternBtn.classList.remove('active');
            instanceBtn.classList.add('active');
            patternForm.style.display = 'none';
            instanceForm.style.display = 'block';
            
            // Set minimum date for instance form
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const formattedToday = formatDate(tomorrow);
            document.getElementById('instance_date').min = formattedToday;
        }
    };

    // Function to update end date based on start date
    window.updateEndDate = function() {
        const startDateInput = document.getElementById('pattern_start_date');
        const endDateInput = document.getElementById('pattern_end_date');
        const endDateDisplay = document.getElementById('end_date_display');
        
        if (startDateInput.value) {
            // Create a date object from the start date
            const startDate = new Date(startDateInput.value);
            
            // Add one month to the start date
            const endDate = new Date(startDate);
            endDate.setMonth(endDate.getMonth() + 1);
            
            // Set the end date value
            const formattedEndDate = formatDate(endDate);
            endDateInput.value = formattedEndDate;
            
            // Update the display
            endDateDisplay.textContent = formatDateLong(endDate);
        } else {
            endDateDisplay.textContent = "(Will be set to one month after start date)";
            endDateInput.value = '';
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Convert PHP data to JavaScript
        const availabilityPatterns = <?php echo json_encode($data['availabilityPatterns'] ?? []); ?>;
        const availabilityInstances = <?php echo json_encode($data['availabilityInstances'] ?? []); ?>;
        
        // Process events for the calendar
        const events = [];
        
        // Day of week names for display
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        // Process recurring patterns
        if (availabilityPatterns && availabilityPatterns.length) {
            availabilityPatterns.forEach(pattern => {
                // For each pattern, create events for all occurrences within the valid date range
                const startDate = new Date(pattern.start_date);
                const endDate = new Date(pattern.end_date);
                
                // Find the first occurrence of this day of week on or after the start date
                let currentDate = new Date(startDate);
                while (currentDate.getDay() != pattern.day_of_week) {
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                
                // Create events for each occurrence
                while (currentDate <= endDate) {
                    const formattedDate = formatDate(currentDate);
                    
                    events.push({
                        id: 'pattern_' + pattern.id + '_' + formattedDate,
                        title: 'Available: ' + formatTime12Hour(pattern.start_time) + ' - ' + formatTime12Hour(pattern.end_time),
                        start: formattedDate + 'T' + pattern.start_time,
                        end: formattedDate + 'T' + pattern.end_time,
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        textColor: '#fff',
                        extendedProps: {
                            eventType: 'pattern',
                            dayOfWeek: pattern.day_of_week,
                            dayName: dayNames[pattern.day_of_week],
                            startTime: pattern.start_time,
                            endTime: pattern.end_time,
                            patternId: pattern.id,
                            startDate: pattern.start_date,
                            endDate: pattern.end_date
                        },
                        classNames: ['recurring-availability']
                    });
                    
                    // Move to the next occurrence (7 days later)
                    currentDate.setDate(currentDate.getDate() + 7);
                }
            });
        }
        
        // Process specific date instances
        if (availabilityInstances && availabilityInstances.length) {
            availabilityInstances.forEach(instance => {
                events.push({
                    id: 'instance_' + instance.id,
                    title: 'Available: ' + formatTime12Hour(instance.start_time) + ' - ' + formatTime12Hour(instance.end_time),
                    start: instance.available_date + 'T' + instance.start_time,
                    end: instance.available_date + 'T' + instance.end_time,
                    backgroundColor: '#ffc107',
                    borderColor: '#ffc107',
                    textColor: '#333',
                    extendedProps: {
                        eventType: 'instance',
                        date: instance.available_date,
                        startTime: instance.start_time,
                        endTime: instance.end_time,
                        instanceId: instance.id
                    },
                    classNames: ['specific-availability']
                });
            });
        }
        
        // Initialize calendar with FullCalendar 5.x
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            editable: false,
            selectable: true,
            dayMaxEvents: false, // Show all events without the "+more" link
            height: 'auto', // Adjust height automatically to fit all events
            events: events,
            dateClick: function(info) {
                // Handle date click to set specific date availability
                handleDateClick(info.date);
            },
            eventClick: function(info) {
                // Handle event click to view details
                showEventDetails(info.event);
            }
        });
        
        calendar.render();
        
        // Add event listener for the "Add Availability" button
        document.getElementById('add-availability-btn').addEventListener('click', function() {
            // Hide other panels and show the form
            document.getElementById('no-date-selected').style.display = 'none';
            document.getElementById('event-details').style.display = 'none';
            document.getElementById('error-container').style.display = 'none';
            document.getElementById('set-availability-form').style.display = 'block';
            
            // Default to pattern form
            toggleAvailabilityType('pattern');
            
            // Set default dates
            const today = new Date();
            const formattedToday = formatDate(today);
            document.getElementById('pattern_start_date').value = formattedToday;
            
            // Calculate and set end date (one month from today)
            updateEndDate();
        });
        
        // Function to handle date click
        function handleDateClick(date) {
            // Format the date as YYYY-MM-DD
            const formattedDate = formatDate(date);
            
            // Don't allow selecting dates in the past
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (date < today) {
                // Show error message
                document.getElementById('no-date-selected').style.display = 'none';
                document.getElementById('set-availability-form').style.display = 'none';
                document.getElementById('event-details').style.display = 'none';
                
                // Show error message in the error container
                const errorContainer = document.getElementById('error-container');
                errorContainer.style.display = 'block';
                errorContainer.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle"></i> You cannot set availability for past dates.</div>';
                return;
            }
            
            // Hide error container if it was shown
            document.getElementById('error-container').style.display = 'none';
            
            // Hide event details and show the form
            document.getElementById('event-details').style.display = 'none';
            document.getElementById('no-date-selected').style.display = 'none';
            document.getElementById('set-availability-form').style.display = 'block';
            
            // Switch to the instance form
            toggleAvailabilityType('instance');
            
            // Set the selected date in the form
            document.getElementById('instance_date').value = formattedDate;
            
            // Focus on the start time input
            setTimeout(() => {
                document.getElementById('instance_start_time').focus();
            }, 100);
        }
        
        // Function to show event details
        function showEventDetails(event) {
            // Hide the form and show event details
            document.getElementById('set-availability-form').style.display = 'none';
            document.getElementById('no-date-selected').style.display = 'none';
            document.getElementById('event-details').style.display = 'block';
            document.getElementById('error-container').style.display = 'none';
            
            // Set event type
            const eventType = event.extendedProps.eventType === 'pattern' ? 'Recurring Availability' : 'Specific Date Availability';
            document.getElementById('event-type').textContent = eventType;
            
            // Show/hide appropriate rows
            if (event.extendedProps.eventType === 'pattern') {
                document.getElementById('day-of-week-row').style.display = 'block';
                document.getElementById('date-row').style.display = 'none';
                document.getElementById('date-range-row').style.display = 'block';
                
                // Set day of week
                document.getElementById('event-day').textContent = event.extendedProps.dayName;
                
                // Set date range
                const startDate = new Date(event.extendedProps.startDate);
                const endDate = new Date(event.extendedProps.endDate);
                document.getElementById('event-date-range').textContent = 
                    formatDateLong(startDate) + ' - ' + formatDateLong(endDate);
            } else {
                document.getElementById('day-of-week-row').style.display = 'none';
                document.getElementById('date-row').style.display = 'block';
                document.getElementById('date-range-row').style.display = 'none';
                
                // Set date
                const eventDate = new Date(event.extendedProps.date);
                document.getElementById('event-date').textContent = formatDateLong(eventDate);
            }
            
            // Set time
            document.getElementById('event-time').textContent = 
                formatTime12Hour(event.extendedProps.startTime) + ' - ' + formatTime12Hour(event.extendedProps.endTime);
        }
        
        // Add validation for time selection
        function validateTimeSelection() {
            // For pattern form
            const startTime = document.getElementById('start_time');
            const endTime = document.getElementById('end_time');
            
            startTime.addEventListener('change', function() {
                const startHour = parseInt(this.value.split(':')[0]);
                
                // Reset end time options
                // Reset end time options
                while (endTime.options.length > 0) {
                    endTime.remove(0);
                }
                
                // Add only valid end times (must be after start time)
                for (let i = startHour + 1; i <= 22; i++) {
                    const option = document.createElement('option');
                    option.value = `${String(i).padStart(2, '0')}:00:00`;
                    option.text = formatTime12Hour(`${String(i).padStart(2, '0')}:00:00`);
                    endTime.add(option);
                }
                
                // Select the first option
                if (endTime.options.length > 0) {
                    endTime.selectedIndex = 0;
                }
            });
            
            // For instance form
            const instanceStartTime = document.getElementById('instance_start_time');
            const instanceEndTime = document.getElementById('instance_end_time');
            
            instanceStartTime.addEventListener('change', function() {
                const startHour = parseInt(this.value.split(':')[0]);
                
                // Reset end time options
                while (instanceEndTime.options.length > 0) {
                    instanceEndTime.remove(0);
                }
                
                // Add only valid end times (must be after start time)
                for (let i = startHour + 1; i <= 22; i++) {
                    const option = document.createElement('option');
                    option.value = `${String(i).padStart(2, '0')}:00:00`;
                    option.text = formatTime12Hour(`${String(i).padStart(2, '0')}:00:00`);
                    instanceEndTime.add(option);
                }
                
                // Select the first option
                if (instanceEndTime.options.length > 0) {
                    instanceEndTime.selectedIndex = 0;
                }
            });
        }
        
        // Call the validation function
        validateTimeSelection();
        
        // Set minimum dates on page load
        const today = new Date();
        const formattedToday = formatDate(today);
        document.getElementById('pattern_start_date').min = formattedToday;
        document.getElementById('instance_date').min = formattedToday;
        
        // Initialize end date
        updateEndDate();
        
        // Trigger initial validation to ensure end time options are properly set
        document.getElementById('start_time').dispatchEvent(new Event('change'));
        document.getElementById('instance_start_time').dispatchEvent(new Event('change'));
    });
</script>
