<?php 
    $required_styles = [
        'moderator/careseekerrequests',
        'moderator/requests',
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
      <h1>Consultant Application Details</h1>
      <div class="search-wrapper">
        <span class="status-badge <?php echo strtolower($data['request']->status); ?>">
            <?php echo $data['request']->status; ?>
        </span>
        <span class="request-id">ID: <?php echo $data['request']->request_id; ?></span>
      </div>
    </header>

    <!-- Main content area -->
    <div class="request-content">
      <!-- Personal Information Section -->
      <div class="request-table-container">
        <h2>Personal Information</h2>
        <div class="request-info-grid">
          <div class="request-info-card">
            <div class="info-label">Full Name</div>
            <div class="info-value"><?php echo $data['request']->username; ?></div>
          </div>
          <div class="request-info-card">
            <div class="info-label">Email Address</div>
            <div class="info-value"><?php echo $data['request']->email; ?></div>
          </div>
          <div class="request-info-card">
            <div class="info-label">Contact Number</div>
            <div class="info-value"><?php echo $data['consultant']->contact_info; ?></div>
          </div>
          <div class="request-info-card">
            <div class="info-label">NIC</div>
            <div class="info-value"><?php echo $data['consultant']->nic_no; ?></div>
          </div>
          <div class="request-info-card">
            <div class="info-label">Expertise in Field</div>
            <div class="info-value"><?php echo $data['consultant']->expertise; ?></div>
          </div>
          <div class="request-info-card">
            <div class="info-label">SLMC Registration Number</div>
            <div class="info-value"><?php echo $data['consultant']->slmc_reg_no; ?></div>
          </div>
        </div>
      </div>
      
      <!-- Documents Section -->
      <div class="request-table-container">
        <h2>Submitted Documents</h2>
        <div class="documents-grid">
          <?php if(!empty($data['documents'])): foreach($data['documents'] as $document): ?>
          <div class="document-card">
            <div class="document-icon">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="document-info">
              <div class="document-title"><?php echo $document->document_type ?? 'Document'; ?></div>
              <button class="request-action-btn" onclick="downloadDocument('<?php echo $document->file_path; ?>', <?php echo $document->document_id; ?>)">
                <i class="fas fa-download"></i> Download
              </button>
            </div>
          </div>
          <?php endforeach; else: ?>
          <div class="no-documents">
            <p>No documents have been submitted.</p>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="action-buttons">
        <button class="reject-btn" onclick="rejectApplication(<?php echo $data['request']->request_id; ?>)" 
          <?php echo (strtolower($data['request']->status) !== 'pending') ? 'disabled' : ''; ?>>
          <i class="fas fa-times"></i> Reject
        </button>

        <button class="interview-btn" onclick="window.location.href='<?php echo URLROOT; ?>/moderator/interview/<?php echo $data['request']->request_id; ?>'"
          <?php echo (strtolower($data['request']->status) !== 'pending') ? 'disabled' : ''; ?>>
          <i class="fas fa-calendar-alt"></i> Schedule Interview
        </button>
        
        <button class="approve-btn" onclick="approveApplication(<?php echo $data['request']->request_id; ?>)"
          <?php echo (strtolower($data['request']->status) !== 'pending') ? 'disabled' : ''; ?>>
          <i class="fas fa-check"></i> Approve
        </button>
      </div>
    </div>
  </div>

  <!-- rejection model popup -->
  <div id="rejectModal" class="modal">
    <div class="modal-content">
      <h2>Confirm Rejection</h2>
      <p>Are you sure you want to reject this application?</p>
      <div class="modal-buttons">
        <button class="modal-btn confirm-btn" onclick="proceedToRejectForm()">Yes, Proceed</button>
        <button class="modal-btn cancel-btn" onclick="closeModal()">Cancel</button>
      </div>
    </div>
  </div>

  <!-- approval modal popup -->
  <div id="approveModal" class="modal">
    <div class="modal-content">
      <h2>Confirm Approval</h2>
      <p>Are you sure you want to approve this application?</p>
      <div class="modal-buttons">
        <button class="modal-btn confirm-btn" onclick="proceedToApproveForm()">Yes, Proceed</button>
        <button class="modal-btn cancel-btn" onclick="closeApproveModal()">Cancel</button>
      </div>
    </div>
  </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>

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

</script>
