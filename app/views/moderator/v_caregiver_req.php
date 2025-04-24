<?php 
    $required_styles = [
        'moderator/viewrequest',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

    <div class="dashboard-container">
        <!-- Dashboard-style header -->
        <header class="dashboard-header">
            <h1>Caregiver Application Details</h1>
            <div class="request-status">
                <span class="status-badge <?php echo strtolower($data['request']->status); ?>">
                    <?php echo $data['request']->status; ?>
                </span>
                <span class="request-id">ID: <?php echo $data['request']->request_id; ?></span>
            </div>
        </header>

        <!-- Main content area -->
        <div class="request-content">
            <!-- Personal Information Section -->
            <div class="m-v-r-info-section">
                <div class="m-v-r-section-header">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>
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
                        <span class="m-v-r-value"><?php echo $data['caregiver']->contact_info; ?></span>
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
                        <span class="m-v-r-label">Years Of Experience</span>
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
                    <h3><i class="fas fa-file-alt"></i> Submitted Documents</h3>
                </div>
                <div class="m-v-r-documents-grid">
                        <div class="m-v-r-doc-card">
                            <div class="m-v-r-doc-icon">
                                <i class="fas fa-file-archive"></i>
                            </div>
                            <div class="m-v-r-doc-info">
                                <span class="m-v-r-doc-title">All Documents</span>
                                <div class="m-v-r-doc-actions">
                                    <button class="m-v-r-download-btn" onclick="downloadAllDocuments()">
                                        <i class="fas fa-download"></i> Download All
                                    </button>
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

    <!-- approval modal popup -->
    <div id="approveModal" class="m-v-r-modal">
        <div class="m-v-r-modal-content">
            <h2>Confirm Approval</h2>
            <p>Are you sure you want to approve this application?</p>
            <div class="m-v-r-modal-buttons">
                <button class="m-v-r-modal-btn m-v-r-confirm-btn" onclick="proceedToApproveForm()">Yes, Proceed</button>
                <button class="m-v-r-modal-btn m-v-r-cancel-btn" onclick="closeApproveModal()">Cancel</button>
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
        const requestId = <?php echo $data['request']->request_id; ?>;
        window.location.href = '<?php echo URLROOT; ?>/moderator/rejectform/' + requestId;
    }

    function approveApplication(requestId) {
        document.getElementById('approveModal').style.display = 'block';
    }

    function closeApproveModal() {
        document.getElementById('approveModal').style.display = 'none';
    }

    function proceedToApproveForm() {
        const requestId = <?php echo $data['request']->request_id; ?>;
        window.location.href = '<?php echo URLROOT; ?>/moderator/approve/' + requestId;
    }

    // Function to download a single document
    function downloadDocument(filePath, documentId) {
        const baseUrl = '<?php echo URLROOT; ?>';
        const fullPath = `${baseUrl}/public/${filePath}`;
        
        // Create a temporary anchor element
        const downloadLink = document.createElement('a');
        downloadLink.href = fullPath;
        
        // Set the download attribute with the document ID as part of the filename
        const fileExtension = filePath.split('.').pop();
        downloadLink.download = `document_${documentId}.${fileExtension}`;
        
        // Append to the body, click it, and remove it
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    // Function to download all documents
    function downloadAllDocuments() {
        // Get all document paths and IDs
        const documents = [
            <?php if(!empty($data['documents'])): foreach($data['documents'] as $document): ?>
                {
                    path: '<?php echo $document->file_path; ?>',
                    id: '<?php echo $document->document_id; ?>'
                },
            <?php endforeach; endif; ?>
        ];
        
        // Download each document with a small delay to prevent browser blocking
        documents.forEach((doc, index) => {
            setTimeout(() => {
                downloadDocument(doc.path, doc.id);
            }, index * 500); // 500ms delay between downloads
        });
    }
    </script>

</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
