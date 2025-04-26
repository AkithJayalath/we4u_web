<?php
$required_styles = [
    'calendar/calendar',
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
            <h2>Change My Availability</h2>
        </div>
        
        <div class="calendar-wrapper">
            <!-- Calendar on the left -->
            <div class="calendar-container">
                <div id="calendar"></div>
            </div>
            
            <!-- Availability management panel -->
            <div class="event-details-container">
                <div class="event-details-header">
                    <h3>Change Schedule</h3>
                    <div class="status-legend">
                        <div class="status-item">
                            <span class="status-label">Approved</span>
                            <span class="status-dot confirmed-dot"></span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Pending</span>
                            <span class="status-dot pending-dot"></span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Unavailable</span>
                            <span class="status-dot unavailable-dot"></span>
                        </div>
                    </div>
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
                        <p>Select a date to manage your availability</p>
                    </div>
                </div>
                
                <!-- Form for marking unavailability -->
                <div class="event-details-content" id="mark-unavailable-form" style="display:none;">
                    <form action="<?php echo URLROOT; ?>/caregivers/editMyCalendar" method="POST">
                        <div class="form-group">
                            <label for="selected_date">Selected Date:</label>
                            <input type="date" id="selected_date" name="selected_date" class="form-control" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Unavailability Type:</label>
                            <div class="toggle-buttons">
                                <button type="button" id="short-term-btn" class="toggle-btn active" onclick="toggleUnavailabilityType('short')">Time Slot</button>
                                <button type="button" id="long-term-btn" class="toggle-btn" onclick="toggleUnavailabilityType('long')">Date Range</button>
                            </div>
                            <input type="hidden" id="unavailability_type" name="unavailability_type" value="short">
                        </div>
                        
                        <!-- Short-term unavailability options -->
                        <div id="short-term-options">
                            <div class="form-group">
                                <label for="shift">Select Time Slot:</label>
                                <select id="shift" name="shift" class="form-control">
                                    <option value="">Select a time slot</option>
                                    <option value="morning">Morning (8:00 AM - 12:00 PM)</option>
                                    <option value="evening">Afternoon (1:00 PM - 7:00 PM)</option>
                                    <option value="overnight">Overnight (8:00 PM - 8:00 AM)</option>
                                    <option value="fullday">Full Day</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Long-term unavailability options -->
                        <div id="long-term-options" style="display:none;">
                            <div class="form-group">
                                <label for="from_date">From Date:</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" readonly>
                                <small class="form-text text-muted">This is the date you selected on the calendar</small>
                            </div>
                            <div class="form-group">
                                <label for="to_date">To Date:</label>
                                <input type="date" id="to_date" name="to_date" class="form-control">
                                <small class="form-text text-muted">Select the last day you'll be unavailable</small>
                            </div>
                        </div>

                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-times"></i> Mark as Unavailable
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Event details when clicking on an existing event -->
                <div class="event-details-content" id="event-details" style="display:none;">
                    <div class="detail-card">
                        <div class="detail-row">
                            <span class="detail-label">Event Type:</span>
                            <span id="event-type" class="detail-value"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Date:</span>
                            <span id="event-date" class="detail-value"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Time:</span>
                            <span id="event-time" class="detail-value"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span id="event-status" class="status-indicator"></span>
                        </div>
                        <div class="detail-row" id="shift-details" style="display:none;">
                            <span class="detail-label">Shift:</span>
                            <span id="event-shift" class="detail-value"></span>
                        </div>
                        <div class="detail-row" id="duration-details" style="display:none;">
                            <span class="detail-label">Duration:</span>
                            <span id="event-duration" class="detail-value"></span>
                        </div>
                    </div>
                    
                    <!-- Only show delete button for unavailable events -->
                    <div id="unavailable-actions" style="display:none;">
                        <form action="<?php echo URLROOT; ?>/caregivers/editMyCalendar" method="POST">
                            <input type="hidden" id="delete_schedule_id" name="schedule_id">
                            <input type="hidden" id="delete_schedule_type" name="schedule_type">
                            <input type="hidden" name="delete_schedule" value="true">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Remove Unavailability
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<?php require APPROOT . '/views/includes/footer.php'; ?>

<style>
/* Additional styles for the edit calendar view */
.toggle-buttons {
    display: flex;
    margin-bottom: 15px;
}

.toggle-btn {
    flex: 1;
    padding: 8px 12px;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s;
}

.toggle-btn:first-child {
    border-radius: 4px 0 0 4px;
}

.toggle-btn:last-child {
    border-radius: 0 4px 4px 0;
}

.toggle-btn.active {
    background-color: #013CC6;
    color: white;
    border-color: #013CC6;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-actions {
    margin-top: 20px;
    text-align: center;
}

.btn {
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    border: none;
}

.btn-primary {
    background-color: #013CC6;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 30px 0;
    color: #888;
}

.empty-state i {
    font-size: 36px;
    margin-bottom: 10px;
    color: #ccc;
}

.detail-card {
    background-color: #f9f9f9;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 15px;
}

.detail-row {
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-label {
    font-weight: 600;
    color: #555;
    display: block;
    margin-bottom: 5px;
}

.status-legend {
    display: flex;
    align-items: center;
    gap: 12px;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.confirmed-dot {
    background-color: #28a745;
}

.pending-dot {
    background-color: #ffc107;
}

.unavailable-dot {
    background-color: #dc3545;
}

/* Calendar event styling */
.fc-event.unavailable-event {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
}

.fully-booked-day {
    background-color: #f8d7da !important;
    cursor: not-allowed !important;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.error-message i {
    font-size: 18px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Convert PHP data to JavaScript
    const shortSchedules = <?php echo json_encode($data['shortShedules']); ?>;
    const longSchedules = <?php echo json_encode($data['longShedules']); ?>;
    
    // Process events
    const events = [];
    
    // Track fully booked days to prevent clicking
    const fullyBookedDays = new Set();
    
    // Process short schedules
    if (shortSchedules && shortSchedules.length) {
        shortSchedules.forEach(schedule => {
            // Define shift times based on requirements
            let startTime = '';
            let endTime = '';
            let shiftName = '';
            let nextDay = false;
            
            // Check if this is a full day booking
            if (schedule.shift === 'fullday') {
                // Add this date to fully booked days
                fullyBookedDays.add(schedule.sheduled_date);
            }
            
            switch(schedule.shift) {
                case 'morning':
                    startTime = '08:00:00';
                    endTime = '12:00:00';
                    shiftName = 'Morning Shift (8:00 AM - 12:00 PM)';
                    break;
                case 'evening':
                    startTime = '13:00:00';
                    endTime = '19:00:00';
                    shiftName = 'Afternoon Shift (1:00 PM - 7:00 PM)';
                    break;
                case 'overnight':
                    startTime = '20:00:00';
                    endTime = '07:00:00';
                    shiftName = 'Overnight Shift (8:00 PM - 7:00 AM next day)';
                    nextDay = true;
                    break;
                case 'fullday':
                    startTime = '08:00:00';
                    endTime = '07:00:00';
                    shiftName = 'Full Day Shift (8:00 AM - 7:00 AM next day)';
                    nextDay = true;
                    break;
                default:
                    startTime = '08:00:00';
                    endTime = '16:00:00';
                    shiftName = 'Default Shift (8:00 AM - 4:00 PM)';
            }
            
            // Determine if this is an unavailability entry or a booking
            const isUnavailable = schedule.status === 'unavailable';
            
            // Create event
            const event = {
                id: 'short_' + schedule.id,
                title: isUnavailable ? 'Unavailable: ' + capitalizeFirstLetter(schedule.shift) : 'Shift: ' + capitalizeFirstLetter(schedule.shift),
                start: schedule.sheduled_date + 'T' + startTime,
                backgroundColor: isUnavailable ? '#dc3545' : getStatusColor(schedule.status),
                borderColor: isUnavailable ? '#dc3545' : getStatusColor(schedule.status),
                textColor: '#fff',
                extendedProps: {
                    eventType: 'short',
                    status: schedule.status,
                    shift: shiftName,
                    isUnavailable: isUnavailable,
                    scheduleId: schedule.id
                },
                classNames: [isUnavailable ? 'unavailable-event' : 'status-' + schedule.status]
            };
            
            // Handle end time for overnight shifts
            if (nextDay) {
                // Create a date object for the next day
                const endDate = new Date(schedule.sheduled_date);
                endDate.setDate(endDate.getDate() + 1);
                
                // Format the date as YYYY-MM-DD
                const formattedEndDate = formatDate(endDate);
                event.end = formattedEndDate + 'T' + endTime;
            } else {
                event.end = schedule.sheduled_date + 'T' + endTime;
            }
            
            events.push(event);
        });
    }
    
    // Process long schedules
    if (longSchedules && longSchedules.length) {
        longSchedules.forEach(schedule => {
            // Determine if this is an unavailability entry or a booking
            const isUnavailable = schedule.status === 'unavailable';
            
            // Add all dates in this range to fully booked days
            const startDate = new Date(schedule.start_date);
            const endDate = new Date(schedule.end_date);
            
            // Loop through all dates in the range
            let currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                fullyBookedDays.add(formatDate(currentDate));
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            events.push({
                id: 'long_' + schedule.id,
                title: isUnavailable ? 'Unavailable' : 'Long-term Booking',
                start: schedule.start_date + 'T00:00:00',
                end: schedule.end_date + 'T24:00:00',
                backgroundColor: isUnavailable ? '#dc3545' : getStatusColor(schedule.status),
                borderColor: isUnavailable ? '#dc3545' : getStatusColor(schedule.status),
                textColor: '#fff',
                extendedProps: {
                    eventType: 'long',
                    status: schedule.status,
                    isUnavailable: isUnavailable,
                    scheduleId: schedule.id
                },
                classNames: [isUnavailable ? 'unavailable-event' : 'status-' + schedule.status]
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
            // Check if this date is fully booked
            const formattedDate = formatDate(info.date);
            if (fullyBookedDays.has(formattedDate)) {
                // Show error message
                document.getElementById('no-date-selected').style.display = 'none';
                document.getElementById('mark-unavailable-form').style.display = 'none';
                document.getElementById('event-details').style.display = 'none';
                
                // Show error message in the error container
                const errorContainer = document.getElementById('error-container');
                errorContainer.style.display = 'block';
                errorContainer.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle"></i> This day is fully scheduled and cannot be modified.</div>';
                return;
            }
            
            // Hide error container if it was shown
            document.getElementById('error-container').style.display = 'none';
            
            // Handle date click to mark unavailability
            handleDateClick(info.date);
        },
        eventClick: function(info) {
            // Hide error container if it was shown
            document.getElementById('error-container').style.display = 'none';
            
            // Handle event click to view details or remove unavailability
            showEventDetails(info.event);
        },
        // Add custom rendering for fully booked days
        dayCellDidMount: function(info) {
            const formattedDate = formatDate(info.date);
            if (fullyBookedDays.has(formattedDate)) {
                // Add a class to fully booked days
                info.el.classList.add('fully-booked-day');
            }
        }
    });
    
    calendar.render();
    
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
            document.getElementById('mark-unavailable-form').style.display = 'none';
            document.getElementById('event-details').style.display = 'none';
            
            // Show error message in the error container
            const errorContainer = document.getElementById('error-container');
            errorContainer.style.display = 'block';
            errorContainer.innerHTML = '<div class="error-message"><i class="fas fa-exclamation-circle"></i> You cannot modify availability for past dates.</div>';
            return;
        }
        
        // Hide event details and show the form
        document.getElementById('event-details').style.display = 'none';
        document.getElementById('no-date-selected').style.display = 'none';
        document.getElementById('mark-unavailable-form').style.display = 'block';
        document.getElementById('error-container').style.display = 'none';
        
        // Set the selected date in the form
        document.getElementById('selected_date').value = formattedDate;
        
        // If it's a long-term selection, set the from date
        document.getElementById('from_date').value = formattedDate;
        
        // Set minimum date for to_date
        document.getElementById('to_date').min = formattedDate;
    }
    
    // Function to show event details
    function showEventDetails(event) {
        // Hide the form and show event details
        document.getElementById('mark-unavailable-form').style.display = 'none';
        document.getElementById('no-date-selected').style.display = 'none';
        document.getElementById('event-details').style.display = 'block';
        
        // Set event type
        document.getElementById('event-type').textContent = 
            event.extendedProps.eventType === 'short' ? 'Short-term ' : 'Long-term ';
        
        document.getElementById('event-type').textContent += 
            event.extendedProps.isUnavailable ? 'Unavailability' : 'Booking';
        
        // Set date
        const startDate = new Date(event.start);
        const endDate = new Date(event.end || event.start);
        
        if (formatDate(startDate) === formatDate(endDate)) {
            document.getElementById('event-date').textContent = formatDateLong(startDate);
        } else {
            document.getElementById('event-date').textContent = 
                formatDateLong(startDate) + ' - ' + formatDateLong(endDate);
        }
        
        // Set time
        document.getElementById('event-time').textContent = formatTime(startDate);
        
        // Set status with appropriate class
        const statusElement = document.getElementById('event-status');
        statusElement.textContent = event.extendedProps.isUnavailable ? 'Unavailable' : capitalizeFirstLetter(event.extendedProps.status);
        
        // Remove any existing status classes
        statusElement.className = 'status-indicator';
        // Add the appropriate status class
        statusElement.classList.add(event.extendedProps.isUnavailable ? 'status-unavailable' : 'status-' + event.extendedProps.status);
        
        // Show/hide shift details
        if (event.extendedProps.eventType === 'short') {
            document.getElementById('shift-details').style.display = 'block';
            document.getElementById('event-shift').textContent = event.extendedProps.shift;
            document.getElementById('duration-details').style.display = 'none';
        } else {
            document.getElementById('shift-details').style.display = 'none';
            document.getElementById('duration-details').style.display = 'block';
            
            // Calculate duration
            const durationMs = endDate - startDate;
            const days = Math.floor(durationMs / (24 * 60 * 60 * 1000));
            
            let durationText = days + ' day' + (days > 1 ? 's' : '');
            document.getElementById('event-duration').textContent = durationText;
        }
        
        // Show/hide delete button for unavailable events
        if (event.extendedProps.isUnavailable) {
            document.getElementById('unavailable-actions').style.display = 'block';
            document.getElementById('delete_schedule_id').value = event.extendedProps.scheduleId;
            document.getElementById('delete_schedule_type').value = event.extendedProps.eventType;
        } else {
            document.getElementById('unavailable-actions').style.display = 'none';
        }
    }
    
        // Function to toggle between short-term and long-term unavailability
        window.toggleUnavailabilityType = function(type) {
            const shortTermBtn = document.getElementById('short-term-btn');
            const longTermBtn = document.getElementById('long-term-btn');
            const shortTermOptions = document.getElementById('short-term-options');
            const longTermOptions = document.getElementById('long-term-options');
            const unavailabilityType = document.getElementById('unavailability_type');
            
            if (type === 'short') {
                shortTermBtn.classList.add('active');
                longTermBtn.classList.remove('active');
                shortTermOptions.style.display = 'block';
                longTermOptions.style.display = 'none';
                unavailabilityType.value = 'short';
            } else {
                shortTermBtn.classList.remove('active');
                longTermBtn.classList.add('active');
                shortTermOptions.style.display = 'none';
                longTermOptions.style.display = 'block';
                unavailabilityType.value = 'long';
                
                // Use the already selected date as the start date
                const selectedDate = document.getElementById('selected_date').value;
                document.getElementById('from_date').value = selectedDate;
                
                // Set minimum date for to_date to be the selected date
                document.getElementById('to_date').min = selectedDate;
                
                // Focus on the end date input since that's all we need now
                setTimeout(() => {
                    document.getElementById('to_date').focus();
                }, 100);
            }
        }


});

// Helper function to capitalize first letter
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

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
// Format time as "h:mm AM/PM"
function formatTime(date) {
    const options = { hour: 'numeric', minute: '2-digit', hour12: true };
    return date.toLocaleTimeString('en-US', options);
}

function getStatusColor(status) {
    switch(status) {
        case 'approved': return '#28a745'; // Green for confirmed
        case 'pending': return '#ffc107'; // Yellow for pending
        case 'unavailable': return '#dc3545'; // Red for unavailable
        default: return '#007bff'; // Blue for default
    }
}
</script>
