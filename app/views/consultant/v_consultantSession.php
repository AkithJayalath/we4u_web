<?php
$required_styles = [
    'consultant/viewConsultantSession',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="session-info">
        <div class="session-info-heading">
            <p>Consultant Session</p>
        </div>

        <!-- Personal info section -->
        <div class="session-info-header">
            <div class="session-info-header-left">
                <div class="session-info-header-left-left">
                    <div class="session-info-circle image1"><img src="https://media.istockphoto.com/id/1759448630/photo/happy-caucasian-young-student-female-looking-at-camera-enjoying-with-a-perfect-white-teeth.jpg?s=612x612&w=0&k=20&c=KbfDI3FjAdGYK5QxTx3PJdxFyx9ZNgvOBd0P7E3Ah38=" alt="Profile" /></div>
                    <div class="session-info-circle image1"><img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Profile" /></div>
                </div>
                <div class="session-info-header-left-right">
                    <div class="session-info-personal-info-profile">

                        <div class="session-info-personal-info-details">
                            <h2>Request ID #154</h2>
                            <h2>Dr. Amala Silva</h2>
                            <span class="tag accepted">On going</span>
                            <p class="consultant-rating">
                                <span class="rating-stars" id="rating-stars"></span>
                            </p>
                        </div>
                        <div class="session-info-personal-info-details">
                        <h2>Profile ID #184</h2>
                        <h2>Gunasena Thalagala</h2>
                        <span class="tag accepted">Granfather</span>
                        </div>
                    </div>
                </div>
                <div class="session-info-buttons">
                <button onclick="window.location.href='<?php echo URLROOT; ?>/consultant/consultantchat'" class="session-send-button">
                    <i class="fas fa-comments"></i> Chat
                </button>
                            <button onclick="window.location.href='<?php echo URLROOT; ?>/consultant/viewElderProfile'" class="session-send-button">
                                View Elder Profile
                            </button>
                            <button onclick="window.location.href='<?php echo URLROOT; ?>'" class="session-upload-button">
                                Upload matreials
                            </button>
                        </div>

            </div>
           

        </div>
        <div class="session-infos-section">
        
    <!-- Lab Reports -->
    <div class="document-category">
        <h3>Lab Reports</h3>
        <div class="document-list">
            <!-- Sample file entry -->
            <div class="document-item">
                <p>Blood Test Report.pdf</p>
                <div class="document-actions">
                    <a href="/path/to/lab-report-1.pdf" target="_blank" class="view-btn">View</a>
                    <!-- <a href="/path/to/lab-report-1.pdf" download class="download-btn">Upload</a> -->
                </div>
            </div>
            <div class="document-item">
                <p>Urine Analysis.pdf</p>
                <div class="document-actions">
                    <a href="/path/to/lab-report-2.pdf" target="_blank" class="view-btn">View</a>
                    <!-- <a href="/path/to/lab-report-2.pdf" download class="download-btn">Upload</a> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="document-category">
        <h3>Instructions</h3>
        <div class="document-list">
            <div class="document-item">
                <p>Medication Schedule.pdf</p>
                <div class="document-actions">
                    <a href="/path/to/instruction-1.pdf" target="_blank" class="view-btn">View</a>
                    <!-- <a href="/path/to/instruction-1.pdf" download class="download-btn">Upload</a> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions -->
    <div class="document-category">
        <h3>Prescriptions</h3>
        <div class="document-list">
            <div class="document-item">
                <p>Pain Relief Prescription.pdf</p>
                <div class="document-actions">
                    <a href="/path/to/prescription-1.pdf" target="_blank" class="view-btn">View</a>
                    <!-- <a href="/path/to/prescription-1.pdf" download class="download-btn">Upload</a> -->
                </div>
            </div>
        </div>
    </div>


   
</div>

       


    </div>

    </div>

</page-body-container>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>