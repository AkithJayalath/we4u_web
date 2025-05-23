<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
</div>

<?php 
      $required_styles = [
          'moderator/interview',
      ];
      echo loadCSS($required_styles);
?>

<page-body-container>
<?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

    <div class="m-container">
      <form action="<?php echo URLROOT; ?>/moderator/submitInterview" method="POST">
        <div class="m-i-main-container">
          <!-- <div class="m-i-profile-section">
            <div class="m-i-profile-image-container">
              <img class="m-i-profile-image" src="<?= isset($data['request']->profile_picture) && $data['request']->profile_picture 
              ? URLROOT . '/images/profile_imgs/' . $data['request']->profile_picture 
             : URLROOT . '/images/def_profile_pic.jpg'; ?>" />

            </div>
            <div class="m-i-profile-info">
              <span><?php echo $data['request']->username ?? 'Name'; ?></span>
              <br />
              <span><?php echo $data['request']->email ?? 'Email'; ?></span>
            </div>
          </div> -->

      
          <div class="m-i-profile-section">
      <div class="m-i-profile-image-container">
          <img class="m-i-profile-image" src="<?= isset($data['request']->profile_picture) && $data['request']->profile_picture 
              ? URLROOT . '/public/images/profile_imgs/' . $data['request']->profile_picture 
              : URLROOT . '/images/def_profile_pic.jpg'; ?>" />
      </div>
      <div class="m-i-profile-info">
          <span class="name"><?php echo $data['request']->username ?? 'Name'; ?></span>
          <span class="email">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
              </svg>
              <?php echo $data['request']->email ?? 'Email'; ?>
          </span>
      </div>
</div>


          <h2 class="m-i-request-id">Request Id: #<?php echo $data['request']->request_id ?? '1234'; ?></h2>
          <input type="hidden" name="request_id" value="<?php echo $data['request']->request_id ?? ''; ?>">

        
      <?php if($data['interview']): ?>
      <?php
          date_default_timezone_set('Asia/Colombo');
        
          // Add validation checks
          if (!empty($data['interview']->request_date) && !empty($data['interview']->interview_time)) {
              $interview_datetime = DateTime::createFromFormat('Y-m-d H:i:s', 
                  $data['interview']->request_date . ' ' . $data['interview']->interview_time);
              $current_time = new DateTime();
            
              if ($interview_datetime) {
                  $interval = $interview_datetime->diff($current_time);
                  $days_left = $interval->days;
                  $hours_left = $interval->h;
                  $is_overdue = $interview_datetime < $current_time;
              }
          }
        
          $interview_status = $data['interview']->status ?? 'pending';
      ?>

      <div class="m-i-countdown <?php echo isset($is_overdue) && $is_overdue ? 'overdue' : 'upcoming'; ?>">
          <?php if($interview_status === 'completed'): ?>
              Interview Completed
          <?php elseif(isset($days_left) && isset($hours_left)): ?>
              <?php if($is_overdue): ?>
                  Interview overdue by <?php echo abs($days_left); ?> days and <?php echo abs($hours_left); ?> hours
              <?php else: ?>
                  Time remaining: <?php echo $days_left; ?> days and <?php echo $hours_left; ?> hours
              <?php endif; ?>
          <?php endif; ?>
      </div>
<?php endif; ?>

          <div class="m-i-form-grid">
            <div class="m-i-form-left">
              <div class="m-i-input-group">
                <div class="name_detail">
                  <span>Request Date</span>
                  <input type="date" name="request_date" 
                         value="<?php echo ($data['interview'] && $data['interview']->request_date) ? $data['interview']->request_date : ''; ?>" 
                         required />
                </div>

                <div class="name_detail">
                  <span>Interview Time</span>
                  <input type="time" name="interview_time" 
                         value="<?php echo ($data['interview'] && $data['interview']->interview_time) ? $data['interview']->interview_time : ''; ?>" 
                         required />
                </div>

                <?php if(!empty($data['time-err-message'])): ?>
                <span class="error-message" style="color: red; font-size: 14px;"><?php echo $data['time-err-message']; ?></span>
                <?php endif; ?>


                <div class="name_detail">
                  <span>Service</span>
                  <input type="text" name="service" value="<?php echo $data['request']->service ?? 'Consultant'; ?>" readonly />
                </div>

                <div class="name_detail">
                  <span>Platform</span>
                  <input type="text" name="platform" 
                         value="<?php echo ($data['interview'] && $data['interview']->platform) ? $data['interview']->platform : ''; ?>" 
                         placeholder="Enter meeting platform" 
                         required />
                </div>

                <div class="name_detail">
                  <span>Meeting Link</span>
                  <input type="text" name="meeting_link" 
                         value="<?php echo ($data['interview'] && $data['interview']->meeting_link) ? $data['interview']->meeting_link : ''; ?>" 
                         placeholder="Enter meeting link" 
                         required />
                         <?php if(!empty($data['link-err-message'])): ?>
                          <span class="error-message" style="color: red; font-size: 14px;"><?php echo $data['link-err-message']; ?></span>
                          <?php endif; ?>
                </div>
              </div>
            </div>          
            <div class="m-i-form-right">
              <div class="m-i-input-group">
                <div class="name_detail">
                  <span>Service Provider Id</span>
                  <input type="text" name="provider_id" value="<?php echo $data['request']->user_id ?? '#cons233'; ?>" readonly />
                </div>

                <div class="name_detail">
                  <span>Service Provider Name</span>
                  <input type="text" name="provider_name" value="<?php echo $data['request']->username ?? 'Provider Name'; ?>" readonly />
                </div>

                <div class="name_detail">
                  <span>Service Provider Email</span>
                  <input type="email" name="provider_email" value="<?php echo $data['request']->email ?? 'provider@email.com'; ?>" readonly />
                </div>
              </div>

              <div class="m-i-button-group">
                      <button type="submit" class="m-i-submit-btn" <?php echo (($data['interview'] && $data['interview']->status == 'Done') || $data['request']->status != 'Pending' ) ? 'disabled' : ''; ?>>
                          <?php echo ($data['interview']) ? 'Update Interview' : 'Add Interview'; ?>
                      </button>

                      <?php if($data['interview']): ?>
                          <button type="button" class="m-i-delete-btn" onclick="deleteInterview(<?php echo $data['request']->request_id; ?>)">
                              Delete Interview
                          </button>
                      <?php endif; ?>

                      <button type="button" class="m-i-cancel-btn" onclick="cancelAction()">Cancel</button>
                </div>
          </div>
        </div>
        </div>
      </form>
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
  
    <!-- Delete interview confirmation modal -->
    <div id="deleteModal" class="modal">
      <div class="modal-content">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this interview? This action cannot be undone.</p>
        <div class="modal-buttons">
          <button class="modal-btn confirm-btn" id="confirmDeleteBtn">Yes, Delete</button>
          <button class="modal-btn cancel-btn" onclick="closeDeleteModal()">Cancel</button>
        </div>
      </div>
    </div>
  
    <!-- Cancel confirmation modal -->
    <div id="cancelModal" class="modal">
      <div class="modal-content">
        <h2>Confirm Cancellation</h2>
        <p>Are you sure you want to cancel? Any unsaved changes will be lost.</p>
        <div class="modal-buttons">
          <button class="modal-btn confirm-btn" id="confirmCancelBtn">Yes, Cancel</button>
          <button class="modal-btn cancel-btn" onclick="closeCancelModal()">No, Continue Editing</button>
        </div>
      </div>
    </div>
  
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>

<style>
/* Modal styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    background: white;
    width: 90%;
    max-width: 500px;
    margin: 20vh auto;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    text-align: center;
}

.modal-content h2 {
    color: #333;
    margin-bottom: 16px;
    font-size: 20px;
}

.modal-content p {
    color: #666;
    margin-bottom: 24px;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 16px;
}

.modal-btn {
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    border: none;
}

.confirm-btn {
    background-color: #f44336;
    color: white;
}

.cancel-btn {
    background-color: #e0e0e0;
    color: #333;
}

.confirm-btn:hover {
    background-color: #d32f2f;
}

.cancel-btn:hover {
    background-color: #bdbdbd;
}
</style>

<script>
      // Function to handle submit/update interview with modal
      document.querySelector('.m-i-submit-btn').addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('approveModal').style.display = 'block';
      });
    
      function proceedToApproveForm() {
          document.querySelector('form').submit();
      }
    
      function closeApproveModal() {
          document.getElementById('approveModal').style.display = 'none';
      }

      // Function to handle delete interview with modal
      function deleteInterview(requestId) {
          document.getElementById('deleteModal').style.display = 'block';
          document.getElementById('confirmDeleteBtn').onclick = function() {
              window.location.href = '<?php echo URLROOT; ?>/moderator/deleteInterview/' + requestId;
          };
      }
    
      function closeDeleteModal() {
          document.getElementById('deleteModal').style.display = 'none';
      }

      // Function to handle cancel with modal
      function cancelAction() {
          document.getElementById('cancelModal').style.display = 'block';
          document.getElementById('confirmCancelBtn').onclick = function() {
              window.location.href = '<?php echo URLROOT; ?>/moderator/careseekerrequests';
          };
      }
    
      function closeCancelModal() {
          document.getElementById('cancelModal').style.display = 'none';
      }
    
      // Functions for the rejection modal (keeping these for consistency)
      function closeModal() {
          document.getElementById('rejectModal').style.display = 'none';
      }
    
      function proceedToRejectForm() {
          const requestId = <?php echo $data['request']->request_id ?? 0; ?>;
          window.location.href = '<?php echo URLROOT; ?>/moderator/rejectform/' + requestId;
      }
</script>
