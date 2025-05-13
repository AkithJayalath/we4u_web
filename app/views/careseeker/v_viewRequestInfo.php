<?php
$required_styles = [
    'careseeker/viewRequestInfo',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    
    <div class="request-info">
        <div class="request-info-heading">
            <p>Request Information</p>
        </div>

        
        <div class="request-info-header">
            <div class="request-info-header-left">
                <div class="request-info-header-left-left">
                    <?php

                    
                    $CaregiverprofilePic = !empty($data->caregiver_pic)
                        ? URLROOT . '/public/images/profile_imgs/' . $data->caregiver_pic
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
                    <div class="info-circle image1"><img src="<?= $CaregiverprofilePic ?>" alt="Profile" /></div>
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

$disabledStatuses = ['cancelled', 'rejected', 'completed'];
$showButton = !in_array(strtolower($data->status), $disabledStatuses);


if ($showButton) {
    
    $isDisabled = false;
    $currentDateTime = new DateTime(); 
    $startDate = new DateTime($data->start_date);
    
    
    if (isset($data->duration_type) && $data->duration_type === 'long-term') {
       
        $startDate->setTime(8, 0, 0);
    } else {
       
        $earliestTime = null;

       
        $raw = trim($data->time_slots, " \t\n\r\0\x0B\"");
        $cleaned = stripslashes($raw);
        $slots = json_decode($cleaned);

       
        $timeSlotMap = [
            'morning' => '08:00',
            'evening' => '13:00',
            'overnight' => '20:00',
            'full-day' => '08:00'
        ];

        
        if (is_array($slots)) {
            $earliestHour = 24; 

            foreach ($slots as $slot) {
                $slot = strtolower($slot);
                if (isset($timeSlotMap[$slot])) {
                    $slotHour = (int)substr($timeSlotMap[$slot], 0, 2);
                    if ($slotHour < $earliestHour) {
                        $earliestHour = $slotHour;
                        $earliestTime = $timeSlotMap[$slot];
                    }
                }
            }
        }

       
        if ($earliestTime) {
            list($hour, $minute) = explode(':', $earliestTime);
            $startDate->setTime((int)$hour, (int)$minute, 0);
        } else {
            $startDate->setTime(8, 0, 0); // Default to 8:00 AM 
        }
    }

    // Check if service has started (current time is past start time)
    if ($currentDateTime >= $startDate && $data->status != 'pending') {
        $isDisabled = true;
    }

    // Only render button if we should show it
    ?>
    <button class="request-cancel-button"
        <?= $isDisabled ? 'disabled' : '' ?>
        onclick="<?= $isDisabled 
            ? "alert('Cannot cancel a service that has already started')" 
            : "window.location.href='" . URLROOT . "/careseeker/cancelCaregivingRequest'" ?>">
        Cancel
    </button>
<?php
}
?>
                    
                </div>

            </div>


        </div>
        <div class="request-infos-section">
            <!-- First Column -->
            <div class="request-info-column">
                <div class="request-info-row">
                    <label>Requested Date</label>
                    <p>
                        <?= date('d/m/Y', strtotime($data->start_date)) ?>
                        <?= $data->end_date ? ' - ' . date('d/m/Y', strtotime($data->end_date)) : '' ?>
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
                        // First, clean up stray trailing quotes or whitespace
                        $raw = trim($data->time_slots, " \t\n\r\0\x0B\"");

                        // Remove escaped quotes if needed
                        $cleaned = stripslashes($raw);

                        // Decode into array
                        $slots = json_decode($cleaned);

                        // Display output
                        if (is_array($slots)) {
                            echo htmlspecialchars(implode(' + ', array_map('ucfirst', $slots)));
                        } else {
                            echo 'Invalid time slots';
                        }
                        ?>
                    </p>
                </div>


                <div class="request-info-row">
                    <label>Service</label>
                    <p>Caregiving</p>
                </div>
                <div class="request-info-row">
                    <label>Service Address</label>
                    <p><?= htmlspecialchars($data->service_address) ?></p>
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
                    <p>#<?= $data->caregiver_id ?></p>
                        <a href="<?= URLROOT ?>/careseeker/viewCaregiverProfile/<?= $data->caregiver_id ?>" class="view-elder-profile-link">( View Profile )</a>
                </div>
                <div class="request-info-row">
                    <label>Service Provider Name</label>
                    <p><?= htmlspecialchars($data->caregiver_name) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Provider Email</label>
                    <p><?= htmlspecialchars($data->caregiver_email) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Total Payment</label>
                    <p>Rs. <?= $data->payment_details ?? 'N/A' ?></p>
                </div>
                <?php if ($data->status === 'cancelled'): ?>
    <?php if ($data->refund_amount > 0): ?>
        <div class="request-info-row">
            <label>Refundable Amount</label>
            <p><?= htmlspecialchars($data->refund_amount) ?></p>
        </div>
    <?php endif; ?>
    
    <?php if ($data->fine_amount > 0): ?>
        <div class="request-info-row">
            <label>Fined Amount</label>
            <p><?= htmlspecialchars($data->fine_amount) ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>
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
                            <li>Cancel more than 24 hours before start: Full refund</li>
                            <li>Cancel less than 24 hours before start: 10% fee applies</li>
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


<!-- Floating Chat Button -->
<?php if (strtolower($data->status) === 'accepted' || strtolower($data->status) === 'pending'): ?>
    <div class="floating-chat-button" data-request-id="<?php echo $data->request_id; ?>" data-user-id="<?php echo $_SESSION['user_id']; ?>">
        <i class="fas fa-comments"></i>
        <span class="message-badge hidden">0</span>
    </div>
<?php endif; ?>
</page-body-container>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        const cancelModal = document.getElementById('cancelModal');
        const closeModalBtn = document.querySelector('.close-modal');
        const keepRequestBtn = document.getElementById('closeModalBtn');
        const cancelButton = document.querySelector('.request-cancel-button');
        const modalFooter = document.getElementById('modalFooter');

        if (!cancelButton || cancelButton.disabled) {
            // If button is disabled or doesn't exist, don't set up the modal
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
                startDate: '<?= $data->start_date ?>',
                isPaid: <?= isset($data->is_paid) && $data->is_paid ? 'true' : 'false' ?>,
                paymentDetails: <?= $data->payment_details ?? 0 ?>,
                durationType: '<?= isset($data->duration_type) ? $data->duration_type : "" ?>',
                timeSlots: <?= $data->time_slots ?? '[]' ?>
            };

            const now = new Date();
            const startDateTime = getStartDateTime(requestData);
            const hoursLeft = (startDateTime - now) / (1000 * 60 * 60);

            let fineAmount = 0;
            let refundAmount = 0;
            let cancellationMessage = '';
            let reasonMessage = '';
            let messageClass = 'neutral';
            let reasonClass = '';
            let requiresFinePay = false;

            // Apply cancellation logic similar to the PHP function
            if (requestData.status === 'pending') {
                // No fees for pending requests
                if (requestData.isPaid) {
                    refundAmount = requestData.paymentDetails;
                    cancellationMessage = "You can cancel this request with a full refund.";
                    reasonMessage = "Your request is still pending and hasn't been accepted by the caregiver yet. You will receive a full refund of Rs. " + refundAmount.toFixed(2) + ".";
                    messageClass = 'success';
                    reasonClass = 'success';
                } else {
                    cancellationMessage = "You're okay to cancel this request.";
                    reasonMessage = "Your request is still pending and hasn't been accepted by the caregiver yet.";
                    messageClass = 'success';
                    reasonClass = 'success';
                }
            } else if (requestData.status === 'accepted') {
                if (hoursLeft >= 24) {
                    // More than 24 hours - full refund
                    if (requestData.isPaid) {
                        refundAmount = requestData.paymentDetails;
                        cancellationMessage = "You can cancel this request with a full refund.";
                        reasonMessage = "Your cancellation is more than 24 hours before the service start time. You will receive a full refund of Rs. " + refundAmount.toFixed(2) + ".";
                        messageClass = 'success';
                        reasonClass = 'success';
                    } else {
                        cancellationMessage = "You're okay to cancel this request.";
                        reasonMessage = "Your cancellation is more than 24 hours before the service start time.";
                        messageClass = 'success';
                        reasonClass = 'success';
                    }
                } else if (hoursLeft > 0) {
                    // Less than 24 hours but before start - 10% fee
                    if (requestData.isPaid) {
                        fineAmount = requestData.paymentDetails * 0.10;
                        refundAmount = requestData.paymentDetails - fineAmount;
                        cancellationMessage = "Cancellation will incur a fee.";
                        reasonMessage = "Your cancellation is less than 24 hours before the service start time. A 10% cancellation fee of Rs. " + fineAmount.toFixed(2) + " will be deducted. You will receive a refund of Rs. " + refundAmount.toFixed(2) + ".";
                        messageClass = 'warning';
                        reasonClass = 'warning';
                    } else {
                        fineAmount = requestData.paymentDetails * 0.10;
                        cancellationMessage = "Payment required before cancellation.";
                        reasonMessage = "Your cancellation is less than 24 hours before the service start time. A 10% cancellation fee of Rs. " + fineAmount.toFixed(2) + " must be paid before you can cancel this request.";
                        messageClass = 'warning';
                        reasonClass = 'warning';
                        requiresFinePay = true;
                    }
                }
            }

            // Update the UI - Message
            const cancellationMessageElem = document.getElementById('cancellationMessage');
            cancellationMessageElem.innerHTML = `<p class="status-message ${messageClass}">${cancellationMessage}</p>`;

            // Update the UI - Reason
            const cancellationReasonElem = document.getElementById('cancellationReason');
            cancellationReasonElem.innerHTML = `<p>${reasonMessage}</p>`;
            cancellationReasonElem.className = `cancellation-reason ${reasonClass}`;

            // Update the UI - Fee Info
            const fineInfo = document.getElementById('fineInfo');
            const refundInfo = document.getElementById('refundInfo');
            const fineAmountElem = document.getElementById('fineAmount');
            const refundAmountElem = document.getElementById('refundAmount');

            if (fineAmount > 0) {
                fineInfo.classList.remove('no-fee');
                fineAmountElem.textContent = 'Rs. ' + fineAmount.toFixed(2);
                fineAmountElem.className = 'fee-amount negative';
            } else {
                fineInfo.classList.add('no-fee');
            }

            if (refundAmount > 0) {
                refundInfo.classList.remove('no-fee');
                refundAmountElem.textContent = 'Rs. ' + refundAmount.toFixed(2);
                refundAmountElem.className = 'fee-amount positive';
            } else if (requestData.isPaid) {
                refundInfo.classList.remove('no-fee');
                refundAmountElem.textContent = 'No refund';
                refundAmountElem.className = 'fee-amount';
            } else {
                refundInfo.classList.add('no-fee');
            }

            // Update buttons based on requirements
            updateModalButtons(requiresFinePay, requestData.id, fineAmount);
        }

        function updateModalButtons(requiresFinePay, requestId, fineAmount) {
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
                    window.location.href = '<?= URLROOT ?>/payment/payFine/' + requestId;
                };
                modalFooter.appendChild(payFineButton);
            } else {
                // Create Confirm Cancellation button
                const confirmCancelBtn = document.createElement('button');
                confirmCancelBtn.id = 'cancelRequestBtn';
                confirmCancelBtn.className = 'confirm-cancel-btn';
                confirmCancelBtn.textContent = 'Confirm Cancellation';
                confirmCancelBtn.onclick = function() {
                    window.location.href = '<?= URLROOT ?>/careseeker/cancelCaregivingRequest/' + requestId;
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
            const date = new Date(request.startDate);

            if (request.durationType === 'long-term') {
                date.setHours(8, 0, 0);
            } else {
                // Handle time slots for short-term requests
                const slots = Array.isArray(request.timeSlots) ? request.timeSlots : [];

                if (slots.includes('morning')) {
                    date.setHours(8, 0, 0);
                } else if (slots.includes('afternoon') || slots.includes('evening')) {
                    date.setHours(13, 0, 0);
                } else if (slots.includes('overnight')) {
                    date.setHours(20, 0, 0);
                } else if (slots.includes('full_day') || slots.includes('full-day')) {
                    date.setHours(8, 0, 0);
                } else {
                    // Default time if no slots matched
                    date.setHours(8, 0, 0);
                }
            }

            return date;
        }
    });
</script>
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
</script>
<script src="<?php echo URLROOT; ?>/js/caregiverChatPopup.js">
<script src="<?php echo URLROOT; ?>/js/ratingStars.js">
    <?php require APPROOT . '/views/includes/footer.php' ?>