<?php 
    $required_styles = [
        'moderator/request_rejectionform',
        // 'components/sidebar',
        
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>


<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

    <!-- view-caregiver-details.php -->
    <div class="reject-form-container">
        <div class="reject-header">
            <h2>Rejection Form</h2>
            <div class="application-details">
                <p>Application ID: <?php echo $data['request']->request_id; ?></p>
                <p>Applicant: <?php echo $data['request']->username; ?></p>
                <p>Email: <?php echo $data['request']->email; ?></p>
                <p>Type: <?php echo $data['request']->request_type; ?></p>
            </div>
        </div>
        
        <form method="POST" action="<?php echo URLROOT; ?>/moderator/rejectRequest" onsubmit="showSuccessModal(); return false;">
            <input type="hidden" name="request_id" value="<?php echo $data['request']->request_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $data['request']->user_id; ?>">
            <input type="hidden" name="role" value="<?php echo $data['request']->role; ?>">
            <div class="form-group">
                <label>Rejection Reason</label>
                <textarea name="comment" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="submit-btn">Submit Rejection</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='<?php echo URLROOT; ?>/moderator/careseekerrequests'">Cancel</button>
            </div>
        </form>
    </div>
    
    <!-- Add this success modal -->
    <div id="successModal" class="reject-modal">
        <div class="reject-modal-content">
            <h2>Operation Successful</h2>
            <p>Request has been rejected successfully</p>
            <div class="reject-modal-buttons">

            <button class="reject-modal-btn reject-ok-btn" onclick="redirectToRequests()">OK</button>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
      
        fetch('<?php echo URLROOT; ?>/moderator/rejectRequest', {
            method: 'POST',
            body: formData
        })
          .then(response => response.json())
          .then(data => {
              if(data.success) {
                  showSuccessModal();
              }
          });
      });

    function showSuccessModal() {
        document.getElementById('successModal').style.display = 'block';
    }

    function redirectToRequests() {
        window.location.href = '<?php echo URLROOT; ?>/moderator/careseekerrequests';
    }
    </script>



</page-body-container>
<?php require APPROOT.'/views/includes/footer.php'?>


