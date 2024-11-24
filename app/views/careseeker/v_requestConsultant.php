<?php
$required_styles = [
    'careseeker/requestCaregiver',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="request-caregiver">
        <div class="request-heading">
        <p>Send Request</p>
        </div>
        
        <!-- Personal info section -->
        <div class="request-header">
        <div class="request-header-left">
                <div class="request-header-left-left">
                    <div class="circle image1"><img src="https://media.istockphoto.com/id/1759448630/photo/happy-caucasian-young-student-female-looking-at-camera-enjoying-with-a-perfect-white-teeth.jpg?s=612x612&w=0&k=20&c=KbfDI3FjAdGYK5QxTx3PJdxFyx9ZNgvOBd0P7E3Ah38=" alt="Profile"  /></div>
                    <div class="circle image1"><img src="https://media.istockphoto.com/id/1371009338/photo/portrait-of-confident-a-young-dentist-working-in-his-consulting-room.jpg?s=612x612&w=0&k=20&c=I212vN7lPpAOwGKRoEY9kYWunJaMj9vH2g-8YBGc2MI=" alt="Profile"  /></div>
                </div>
                <div class="request-header-left-right">
                    <div class="request-personal-info-profile">

                        <div class="request-personal-info-details">
                            <span class="request-personal-info-tag">Verfied</span>
                            <h2>Dr.Dr.Amal Perera</h2>
                            <span class="request-email">amalperera@gmail.com</span>
                            <p class="consultant-rating">
                                <span class="rating-stars" id="rating-stars"></span>
                            </p>
                            <p>29 years</p>
                            <p>Male</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="request-header-right">
                <button class="request-send-button">
                    <i class="fas fa-paper-plane"></i> Send Request
                </button>
                <button class="request-cancel-button">
                    Cancel
                </button>
            </div>

        </div>
        <div class="request-info-section">
    <div class="request-other-concern-section">
        <div class="request-other-concern-section-content">
            <form class="request-form">
                <div class="form-row two-columns">
                    <!-- Column 1 -->
                    <div class="form-group">
                        <label for="time-slot">Requested Date</label>
                            <label for="from-date">Date:</label>
                            <input type="date" id="from-date" placeholder="12th Jan" />
                    </div>

                    <div class="form-group">
                        <label for="service">Service</label>
                        <input type="text" id="service" placeholder="Consultation" readonly/>
                    </div>
                    <div class="form-group">
                        <label for="time-slot">Requested Time Slot</label>
                        <div class="date-range">
                            <label for="from-time">From:</label>
                            <input type="time" id="from-time" />
                            <label for="to-time">To:</label>
                            <input type="time" id="to-time" />
                        </div>
                    </div>
                    
                </div>

                <div class="form-row two-columns">
                    <!-- Column 2 -->
                    <div class="form-group">
                        <label for="elder-profile">Select Elder Profile</label>
                        <select id="elder-profile">
                            <option value="" disabled selected>Select a profile</option>
                            <option value="elder1">Elder Profile 1</option>
                            <option value="elder2">Elder Profile 2</option>
                            <option value="elder3">Elder Profile 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expected-services">Expected Services</label>
                        <textarea id="expected-services" placeholder="Services you expect from the provider"></textarea>
                    </div>
                </div>

                <div class="form-row two-columns">
                    <!-- Column 3 -->
                    <div class="form-group">
                        <label>Payment Details</label>
                        <input type="text" id="payment-details" placeholder="Rs.4000 per visit + Rs.400 per session" readonly />
                    </div>
                    <div class="form-group">
                        <label for="additional-notes">Additional Notes</label>
                        <textarea id="additional-notes" placeholder="Anything more..."></textarea>
                    </div>
</div>
            </form>
        </div>
    </div>
</div>

            
            </div>

    </div>

</page-body-container>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>