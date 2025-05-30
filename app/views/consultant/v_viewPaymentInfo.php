<?php
$required_styles = [
    'consultant/viewRequestInfo',
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
            <p>Payment Information</p>
        </div>

        <!-- Personal info section -->
        <div class="request-info-header">
            <div class="request-info-header-left">
                <div class="request-info-header-left-left">
                    <div class="info-circle image1"><img src="https://media.istockphoto.com/id/1759448630/photo/happy-caucasian-young-student-female-looking-at-camera-enjoying-with-a-perfect-white-teeth.jpg?s=612x612&w=0&k=20&c=KbfDI3FjAdGYK5QxTx3PJdxFyx9ZNgvOBd0P7E3Ah38=" alt="Profile" /></div>
                    <div class="info-circle image1"><img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Profile" /></div>
                </div>
                <div class="request-info-header-left-right">
                    <div class="request-info-personal-info-profile">

                        <div class="request-info-personal-info-details">
                            <h2>Request ID #154</h2>
                            <span class="tag accepted">Accepted</span>
                        </div>
                    </div>
                </div>
                <div class="request-info-buttons">
            <button class="request-send-button">
                   Confirm Payment
                </button>
                <button class="request-cancel-button">
                    cancel Payment
                </button>
            </div>

            </div>
           

        </div>
        <div class="request-infos-section">
    <!-- First Column -->
    <div class="request-info-column">
        <div class="request-info-row">
            <label>Requested Date</label>
            <p>12/01/2023</p>
        </div>
        <div class="request-info-row">
            <label>careseeker ID</label>
            <p>#CS1234</p>
        </div>
        <div class="request-info-row">
            <label>Service Requested Time Slot</label>
            <p>From: 12th Jan 9.30 a.m.</p> 
            <p> To: 12th Jan 10.30 a.m.</p>
        </div>
        <div class="request-info-row">
            <label>Service</label>
            <p>Consultation</p>
        </div>
    </div>

    <!-- Second Column -->
    <div class="request-info-column">
        <div class="request-info-row">
            <label>Service Provider ID</label>
            <p>#Cons1234</p>
        </div>
        <div class="request-info-row">
            <label>Service Provider Name</label>
            <p>Dr. Pawan Fernando</p>
        </div>
        <div class="request-info-row">
            <label>Service Provider Email</label>
            <p>lol@gmail.com</p>
        </div>
        <div class="request-info-row">
            <label>Total Payment</label>
            <p>Rs.15,000.00</p>
        </div>
    </div>
</div>

       


    </div>

    </div>

</page-body-container>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>