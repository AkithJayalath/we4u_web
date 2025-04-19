<?php
$required_styles = [
    'calendar/calendar',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>

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
$(document).ready(function() {
    // Convert PHP data to JavaScript
    const shortSchedules = <?php echo json_encode($data['shortShedules']); ?>;
    const longSchedules = <?php echo json_encode($data['longShedules']); ?>;
    
    // Process events
    const events = [];
    
    // Process short schedules
    if (shortSchedules && shortSchedules.length) {
        shortSchedules.forEach(schedule => {
            // Define shift times
            let startTime = '08:00:00';
            let endTime = '16:00:00';
            let shiftName = 'Day Shift (8:00 AM - 4:00 PM)';
            
            if (schedule.shift === 'night') {
                startTime = '16:00:00';
                endTime = '23::00';
                shiftName = 'Night Shift (4:00 PM - 12:00 AM)';
            } else if (schedule.shift === 'overnight') {
                startTime = '00:00:00';
                endTime = '08:00:00';
                shiftName = 'Overnight Shift (12:00 AM - 8:00 AM)';
            }
            
            // Create event
            events.push({
                id: 'short_' + schedule.id,
                title: 'Shift: ' + schedule.shift.charAt(0).toUpperCase() + schedule.shift.slice(1),
                start: schedule.sheduled_date + 'T' + startTime,
                end: schedule.sheduled_date + 'T' + endTime,
                backgroundColor: getStatusColor(schedule.status),
                borderColor: getStatusColor(schedule.status),
                textColor: '#fff',
                eventType: 'short',
                status: schedule.status,
                shift: shiftName,
                className: 'status-' + schedule.status
            });
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
                eventType: 'long',
                status: schedule.status,
                className: 'status-' + schedule.status
            });
        });
    }
    
    // Initialize calendar
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month',
        editable: false,
        eventLimit: false, // Allow "more" link when too many events
        events: events,
        eventClick: function(event) {
            showEventDetails(event);
        }
    });
});

function getStatusColor(status) {
    switch(status) {
        case 'yes': return '#28a745'; // Green
        case 'pending': return '#ffc107';   // Yellow
        case 'cancelled': return '#dc3545'; // Red
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
        event.eventType === 'short' ? 'Short-term Shift' : 'Long-term Booking';
    
    // Set date
    if (moment(event.start).format('YYYY-MM-DD') === moment(event.end).format('YYYY-MM-DD')) {
        document.getElementById('event-date').textContent = moment(event.start).format('MMMM D, YYYY');
    } else {
        document.getElementById('event-date').textContent = 
            moment(event.start).format('MMMM D, YYYY') + ' - ' + moment(event.end).format('MMMM D, YYYY');
    }
    
    // Set time
    document.getElementById('event-time').textContent = 
        moment(event.start).format('h:mm A')
    
    // Set status with appropriate class
    const statusElement = document.getElementById('event-status');
    statusElement.textContent = event.status.charAt(0).toUpperCase() + event.status.slice(1);
    
    // Remove any existing status classes
    statusElement.className = 'status-indicator';
    // Add the appropriate status class
    statusElement.classList.add('status-' + event.status);
    
    // Show/hide shift details
    if (event.eventType === 'short') {
        document.getElementById('shift-details').style.display = 'block';
        document.getElementById('event-shift').textContent = event.shift;
        document.getElementById('duration-details').style.display = 'none';
    } else {
        document.getElementById('shift-details').style.display = 'none';
        document.getElementById('duration-details').style.display = 'block';
        
        // Calculate duration
        const start = moment(event.start);
        const end = moment(event.end);
        const duration = moment.duration(end.diff(start));
        
        let durationText = '';
        const days = Math.floor(duration.asDays());
        const hours = duration.hours();
        const minutes = duration.minutes();
        
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
</script>
