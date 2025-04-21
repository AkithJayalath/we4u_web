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
        </div>
        
        <div class="calendar-wrapper">
            <!-- Calendar on the left -->
            <div class="calendar-container">
                <div id="calendar"></div>
            </div>
            
            <!-- Event details on the right -->
            <div class="event-details-container">
                <div class="event-details-header">
                    <h3>Event Details</h3>
                    <div>
                        <span class="confirmed">confirmed</span> <span class="dot1"></span>
                        <span>           </span>
                        <span class="pending">pending</span> <span class="dot2"></span>
                    </div>

                </div>
                <div class="event-details-content" id="no-event-message">
                    <p>Select an event to view details</p>
                </div>
                <div class="event-details-content" id="event-details" style="display:none;">
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
    
    // Process short schedules
    if (shortSchedules && shortSchedules.length) {
        shortSchedules.forEach(schedule => {
            // Define shift times based on new requirements
            let startTime = '';
            let endTime = '';
            let shiftName = '';
            let nextDay = false;
            
            switch(schedule.shift) {
                case 'day':
                    startTime = '08:00:00';
                    endTime = '12:00:00';
                    shiftName = 'Morning Shift (8:00 AM - 12:00 PM)';
                    break;
                case 'night':
                    startTime = '13:00:00';
                    endTime = '19:00:00';
                    shiftName = 'Afternoon Shift (1:00 PM - 7:00 PM)';
                    break;
                case 'overnight':
                    startTime = '20:00:00';
                    endTime = '08:00:00';
                    shiftName = 'Overnight Shift (8:00 PM - 8:00 AM next day)';
                    nextDay = true;
                    break;
                case 'fullday':
                    startTime = '08:00:00';
                    endTime = '08:00:00';
                    shiftName = 'Full Day Shift (8:00 AM - 8:00 AM next day)';
                    nextDay = true;
                    break;
                default:
                    startTime = '08:00:00';
                    endTime = '16:00:00';
                    shiftName = 'Default Shift (8:00 AM - 4:00 PM)';
            }
            
            // Create event
            const event = {
                id: 'short_' + schedule.id,
                title: 'Shift: ' + capitalizeFirstLetter(schedule.shift),
                start: schedule.sheduled_date + 'T' + startTime,
                backgroundColor: getStatusColor(schedule.status),
                borderColor: getStatusColor(schedule.status),
                textColor: '#fff',
                extendedProps: {
                    eventType: 'short',
                    status: schedule.status,
                    shift: shiftName
                },
                classNames: ['status-' + schedule.status]
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
            events.push({
                id: 'long_' + schedule.id,
                title: 'Long-term Booking',
                start: schedule.start_date_time,
                end: schedule.end_date_time,
                backgroundColor: getStatusColor(schedule.status),
                borderColor: getStatusColor(schedule.status),
                textColor: '#fff',
                extendedProps: {
                    eventType: 'long',
                    status: schedule.status
                },
                classNames: ['status-' + schedule.status]
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
        case 'yes': return '#28a745'; // Green
        case 'pending': return '#ffc107';   // Yellow
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
    statusElement.textContent = capitalizeFirstLetter(event.extendedProps.status);
    
    // Remove any existing status classes
    statusElement.className = 'status-indicator';
    // Add the appropriate status class
    statusElement.classList.add('status-' + event.extendedProps.status);
    
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
</script>
