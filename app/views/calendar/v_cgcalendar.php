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
            <h2>My Calendar</h2>
            <a href="<?php echo URLROOT; ?>/caregivers/editMyCalendar" class="edit-calendar-btn">
            <i class="fas fa-edit"></i> Edit Availability
            </a>
        </div>
        
        <div class="calendar-wrapper">
            <!-- Calendar on the left -->
            <div class="calendar-container">
                <div id="calendar"></div>
            </div>
            
            <!-- event details  -->
            <div class="event-details-container">
                <div class="event-details-header">
                    <h3>Event Details</h3>
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
                <div class="event-details-content" id="no-event-message">
                    <div class="empty-state">
                        <i class="fas fa-calendar-day empty-icon"></i>
                        <p>Select an event to view details</p>
                    </div>
                </div>
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
                        <div class="detail-row" id="request-id-row" style="display:none;">
                            <span class="detail-label">Request ID:</span>
                            <span id="request-id" class="detail-value"></span>
                        </div>
                    </div>
                    <div class="event-actions">
                        <button id="view-request-btn" class="view-request-button">
                            <i class="fas fa-external-link-alt"></i> View Request
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</page-body-container>

<?php require APPROOT . '/views/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Convert PHP data to JavaScript
    const shortSchedules = <?php echo json_encode($data['shortShedules']); ?>;
    const longSchedules = <?php echo json_encode($data['longShedules']); ?>;
    
    // Process events
    const events = [];
    
    // Track fully booked days
    const fullyBookedDays = new Set();
    
    // Process short schedules
    if (shortSchedules && shortSchedules.length) {
        shortSchedules.forEach(schedule => {
            // Define shift times based on new requirements
            let startTime = '';
            let endTime = '';
            let shiftName = '';
            let nextDay = false;
            
            // Check if this is a full day booking
            if (schedule.shift === 'fullday') {
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
                    shiftName = 'Evening Shift (1:00 PM - 7:00 PM)';
                    break;
                case 'overnight':
                    startTime = '20:00:00';
                    endTime = '08:00:00';
                    shiftName = 'Overnight Shift (8:00 PM - 8:00 AM next day)';
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
            
            // Determine if this is an unavailability entry
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
                    scheduleId: schedule.id,
                    requestId: schedule.request_id
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
            // Determine if this is an unavailability entry
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
                    scheduleId: schedule.id,
                    requestId: schedule.request_id
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
        dayMaxEvents: false, // Show all events without the "+more" link
        height: 'auto', // Adjust height automatically to fit all events
        events: events,
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        eventDidMount: function(info) {
            // Check if this is a long-term event
            if (info.event.extendedProps.eventType === 'long') {
                info.el.style.height = 'auto';
                info.el.style.minHeight = '30px';
                info.el.style.paddingTop = '6px';
                info.el.style.paddingBottom = '6px';
                info.el.style.fontSize = '0.9em';
                info.el.style.fontWeight = 'bold';
            }
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

function getStatusColor(status) {
    switch(status) {
        case 'approved': return '#28a745'; // Green
        case 'pending': return '#ffc107';   // Yellow
        case 'unavailable': return '#dc3545'; // Red
        default: return '#007bff';          // Blue
    }
}

function showEventDetails(event) {
    // Hide no event message
    document.getElementById('no-event-message').style.display = 'none';
    
    // Show event details
    document.getElementById('event-details').style.display = 'block';
    
    // Set event type
    document.getElementById('event-type').textContent = 
        event.extendedProps.eventType === 'short' ? 'Short-term Shift' : 'Long-term Booking';
    
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
        const hours = Math.floor((durationMs % (24 * 60 * 60 * 1000)) / (60 * 60 * 1000));
        const minutes = Math.floor((durationMs % (60 * 60 * 1000)) / (60 * 1000));
        
        let durationText = '';
        
        if (days > 0) {
            durationText += days + ' day' + (days > 1 ? 's' : '');
        }
        
        if (hours > 0) {
            if (durationText) durationText += ', ';
            durationText += hours + ' hour' + (hours > 1 ? 's' : '');
        }
        
        if (minutes > 0 && days === 0) {
            if (durationText) durationText += ', ';
            durationText += minutes + ' minute' + (minutes > 1 ? 's' : '');
        }
        
        document.getElementById('event-duration').textContent = durationText;
    }
    
    // Show/hide request ID and view request button
    if (event.extendedProps.requestId) {
        document.getElementById('request-id-row').style.display = 'block';
        document.getElementById('request-id').textContent = event.extendedProps.requestId;
        document.getElementById('view-request-btn').style.display = 'inline-block';
        // Store the request ID for the view request button
        document.getElementById('view-request-btn').setAttribute('data-request-id', event.extendedProps.requestId);
    } else {
        document.getElementById('request-id-row').style.display = 'none';
        document.getElementById('view-request-btn').style.display = 'none';
    }
}

// Format date as "Month Day, Year"
function formatDateLong(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// Format time as "h:mm AM/PM"
function formatTime(date) {
    const options = { hour: 'numeric', minute: '2-digit', hour12: true };
    return date.toLocaleTimeString('en-US', options);
}

// View request button handler
document.getElementById('view-request-btn').addEventListener('click', function() {
    const requestId = this.getAttribute('data-request-id');
    if (requestId) {
        window.location.href = `<?php echo URLROOT; ?>/caregivers/viewreqinfo/${requestId}`;
    }
});
</script>
