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
                <div class="detail-label">Service:</div>
                <div class="detail-value">Caregiving</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Client (payer):</div>
                <div class="detail-value">Smith robert</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Caregiver:</div>
                <div class="detail-value">John Doe</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Service Type:</div>
                <div class="detail-value">Short Term 7-12 , 13-19</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Service Date:</div>
                <div class="detail-value">April 10 , 2025</div>
            </div>

            <div class="payment-breakdown">
                <h3>Your Payment Breakdown</h3>
                <div class="breakdown-row">
                    <div class="breakdown-label">payment for 1 slot:</div>
                    <div class="breakdown-value">Rs.1500</div>
                </div>
                <div class="breakdown-row">
                    <div class="breakdown-label">Number of slots:</div>
                    <div class="breakdown-value">2</div>
                </div>
                <div class="breakdown-row">
                    
                    <div class="breakdown-value">Rs.1500 * 2</div>
                </div>
                <div class="breakdown-row">
                    <div class="breakdown-label">Additional Payments:</div>
                    <div class="breakdown-value">-</div>
                </div>

                <div class="breakdown-row total">
                    <div class="breakdown-label">Total Payment:</div>
                    <div class="breakdown-value">Rs.3000</div>
                </div>

                
        

            </div>

            <form action="<?php echo URLROOT; ?>/payments/stripe" method="post">
                <input type="hidden" name="amount" value="3000"> 
                <input type="hidden" name="description" value="Elderly Care Visit">
                <div class="btn">
                    <button class="proceed-btn" type="submit">Proceed to Payment</button>

                </div>
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

</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';?>
