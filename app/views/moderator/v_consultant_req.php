<?php 
    $required_styles = [
        'moderator/viewrequest',
        // 'components/sidebar',
        
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>


<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

    <!-- m-v-r means moderator individual details -->
    <div class="m-v-r-main-container">
        <div class="m-v-r-profile-header">
            <div class="m-v-r-header-content">
                <h2>Consultant Application Details</h2>
                <span class="m-v-r-application-id">ID: <?php echo $data['request']->request_id; ?></span>
            </div>
            <div class="m-v-r-status">
                <span class="m-v-r-status-badge"><?php echo $data['request']->status; ?></span>
            </div>
        </div>

        <div class="m-v-r-content-wrapper">
            <!-- Personal Information Section -->
            <div class="m-v-r-info-section">
                <div class="m-v-r-section-header">
                    <i class="fas fa-user"></i>
                    <h3>Personal Information</h3>
                </div>
                <div class="m-v-r-info-grid">
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">Full Name</span>
                        <span class="m-v-r-value"><?php echo $data['request']->username; ?></span>
                    </div>
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">Email Address</span>
                        <span class="m-v-r-value"><?php echo $data['request']->email; ?></span>
                    </div>
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">Contact Number</span>
                        <span class="m-v-r-value"><?php echo $data['consultant']->contact_info; ?></span>
                    </div>
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">NIC</span>
                        <span class="m-v-r-value"><?php echo $data['caregiver']->national_id; ?></span>
                    </div>
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">Address</span>
                        <span class="m-v-r-value"><?php echo $data['caregiver']->address; ?></span>
                    </div>
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">Care Giving Type</span>
                        <span class="m-v-r-value"><?php echo $data['caregiver']->caregiver_type; ?></span>
                    </div>
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">Years Of Experiance</span>
                        <span class="m-v-r-value"><?php echo $data['caregiver']->years_of_experience; ?></span>
                    </div>
                    <div class="m-v-r-info-item">
                        <span class="m-v-r-label">Special Skills</span>
                        <span class="m-v-r-value"><?php echo $data['caregiver']->skills; ?></span>
                    </div>

                </div>
            </div>
            <!-- Documents Section -->
            <div class="m-v-r-documents-section">
                <div class="m-v-r-section-header">
                    <i class="fas fa-file-alt"></i>
                    <h3>Submitted Documents</h3>
                </div>
                <div class="m-v-r-documents-grid">
                    <div class="m-v-r-doc-card">
                        <div class="m-v-r-doc-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="m-v-r-doc-info">
                            <span class="m-v-r-doc-title">Police Report</span>
                            <div class="m-v-r-doc-actions">
                                <button class="m-v-r-view-btn" >
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="m-v-r-download-btn" >
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="m-v-r-doc-card">
                        <div class="m-v-r-doc-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="m-v-r-doc-info">
                            <span class="m-v-r-doc-title">Qualifications</span>
                            <div class="m-v-r-doc-actions">
                                <!-- <button class="m-v-r-view-btn" onclick="viewDocument('<?php echo $caregiver->qualifications; ?>')">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="m-v-r-download-btn" onclick="downloadDocument('<?php echo $caregiver->qualifications; ?>')">
                                    <i class="fas fa-download"></i>
                                </button> -->

                                <button class="m-v-r-view-btn" >
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="m-v-r-download-btn">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="m-v-r-doc-card">
                        <div class="m-v-r-doc-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="m-v-r-doc-info">
                            <span class="m-v-r-doc-title">Birth Certificate</span>
                            <div class="m-v-r-doc-actions">
                                <button class="m-v-r-view-btn" >
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="m-v-r-download-btn" >
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="m-v-r-doc-card">
                        <div class="m-v-r-doc-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="m-v-r-doc-info">
                            <span class="m-v-r-doc-title">Photo</span>
                            <div class="m-v-r-doc-actions">
                                <button class="m-v-r-view-btn" >
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="m-v-r-download-btn">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="m-v-r-doc-card">
                        <div class="m-v-r-doc-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="m-v-r-doc-info">
                            <span class="m-v-r-doc-title">NIC</span>
                            <div class="m-v-r-doc-actions">
                                <button class="m-v-r-view-btn" >
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="m-v-r-download-btn">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="m-v-r-action-panel">
            <button class="m-v-r-reject-btn" onclick="rejectApplication(<?php echo $data['request']->request_id; ?>)">
                <i class="fas fa-times"></i> Reject
            </button>

            <button class="m-v-r-interview-btn" onclick="window.location.href='<?php echo URLROOT; ?>/moderator/interview/<?php echo $data['request']->request_id; ?>'">
            <i class="fas fa-calendar-alt"></i> Schedule Interview
            </button>

            <button class="m-v-r-approve-btn" onclick="approveApplication(<?php echo $data['request']->request_id; ?>)">
                <i class="fas fa-check"></i> Approve
            </button>
        </div>
    </div>

    <!-- rejection model popup -->
    <div id="rejectModal" class="m-v-r-modal">
        <div class="m-v-r-modal-content">
            <h2>Confirm Rejection</h2>
            <p>Are you sure you want to reject this application?</p>
            <div class="m-v-r-modal-buttons">
                <button class="m-v-r-modal-btn m-v-r-confirm-btn" onclick="proceedToRejectForm()">Yes, Proceed</button>
                <button class="m-v-r-modal-btn m-v-r-cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
    function rejectApplication(requestId) {
        document.getElementById('rejectModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    function proceedToRejectForm() {
        const requestId = document.querySelector('.m-v-r-application-id').textContent.split(':')[1].trim();
        window.location.href = '<?php echo URLROOT; ?>/moderator/rejectform/' + requestId;
    }
    </script>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>


