<?php
$required_styles = [
    'careseeker/viewConsultantRequestInfo',
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

                    $CareseekerprofilePic = !empty($data->requester_pic)
                        ? URLROOT . '/public/images/profile_imgs/' . $data->requester_pic
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
                <div class="request-info-buttons">
                
                <?php if ($data->status == 'pending'): ?>
                    <span class="text-warning" style="color: #007bff; font-weight: bold;">⏳ Request Pending</span>
                
                <?php elseif ($data->status == 'accepted'): ?>
                    <?php if ($data->is_paid == 0): ?>
                        <a href="<?= URLROOT ?>/payments/checkout?type=caregiving&request_id=<?= $data->request_id ?>" class="request-send-button">Make Payment</a>
                    <?php else: ?>
                        <span class="text-success" style="color: #007bff; font-weight: bold;">✅ Payment Done</span>
                    <?php endif; ?>
                <?php endif; ?>
 <?php
// Determine button state: delete or cancel
$disabledStatuses = ['cancelled', 'rejected', 'completed'];
$isDeleteButton = in_array(strtolower($data->status), $disabledStatuses);

// Calculate if cancel button should be disabled (for the cancel functionality only)
$isDisabled = false;

if (!$isDeleteButton) {
    $currentDateTime = new DateTime(); // Current date and time
    
    // Create appointment datetime
    $appointmentDate = new DateTime($data->appointment_date);
    
    // Extract time from the time_slot
    if (!empty($data->time_slot)) {
        [$from, $to] = explode('-', $data->time_slot);
        $fromTime = trim($from);
        
        // Set the appointment time
        list($hours, $minutes) = explode(':', $fromTime);
        $appointmentDate->setTime((int)$hours, (int)$minutes, 0);
    } else {
        // Default to 8:00 AM if no time slot is specified
        $appointmentDate->setTime(8, 0, 0);
    }
    
    // Calculate hours left until appointment
    $hoursLeft = ($appointmentDate->getTimestamp() - $currentDateTime->getTimestamp()) / 3600;
    
    // Check if less than 5 hours left or if appointment has already started
    if ($hoursLeft <= 0 && $data->status != 'pending') {
        $isDisabled = true; // Appointment has already started
    }
}

// Button class and text based on state
$buttonClass = $isDeleteButton ? "request-delete-button" : "request-cancel-button";
$buttonText = $isDeleteButton ? "Delete Request" : "Cancel";
$buttonAction = $isDeleteButton 
    ? "window.location.href='".URLROOT."/careseeker/deleteConsultRequest/".$data->request_id."'"
    : ($isDisabled 
        ? "alert('Cannot cancel an appointment that has already started')" 
        : "openCancelModal()");
?>

<!-- Replace the original cancel button with this code -->
<button class="<?= $buttonClass ?>" 
        <?= (!$isDeleteButton && $isDisabled) ? 'disabled' : '' ?> 
        onclick="<?= $buttonAction ?>">
    <?= $buttonText ?>
</button>

                </div>

            </div>


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
                    <p><?= htmlspecialchars($data->elder_name) ?> (<?= htmlspecialchars($data->relationship_to_careseeker) ?>)</p>
                </div>

                <div class="request-info-row">
                    <label>Requested Slot</label>
                    <p>
                        <?php
                        if (!empty($data->time_slot)) {
                            [$from, $to] = explode('-', $data->time_slot);

                            $fromTime = DateTime::createFromFormat('H:i', trim($from));
                            $toTime = DateTime::createFromFormat('H:i', trim($to));

                            if ($fromTime && $toTime) {
                                echo "From " . $fromTime->format('g:i A') . " to " . $toTime->format('g:i A');
                            } else {
                                echo htmlspecialchars($data->time_slot); // fallback if format is unexpected
                            }
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </p>


                </div>

                <div class="request-info-row">
                    <label>Service</label>
                    <p>Consultation</p>
                </div>
                <div class="request-info-row">
                    <label>Expected Services</label>
                    <p><?= htmlspecialchars($data->expected_services) ?></p>
                </div>

            </div>

            <!-- Second Column -->
            <div class="request-info-column">
                <div class="request-info-row">
                    <label>Service Provider ID</label>
                    <p>#<?= $data->consultant_id ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Provider Name</label>
                    <p><?= htmlspecialchars($data->consultant_name) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Provider Email</label>
                    <p><?= htmlspecialchars($data->consultant_email) ?></p>
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
    <div id="cancelModal" class="cancel-modal">
    <div class="cancel-modal-content">
        <div class="cancel-modal-header">
            <h2>Cancel Consultation</h2>
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
                <div class="fee-info-container">
                    <div class="fee-info" id="fineInfo">
                        <span class="fee-label">Cancellation Fee:</span>
                        <span class="fee-amount" id="fineAmount">Calculating...</span>
                    </div>
                    <div class="fee-info" id="refundInfo">
                        <span class="fee-label">Refund Amount:</span>
                        <span class="fee-amount" id="refundAmount">Calculating...</span>
                    </div>
                </div>
                <div id="cancellationReason" class="cancellation-reason">
                    <!-- Dynamic reason will be inserted here -->
                </div>
                <div class="cancellation-policy">
                    <p><strong>Cancellation Policy:</strong></p>
                    <ul>
                        <li>Cancel more than 5 hours before appointment: Full refund</li>
                        <li>Cancel less than 5 hours before appointment: 10% fee applies</li>
                        <li>Cannot cancel after appointment has started</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="cancel-modal-footer" id="modalFooter">
            <button id="cancelRequestBtn" class="confirm-cancel-btn">Confirm Cancellation</button>
            <button id="closeModalBtn" class="keep-request-btn">Keep Appointment</button>
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
    
    // Close modal functions
    if (closeModalBtn) {
        closeModalBtn.onclick = closeModal;
    }
    
    if (keepRequestBtn) {
        keepRequestBtn.onclick = closeModal;
    }
    
    window.onclick = function(event) {
        if (event.target === cancelModal) {
            closeModal();
        }
    };
    
    // Make openCancelModal available globally
    window.openCancelModal = function() {
        // Show the modal
        if (cancelModal) {
            cancelModal.style.display = 'block';
            
            // Calculate cancellation details
            calculateCancellationDetails();
        }
    };
    
    function closeModal() {
        if (cancelModal) {
            cancelModal.style.display = 'none';
        }
    }
    
    function calculateCancellationDetails() {
        // Get request data from PHP
        const requestData = {
            id: <?= $data->request_id ?>,
            status: '<?= $data->status ?>',
            appointmentDate: '<?= $data->appointment_date ?>',
            timeSlot: '<?= addslashes($data->time_slot) ?>',
            isPaid: <?= isset($data->is_paid) && $data->is_paid ? 'true' : 'false' ?>,
            paymentDetails: <?= $data->payment_details ?? 0 ?>
        };
        
        const now = new Date();
        
        // Parse appointment date and time
        const appointmentDate = new Date(requestData.appointmentDate);
        
        // Extract time from time slot
        if (requestData.timeSlot) {
            const timeParts = requestData.timeSlot.split('-');
            if (timeParts.length > 0) {
                const fromTime = timeParts[0].trim();
                const [hours, minutes] = fromTime.split(':');
                appointmentDate.setHours(parseInt(hours), parseInt(minutes), 0);
            }
        }
        
        // Calculate hours left until appointment
        const hoursLeft = (appointmentDate - now) / (1000 * 60 * 60);
        
        let fineAmount = 0;
        let refundAmount = 0;
        let cancellationMessage = '';
        let reasonMessage = '';
        let messageClass = 'neutral';
        let reasonClass = '';
        let requiresFinePay = false;
        
        // Apply cancellation logic
        if (requestData.status === 'pending') {
            // No fees for pending requests
            if (requestData.isPaid) {
                refundAmount = requestData.paymentDetails;
                cancellationMessage = "You can cancel this appointment with a full refund.";
                reasonMessage = "Your appointment is still pending and hasn't been accepted by the consultant yet. You will receive a full refund of Rs. " + refundAmount.toFixed(2) + ".";
                messageClass = 'success';
                reasonClass = 'success';
            } else {
                cancellationMessage = "You're okay to cancel this appointment.";
                reasonMessage = "Your appointment is still pending and hasn't been accepted by the consultant yet.";
                messageClass = 'success';
                reasonClass = 'success';
            }
        } else if (requestData.status === 'accepted') {
            if (hoursLeft >= 5) {
                // More than 5 hours - full refund
                if (requestData.isPaid) {
                    refundAmount = requestData.paymentDetails;
                    cancellationMessage = "You can cancel this appointment with a full refund.";
                    reasonMessage = "Your cancellation is more than 5 hours before the appointment time. You will receive a full refund of Rs. " + refundAmount.toFixed(2) + ".";
                    messageClass = 'success';
                    reasonClass = 'success';
                } else {
                    cancellationMessage = "You're okay to cancel this appointment.";
                    reasonMessage = "Your cancellation is more than 5 hours before the appointment time.";
                    messageClass = 'success';
                    reasonClass = 'success';
                }
            } else if (hoursLeft > 0) {
                // Less than 5 hours but before start - 10% fee
                if (requestData.isPaid) {
                    fineAmount = requestData.paymentDetails * 0.10;
                    refundAmount = requestData.paymentDetails - fineAmount;
                    cancellationMessage = "Cancellation will incur a fee.";
                    reasonMessage = "Your cancellation is less than 5 hours before the appointment time. A 10% cancellation fee of Rs. " + fineAmount.toFixed(2) + " will be deducted. You will receive a refund of Rs. " + refundAmount.toFixed(2) + ".";
                    messageClass = 'warning';
                    reasonClass = 'warning';
                } else {
                    fineAmount = requestData.paymentDetails * 0.10;
                    cancellationMessage = "Payment required before cancellation.";
                    reasonMessage = "Your cancellation is less than 5 hours before the appointment time. A 10% cancellation fee of Rs. " + fineAmount.toFixed(2) + " must be paid before you can cancel this appointment.";
                    messageClass = 'warning';
                    reasonClass = 'warning';
                    requiresFinePay = true;
                }
            }
        }
        
        // Update the UI - Message
        const cancellationMessageElem = document.getElementById('cancellationMessage');
        if (cancellationMessageElem) {
            cancellationMessageElem.innerHTML = `<p class="status-message ${messageClass}">${cancellationMessage}</p>`;
        }
        
        // Update the UI - Reason
        const cancellationReasonElem = document.getElementById('cancellationReason');
        if (cancellationReasonElem) {
            cancellationReasonElem.innerHTML = `<p>${reasonMessage}</p>`;
            cancellationReasonElem.className = `cancellation-reason ${reasonClass}`;
        }
        
        // Update the UI - Fee Info
        const fineInfo = document.getElementById('fineInfo');
        const refundInfo = document.getElementById('refundInfo');
        const fineAmountElem = document.getElementById('fineAmount');
        const refundAmountElem = document.getElementById('refundAmount');
        
        if (fineAmount > 0 && fineInfo && fineAmountElem) {
            fineInfo.classList.remove('no-fee');
            fineAmountElem.textContent = 'Rs. ' + fineAmount.toFixed(2);
            fineAmountElem.className = 'fee-amount negative';
        } else if (fineInfo) {
            fineInfo.classList.add('no-fee');
        }
        
        if (refundAmount > 0 && refundInfo && refundAmountElem) {
            refundInfo.classList.remove('no-fee');
            refundAmountElem.textContent = 'Rs. ' + refundAmount.toFixed(2);
            refundAmountElem.className = 'fee-amount positive';
        } else if (requestData.isPaid && refundInfo && refundAmountElem) {
            refundInfo.classList.remove('no-fee');
            refundAmountElem.textContent = 'No refund';
            refundAmountElem.className = 'fee-amount';
        } else if (refundInfo) {
            refundInfo.classList.add('no-fee');
        }
        
        // Update buttons based on requirements
        updateModalButtons(requiresFinePay, requestData.id, fineAmount);
    }
    
    function updateModalButtons(requiresFinePay, requestId, fineAmount) {
        const modalFooter = document.getElementById('modalFooter');
        if (!modalFooter) return;
        
        // Clear existing buttons first
        modalFooter.innerHTML = '';
        
        if (requiresFinePay) {
            // Create Pay Fine button
            const payFineButton = document.createElement('button');
            payFineButton.id = 'payFineBtn';
            payFineButton.className = 'pay-fine-btn';
            payFineButton.textContent = 'Pay Fine to Cancel';
            payFineButton.onclick = function() {
                // Redirect to fine payment page
                window.location.href = '<?= URLROOT ?>/payment/payConsultFine/' + requestId;
            };
            modalFooter.appendChild(payFineButton);
        } else {
            // Create Confirm Cancellation button
            const confirmCancelBtn = document.createElement('button');
            confirmCancelBtn.id = 'cancelRequestBtn';
            confirmCancelBtn.className = 'confirm-cancel-btn';
            confirmCancelBtn.textContent = 'Confirm Cancellation';
            confirmCancelBtn.onclick = function() {
                window.location.href = '<?= URLROOT ?>/careseeker/cancelConsultRequest/' + requestId;
            };
            modalFooter.appendChild(confirmCancelBtn);
        }
        
        // Always create Keep Request button
        const keepRequestBtn = document.createElement('button');
        keepRequestBtn.id = 'closeModalBtn';
        keepRequestBtn.className = 'keep-request-btn';
        keepRequestBtn.textContent = 'Keep Appointment';
        keepRequestBtn.onclick = closeModal;
        modalFooter.appendChild(keepRequestBtn);
    }
});
</script>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>