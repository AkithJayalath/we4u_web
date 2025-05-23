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

<!-- view-caregiver-details.php -->
<div class="m-i-d-main-container">
    <div class="m-i-d-profile-header">
        <div class="m-i-d-header-content">
            <h2>Caregiver Application Details</h2>
            <span class="m-i-d-application-id">ID: <?php echo $data['request']->request_id; ?></span>
        </div>
        <div class="m-i-d-status">
            <span class="m-i-d-status-badge">Pending Review</span>
        </div>
    </div>

    <div class="m-i-d-content-wrapper">
        <!-- Personal Information Section -->
        <div class="m-i-d-info-section">
            <div class="m-i-d-section-header">
                <i class="fas fa-user"></i>
                <h3>Personal Information</h3>
            </div>
            <div class="m-i-d-info-grid">
                <div class="m-i-d-info-item">
                    <span class="m-i-d-label">Name</span>
                    <span class="m-i-d-value"><?php echo $data['request']->username; ?></span>
                </div>
                <div class="m-i-d-info-item">
                    <span class="m-i-d-label">Email</span>
                    <span class="m-i-d-value"><?php echo $data['request']->email; ?></span>
                </div>
                <div class="m-i-d-info-item">
                    <span class="m-i-d-label">Contact</span>
                    <span class="m-i-d-value"><?php echo $data['request']->request_id; ?></span>
                </div>
                <div class="m-i-d-info-item">
                    <span class="m-i-d-label">Address</span>
                    <span class="m-i-d-value"><?php echo $data['request']->request_id; ?></span>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="m-i-d-documents-section">
            <div class="m-i-d-section-header">
                <i class="fas fa-file-alt"></i>
                <h3>Submitted Documents</h3>
            </div>
            <div class="m-i-d-documents-grid">
                <div class="m-i-d-doc-card">
                    <div class="m-i-d-doc-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="m-i-d-doc-info">
                        <span class="m-i-d-doc-title">Police Report</span>
                        <div class="m-i-d-doc-actions">
                            <button class="m-i-d-view-btn" onclick="viewDocument('<?php echo $caregiver->police_report; ?>')">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="m-i-d-download-btn" onclick="downloadDocument('<?php echo $caregiver->police_report; ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="m-i-d-doc-card">
                    <div class="m-i-d-doc-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="m-i-d-doc-info">
                        <span class="m-i-d-doc-title">Qualifications</span>
                        <div class="m-i-d-doc-actions">
                            <button class="m-i-d-view-btn" onclick="viewDocument('<?php echo $caregiver->qualifications; ?>')">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="m-i-d-download-btn" onclick="downloadDocument('<?php echo $caregiver->qualifications; ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="m-i-d-doc-card">
                    <div class="m-i-d-doc-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="m-i-d-doc-info">
                        <span class="m-i-d-doc-title">Birth Certificate</span>
                        <div class="m-i-d-doc-actions">
                            <button class="m-i-d-view-btn" onclick="viewDocument('<?php echo $caregiver->birth_certificate; ?>')">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="m-i-d-download-btn" onclick="downloadDocument('<?php echo $caregiver->birth_certificate; ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="m-i-d-doc-card">
                    <div class="m-i-d-doc-icon">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="m-i-d-doc-info">
                        <span class="m-i-d-doc-title">Photo</span>
                        <div class="m-i-d-doc-actions">
                            <button class="m-i-d-view-btn" onclick="viewDocument('<?php echo $caregiver->photo; ?>')">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="m-i-d-download-btn" onclick="downloadDocument('<?php echo $caregiver->photo; ?>')">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="m-i-d-action-panel">
        <button class="m-i-d-reject-btn" onclick="rejectApplication(<?php echo $data['request']->request_id; ?>)">
            <i class="fas fa-times"></i> Reject
        </button>

        <button class="m-i-d-interview-btn" onclick="window.location.href='<?php echo URLROOT; ?>/moderator/schedule/<?php echo $data['request']->request_id; ?>'">
        <i class="fas fa-calendar-alt"></i> Schedule Interview
        </button>

        <button class="m-i-d-approve-btn" onclick="approveApplication(<?php echo $data['request']->request_id; ?>)">
            <i class="fas fa-check"></i> Approve
        </button>
    </div>
</div>

</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>



<script>
function rejectApplication(requestId) {
    const comment = prompt("Are you sure you want to reject this application?\nPlease provide a reason for rejection:");
    
    if (comment !== null) {
        // Create form data
        const formData = new FormData();
        formData.append('request_id', requestId);
        formData.append('comment', comment);
        
        // Send POST request
        fetch('<?php echo URLROOT; ?>/moderator/rejectRequest', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                window.location.href = '<?php echo URLROOT; ?>/moderator/requests';
            }
        });
    }
}
</script>
