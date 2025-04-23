<?php
$required_styles = [
    'caregiver/viewRequestInfo',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="request-info">
        <div class="request-info-heading">
            <p>Request Information</p>
        </div>

        <!-- Personal info section -->
        <div class="request-info-header">
    <div class="request-info-header-left">
        <div class="request-info-header-left-left">
            <?php
            // Determine which image to display
            $ConsultantprofilePic = !empty($data->consultant_pic)
                ? URLROOT . '/public/images/profile_imgs/' . $data->consultant_pic
                : URLROOT . '/public/images/def_profile_pic2.jpg';

            $CareseekerprofilePic = !empty($data->careseeker_pic)
                ? URLROOT . '/public/images/profile_imgs/' . $data->careseeker_pic
                : URLROOT . '/public/images/def_profile_pic2.jpg';

            $elderprofilePic = !empty($data->elder_pic)
                ? URLROOT . '/public/images/profile_imgs/' . $data->elder_pic
                : URLROOT . '/public/images/def_profile_pic2.jpg';
            ?>
            <div class="info-circle image1"><img src="<?= $CareseekerprofilePic ?>" alt="Profile" /></div>
            <div class="info-circle image1"><img src="<?= $elderprofilePic ?>" alt="Profile" /></div>
            <div class="info-circle image1"><img src="<?= $ConsultantprofilePic ?>" alt="Profile" /></div>
        </div>
        <div class="request-info-header-left-right">
            <div class="request-info-personal-info-profile">
                <div class="request-info-personal-info-details">
                    <h2>Request ID #<?= $data->request_id ?></h2>
                    <span class="tag <?= strtolower($data->status) ?>">
                        <?= ucfirst($data->status) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($data->status === 'pending'): ?>
    <div class="request-info-buttons">
        <form action="<?= URLROOT ?>/consultant/acceptRequest/<?= $data->request_id ?>" method="post">
            <button class="request-send-button" type="submit">Accept</button>
        </form>
        <form action="<?= URLROOT ?>/consultant/rejectRequest/<?= $data->request_id ?>" method="post">
            <button class="request-reject-button" type="submit">Reject</button>
        </form>
    </div>

<?php elseif ($data->status === 'accepted'): ?>
    <div class="request-info-buttons">
        <form action="<?= URLROOT ?>/consultant/cancelRequest/<?= $data->request_id ?>" method="post">
            <button class="request-cancel-button" type="submit">Cancel Request</button>
        </form>
    </div>
<?php endif; ?>

</div>
        <div class="request-infos-section">
            <!-- First Column -->
            <div class="request-info-column">
                <div class="request-info-row">
                    <label>Requested Date</label>
                    <p>
                        <?= date('d/m/Y', strtotime($data->appointment_date)) ?>
                    </p>
                </div>

                <div class="request-info-row">
    <label>Elder Profile Name</label>
    <p>
        <?= htmlspecialchars($data->elder_name) ?> (<?= htmlspecialchars($data->relationship_to_careseeker) ?>)
        &nbsp;
        <?php if ($data->status !== 'rejected'): ?>
    <a href="<?= URLROOT ?>/consultant/viewCareseeker/<?= $data->elder_id ?>" class="view-elder-profile-link">
        View Profile
    </a>
<?php endif; ?>

    </p>
</div>


                <div class="request-info-row">
                <label>Requested Slot</label>
                <p>
    <?php
    if (!empty($data->start_time) && !empty($data->end_time)) {
        $fromTime = DateTime::createFromFormat('H:i:s', $data->start_time);
        $toTime = DateTime::createFromFormat('H:i:s', $data->end_time);

        if ($fromTime && $toTime) {
            echo "From " . $fromTime->format('g:i A') . " to " . $toTime->format('g:i A');
        } else {
            echo htmlspecialchars($data->start_time . ' - ' . $data->end_time); // fallback
        }
    } else {
        echo "N/A";
    }
    ?>
</p>


                </div>


                <div class="request-info-row">
                    <label>Service</label>
                    <p>Caregiving</p>
                </div>
                <div class="request-info-row">
                    <label>Expected Services</label>
                    <p><?= htmlspecialchars($data->expected_services) ?></p>
                </div>

            </div>

            <!-- Second Column -->
            <div class="request-info-column">
                <div class="request-info-row">
                    <label>Service Requester Name</label>
                    <p><?= htmlspecialchars($data->careseeker_name) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Requester Address</label>
                    <p><?= $data->careseeker_address ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Requester Email</label>
                    <p><?= htmlspecialchars($data->careseeker_email) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Requester Contact</label>
                    <p><?= htmlspecialchars($data->careseeker_contact) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Total Payment</label>
                    <p>Rs. <?= $data->payment_details ?? 'N/A' ?></p>
                </div>
                <div class="request-info-row">
                    <label>Additional Notes</label>
                    <p><?= htmlspecialchars($data->additional_notes) ?></p>
                </div>

            </div>
        </div>




    </div>

    </div>

<!-- Cancel Modal -->
<div id="cancelModal" class="cancel-modal">
    <div class="cancel-modal-content">
        <div class="cancel-modal-header">
            <h2>Cancel Request</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="cancel-modal-body">
            <div class="cancel-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div id="cancellationMessage">
                <!-- Dynamic message will be inserted here -->
            </div>

            <div id="cancellationDetails" class="cancellation-details">
                <div id="cancellationReason" class="cancellation-reason">
                    <!-- Dynamic reason will be inserted here -->
                </div>
                <div class="cancellation-policy">
                    <p><strong>Consultant Cancellation Policy:</strong></p>
                    <ul>
                        <li>Cancel more than 24 hours before start: No penalty</li>
                        <li>Cancel between 12-24 hours before start: Warning flag added to your account</li>
                        <li>Cannot cancel less than 12 hours before service start</li>
                        <li>Cannot cancel after service has started</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="cancel-modal-footer" id="modalFooter">
            <button id="cancelRequestBtn" class="confirm-cancel-btn">Confirm Cancellation</button>
            <button id="closeModalBtn" class="keep-request-btn">Keep Request</button>
        </div>
    </div>
</div>

</page-body-container>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        const cancelModal = document.getElementById('cancelModal');
        const closeModalBtn = document.querySelector('.close-modal');
        const keepRequestBtn = document.getElementById('closeModalBtn');
        const cancelButton = document.querySelector('.request-cancel-button');
        const modalFooter = document.getElementById('modalFooter');

        if (!cancelButton) {
            // If button doesn't exist, don't set up the modal
            return;
        }

        // Override the original cancel button click handler
        cancelButton.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            openCancelModal();
            return false;
        };

        // Close modal functions
        closeModalBtn.onclick = closeModal;
        keepRequestBtn.onclick = closeModal;
        window.onclick = function(event) {
            if (event.target === cancelModal) {
                closeModal();
            }
        };

        function openCancelModal() {
            // Show the modal
            cancelModal.style.display = 'block';

            // Calculate cancellation details
            calculateCancellationDetails();
        }

        function closeModal() {
            cancelModal.style.display = 'none';
        }

        function calculateCancellationDetails() {
            // Get request data from PHP
            const requestData = {
                id: <?= $data->request_id ?>,
                status: '<?= $data->status ?>',
                appointmentDate: '<?= $data->appointment_date ?>',
                startTime: '<?= $data->start_time ?>',
                endTime: '<?= $data->end_time ?>',
                paymentDetails: <?= $data->payment_details ?? 0 ?>
            };

            const now = new Date();
            const startDateTime = getStartDateTime(requestData);
            const hoursLeft = (startDateTime - now) / (1000 * 60 * 60);

            let cancellationMessage = '';
            let reasonMessage = '';
            let messageClass = 'neutral';
            let reasonClass = '';
            let canCancel = true;

            // Check if service has already started
            if (hoursLeft < 0) {
                // Service has already started - cannot cancel
                cancellationMessage = "Cannot cancel an active service.";
                reasonMessage = "This appointment has already started. You cannot cancel a service that is already in progress. Please contact support if you have an emergency situation.";
                messageClass = 'error';
                reasonClass = 'error';
                canCancel = false;
            }
            // Apply cancellation logic for consultants
            else if (hoursLeft >= 24) {
                // More than 24 hours - no flag
                cancellationMessage = "You can cancel this request without penalty.";
                reasonMessage = "Your cancellation is more than 24 hours before the appointment start time.";
                messageClass = 'success';
                reasonClass = 'success';
            } else if (hoursLeft >= 12) {
                // Between 12-24 hours - flag but can cancel
                cancellationMessage = "Cancellation will add a warning flag to your account.";
                reasonMessage = "Your cancellation is less than 24 hours but more than 12 hours before the appointment start time. This will add a warning flag to your account that may be reviewed by administrators.";
                messageClass = 'warning';
                reasonClass = 'warning';
            } else {
                // Less than 12 hours - cannot cancel
                cancellationMessage = "You cannot cancel this request.";
                reasonMessage = "Cancellations are not allowed less than 12 hours before the appointment start time. Please contact support if you have an emergency situation.";
                messageClass = 'error';
                reasonClass = 'error';
                canCancel = false;
            }

            // Update the UI - Message
            const cancellationMessageElem = document.getElementById('cancellationMessage');
            cancellationMessageElem.innerHTML = `<p class="status-message ${messageClass}">${cancellationMessage}</p>`;

            // Update the UI - Reason
            const cancellationReasonElem = document.getElementById('cancellationReason');
            cancellationReasonElem.innerHTML = `<p>${reasonMessage}</p>`;
            cancellationReasonElem.className = `cancellation-reason ${reasonClass}`;

            // Update buttons based on whether cancellation is allowed
            updateModalButtons(canCancel, requestData.id, hoursLeft);
        }

        function updateModalButtons(canCancel, requestId, hoursLeft) {
            // Clear existing buttons first
            modalFooter.innerHTML = '';

            if (canCancel) {
                // Create Confirm Cancellation button
                const confirmCancelBtn = document.createElement('button');
                confirmCancelBtn.id = 'cancelRequestBtn';
                confirmCancelBtn.className = 'confirm-cancel-btn';
                confirmCancelBtn.textContent = 'Confirm Cancellation';
                confirmCancelBtn.onclick = function() {
                    // Add flag parameter if hours left is between 12-24
                    const flagParam = (hoursLeft < 24 && hoursLeft >= 12) ? '/flag' : '';
                    window.location.href = '<?= URLROOT ?>/consultant/cancelRequest/' + requestId + flagParam;
                };
                modalFooter.appendChild(confirmCancelBtn);
            }

            // Always create Keep Request button
            const keepRequestBtn = document.createElement('button');
            keepRequestBtn.id = 'closeModalBtn';
            keepRequestBtn.className = 'keep-request-btn';
            keepRequestBtn.textContent = 'Keep Request';
            keepRequestBtn.onclick = closeModal;
            modalFooter.appendChild(keepRequestBtn);
        }

        function getStartDateTime(request) {
    const date = new Date(request.appointmentDate);

    if (request.startTime) {
        const timeParts = request.startTime.split(':');
        if (timeParts.length >= 2) {
            const hours = parseInt(timeParts[0], 10);
            const minutes = parseInt(timeParts[1], 10);
            date.setHours(hours, minutes, 0);
            return date;
        }
    }

    // Default fallback time
    date.setHours(9, 0, 0);
    return date;
}

    });
</script>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>