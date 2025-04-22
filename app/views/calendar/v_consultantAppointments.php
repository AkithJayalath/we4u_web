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
            <a href="<?php echo URLROOT; ?>/consultant/editAvailability"

            
            class="edit-calendar-btn">
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
    // Convert PHP data to JavaScript
    const bookings = <?php echo json_encode($data['bookings'] ?? []); ?>;
    
    // Process events for the calendar
    const events = [];
    
    // Process bookings
    if (bookings && bookings.length) {
        bookings.forEach(booking => {
            // Parse time slot (expected format "HH:MM-HH:MM")
            let startTime = '09:00:00';
            let endTime = '10:00:00';
            
            if (booking.time_slot && booking.time_slot.includes('-')) {
                const timeParts = booking.time_slot.split('-');
                if (timeParts.length === 2) {
                    startTime = timeParts[0].trim() + ':00';
                    endTime = timeParts[1].trim() + ':00';
                }
            }
            
            // Determine status color
            let statusColor = getStatusColor(booking.status);
            let textColor = booking.status === 'pending' ? '#333' : '#fff';
            
            events.push({
                id: 'booking_' + booking.request_id,
                title: 'Appointment: ' + booking.time_slot,
                start: booking.appointment_date + 'T' + startTime,
                end: booking.appointment_date + 'T' + endTime,
                backgroundColor: statusColor,
                borderColor: statusColor,
                textColor: textColor,
                extendedProps: {
                    requestId: booking.request_id,
                    date: booking.appointment_date,
                    timeSlot: booking.time_slot,
                    status: booking.status,
                    elderId: booking.elder_id
                }
            });
        });
    }

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
    const calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        editable: false,
        selectable: false,
        dayMaxEvents: false, // Show all events without the "+more" link
        height: 'auto', // Adjust height automatically to fit all events
        events: events,
        eventClick: function(info) {
            // Handle event click to view details
            showEventDetails(info.event);
        }
    });
    
    calendar.render();
    
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
            statusElement.classList.add('status-approved');
        } else if (event.extendedProps.status === 'pending') {
            statusElement.classList.add('status-pending');
        } else {
            statusElement.classList.add('status-unavailable');
        }
        
        // Update the view request link
        const viewRequestLink = document.getElementById('view-request-link');
        viewRequestLink.href = '<?php echo URLROOT; ?>/consultant/viewreqinfo/' + event.extendedProps.requestId;
    }
});

// Helper function to capitalize first letter
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Format date as "Month Day, Year"
    function formatDateLong(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }
</script>



<style>/* Status colors for events */
    .fc-event.status-approved {
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
    .status-indicator.status-approved {
        background-color: #28a745;
        color: white;
    }

    .status-indicator.status-pending {
        background-color: #ffc107;
        color: #333;
    }

    .status-indicator.status-rejected, .status-indicator.status-cancelled {
        background-color: #dc3545;
        color: white;
    }
</style
