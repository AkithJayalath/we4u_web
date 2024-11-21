<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
</div>

<page-body-container>
<?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

  <div class="m-container">



    <form action="<?php echo URLROOT; ?>/moderator/submitInterview" method="POST">
      <div class="m-i-main-container">
        <div class="m-i-profile-section">
          <div class="m-i-profile-image-container">
            <img class="m-i-profile-image" src="/we4u/public/images/image1.jpg" />
          </div>
          <div class="m-i-profile-info">
            <span><?php echo $data['request']->username ?? 'Name'; ?></span>
            <br />
            <span><?php echo $data['request']->email ?? 'Email'; ?></span>
          </div>
        </div>

        <h2 class="m-i-request-id">Request Id: #<?php echo $data['request']->request_id ?? '1234'; ?></h2>
        <input type="hidden" name="request_id" value="<?php echo $data['request']->request_id ?? ''; ?>">

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
              <button type="submit" class="m-i-submit-btn">
                  <?php echo ($data['interview']) ? 'Update Interview' : 'Add Interview'; ?>
              </button>
              <button type="button" class="m-i-cancel-btn" onclick="window.location.href='<?php echo URLROOT; ?>/moderator/requests'">Cancel</button>
          </div>
        </div>
      </div>
      </div>
    </form>
  </div>
  
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
