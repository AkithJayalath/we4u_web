<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/Paycheckout.css"> 

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">



<page-body-container>
<div class="container">
        <div class="header">
            <h1>Secure Payment for ElderCare Services</h1>
        </div>

    <div class="payment-container">
        <div class="payment-details">
            <h2>Payment Details</h2>

            <div class="detail-item">
                <div class="detail-label">Request ID:</div>
                <div class="detail-value">#<?= htmlspecialchars($data['request_id']) ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Service:</div>
                <div class="detail-value"><?= ucfirst($data['type']) ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Client (payer):</div>
                <div class="detail-value"><?= htmlspecialchars($data['payer']) ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Service Provider:</div>
                <div class="detail-value">#<?= $data['provider_id'] ?>      <?= htmlspecialchars($data['provider']) ?> </div>           
             </div>

            <div class="detail-item">
                <div class="detail-label">Service Type:</div>
                <div class="detail-value">
                <?= htmlspecialchars($data['service_type']) ?> (
                    <?php
                        if ($data['type'] === 'caregiving') {
                            if (isset($data['time_slots']) && $data['time_slots'] !== 'N/A') {
                                $slots = json_decode($data['time_slots']);
                                if (is_array($slots)) {
                                    echo htmlspecialchars(implode(' + ', array_map('ucfirst', $slots)));
                                } else {
                                    
                                }
                            } else {
                                echo 'N/A';
                            }
                        } elseif ($data['type'] === 'consulting') {
                            // Just display the plain text time slot
                            echo htmlspecialchars($data['time_slots'] ?? 'N/A');
                        } else {
                            echo 'N/A';
                        }
                        ?>
                )
            </div>
            </div>

            <div class="detail-item">
            <?php

            date_default_timezone_set('Asia/Colombo');


            ?>

                <div class="detail-label">Payment Date & Time:</div>
                <div class="detail-value"><?= date('F j, Y   g:i A') ?></div>
            </div>

            <div class="payment-breakdown">
                

                <div class="breakdown-row total">
                    <div class="breakdown-label">Total Payment:</div>
                    <div class="breakdown-value">Rs.<?= number_format($data['amount'], 2) ?></div>
                </div>

                
        

            </div>

            <div class="btn">
                <button class="proceed-btn" type="button" onclick="handlePayment()">Proceed to Payment</button> 

            </div>

            <form id="paymentForm" action="<?php echo URLROOT; ?>/payments/stripe" method="post">
                <input type="hidden" name="amount" value="<?= $data['amount'] ?>"> 
                <input type="hidden" name="description" value="Elderly Care Visit">
                
            </form>
            

            <div class="policies">
                <h3>Payment Policies</h3>
                <ul>
                    <li>Payments are processed securely through Stripe payment gateway.</li>
                    <li>Cancellations made 24 hours before service are eligible for full refund.</li>
                    <li>Your payment receipt will be emailed immediately.</li>
                    <li>Service quality guarantee: If you're not satisfied, contact us within 48 hours.</li>
                </ul>

                
            </div>

            <div class="help-section">
                <div class="help-title">Need Help?</div>
                <div class="help-text">If you need assistance completing this payment, please call our support team:</div>
                <div class="phone">WE4U (1-800-353-3722)</div>
            </div>
        </div>

        
    </div>
</div>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>Are u sure?</h2>
            <p>Proceed with the payment of Rs.<?= number_format($data['amount'], 2) ?></p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="submitAdd()">Yes, Proceed</button>
                <button class="modal-cancel-btn" onclick="closeAddModal()">Cancel</button>
            </div>
        </div>
    </div>
</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';?>

<script>
    // Modal functions
    function handlePayment() {
        event.preventDefault();
        const modal = document.getElementById("addModal");
        if (modal) {
            modal.style.display = "block";
            document.body.style.overflow = "hidden";
        }
    }

    function submitAdd() {
        document.getElementById('paymentForm').submit();
        closeAddModal();
    }

    function closeAddModal() {
        const modal = document.getElementById("addModal");
        if (modal) {
            modal.style.display = "none";
            document.body.style.overflow = "auto";
        }
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setupDropdowns();
        setupCheckboxes();
        
        // Outside click handler for modal
        window.addEventListener("click", function(event) {
            const addModal = document.getElementById("addModal");
            if (addModal && event.target === addModal) {
                closeAddModal();
            }
        });
    });
</script>



