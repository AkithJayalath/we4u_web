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
            <h2>My Appointments</h2>
            <a href="<?php echo URLROOT; ?>/consultant/editAvailability" class="edit-calendar-btn">
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
                    <h3>Appointment Details</h3>
                    <div class="status-legend">
                        <div class="status-item">
                            <span class="status-label">Accepted</span>
                            <span class="status-dot confirmed-dot"></span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Pending</span>
                            <span class="status-dot pending-dot"></span>
                        </div>
                    </div>
                </div>
                <div class="event-details-content" id="no-event-message">
                    <div class="empty-state">
                        <i class="fas fa-calendar-day empty-icon"></i>
                        <p>Select an appointment to view details</p>
                    </div>
                </div>
                <div class="event-details-content" id="event-details" style="display:none;">
                    <div class="detail-card">
                        <div class="detail-row">
                            <span class="detail-label">Appointment Date:</span>
                            <span id="event-date" class="detail-value"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Time Slot:</span>
                            <span id="event-time" class="detail-value"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span id="event-status" class="status-indicator"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Request ID:</span>
                            <span id="request-id" class="detail-value"></span>
                        </div>
                    </div>
                    <div class="event-actions">
                        <a id="view-request-link" href="#" class="view-request-button">
                            <i class="fas fa-eye"></i> View Full Request
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<?php require APPROOT . '/views/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Log the consultant ID
    console.log('Consultant ID:', <?php echo json_encode($data['consultant_id']); ?>);
    
    // Convert PHP data to JavaScript
    const bookingsData = <?php echo json_encode($data['bookings'] ?? []); ?>;
    
    // Debug: Log the raw bookings data
    console.log('Raw bookings data:', bookingsData);
    
    // Process events for the calendar
    const events = [];
    
    // Process bookings
    if (bookingsData && bookingsData.length > 0) {
        console.log('Processing ' + bookingsData.length + ' bookings');
        
        bookingsData.forEach(function(booking) {
            console.log('Processing booking:', booking);
            
            // Ensure start_time and end_time have proper format (add seconds if needed)
            let startTime = booking.start_time;
            if (startTime && !startTime.includes(':')) {
                startTime = startTime + ':00';
            }
            
            let endTime = booking.end_time;
            if (endTime && !endTime.includes(':')) {
                endTime = endTime + ':00';
            }
            
            // Create a time slot string for display
            let timeSlot = startTime + ' - ' + endTime;
            
            // Format the event start and end times
            let eventStart = booking.appointment_date + 'T' + startTime;
            let eventEnd = booking.appointment_date + 'T' + endTime;
            
            console.log('Event time:', eventStart, 'to', eventEnd);
            
            // Determine status color
            let statusColor = getStatusColor(booking.status);
            let textColor = booking.status === 'pending' ? '#333' : '#fff';
            
            // Create the event object
            const event = {
                id: 'booking_' + booking.request_id,
                title: 'Appointment: ' + timeSlot,
                start: eventStart,
                end: eventEnd,
                backgroundColor: statusColor,
                borderColor: statusColor,
                textColor: textColor,
                className: 'status-' + booking.status,
                extendedProps: {
                    requestId: booking.request_id,
                    date: booking.appointment_date,
                    timeSlot: timeSlot,
                    status: booking.status,
                    elderId: booking.elder_id
                }
            };
            
            console.log('Created event:', event);
            events.push(event);
        });
    } else {
        console.log('No bookings found or bookings array is empty');
    }
    
    console.log('Final events for calendar:', events);
    
    // Helper function to get color based on status
    function getStatusColor(status) {
        switch(status) {
            case 'accepted':
                return '#28a745'; // Green
            case 'pending':
                return '#ffc107'; // Yellow
            case 'rejected':
            case 'cancelled':
                return '#dc3545'; // Red
            default:
                return '#007bff'; // Blue
        }
    }
    
    // Initialize calendar with FullCalendar 5.x
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('Calendar element not found!');
        return;
    }
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        editable: false,
        selectable: false,
        dayMaxEvents: true, // Show all events without the "+more" link
        height: 'auto', // Adjust height automatically to fit all events
        events: events,
        eventClick: function(info) {
            // Handle event click to view details
            console.log('Event clicked:', info.event);
            showEventDetails(info.event);
        },
        // Debug: Log when events are rendered
        eventDidMount: function(info) {
            console.log('Event mounted:', info.event.title);
        },
        // Debug: Log when calendar is fully rendered
        datesSet: function() {
            console.log('Calendar dates set, events should be visible now');
        }
    });
    
    calendar.render();
    console.log('Calendar rendered');
    
    // Function to show event details
    function showEventDetails(event) {
        // Hide the empty message and show event details
        document.getElementById('no-event-message').style.display = 'none';
        document.getElementById('event-details').style.display = 'block';
        
        // Set date
        const eventDate = new Date(event.extendedProps.date);
        document.getElementById('event-date').textContent = formatDateLong(eventDate);
        
        // Set time
        document.getElementById('event-time').textContent = event.extendedProps.timeSlot;
        
        // Set request ID
        document.getElementById('request-id').textContent = event.extendedProps.requestId;
        
        // Set status with appropriate class
        const statusElement = document.getElementById('event-status');
        statusElement.textContent = capitalizeFirstLetter(event.extendedProps.status);
        
        // Remove any existing status classes
        statusElement.className = 'status-indicator';
        
        // Add the appropriate status class
        if (event.extendedProps.status === 'accepted' || event.extendedProps.status === 'approved') {
            statusElement.classList.add('status-accepted');
        } else if (event.extendedProps.status === 'pending') {
            statusElement.classList.add('status-pending');
        } else {
            statusElement.classList.add('status-unavailable');
        }
        
        // Update the view request link
        const viewRequestLink = document.getElementById('view-request-link');
        viewRequestLink.href = '<?php echo URLROOT; ?>/consultant/viewreqinfo/' + event.extendedProps.requestId;
    }
    
    // Helper function to capitalize first letter
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Format date as "Month Day, Year"
    function formatDateLong(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }
});


</script>

<style>
/* Status colors for events */
.fc-event.status-accepted {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: #fff !important;
}

.fc-event.status-pending {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #333 !important;
}

.fc-event.status-rejected, .fc-event.status-cancelled {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: #fff !important;
}

/* Status indicator in details panel */
.status-indicator.status-accepted {
    background-color: #28a745;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
}

.status-indicator.status-pending {
    background-color: #ffc107;
    color: #333;
    padding: 4px 8px;
    border-radius: 4px;
}

.status-indicator.status-unavailable {
    background-color: #dc3545;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
}

/* Status dots in legend */
.status-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left: 6px;
}

.confirmed-dot {
    background-color: #28a745;
}

.pending-dot {
    background-color: #ffc107;
}

/* Make sure calendar is visible */
#calendar {
    min-height: 500px;
    border: 1px solid #ddd;
    background-color: white;
}
</style>