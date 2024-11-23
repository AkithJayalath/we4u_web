<?php 
    $required_styles = [
        'moderator/interviews',
        // 'components/sidebar',
        
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>


<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="m-i-container">
      <div class="m-i-search-section">
        <input type="text" placeholder="Search By UserName Or ID" />
        <button>Search</button>
      </div>

      <div class="m-i-table-container">
        <h2>Interview Details</h2>
        <div class="m-i-table">
          <div class="m-i-table-header">
            <div class="m-i-table-cell">Interview ID</div>
            <div class="m-i-table-cell">Status</div>
            <div class="m-i-table-cell">Date</div>
            <div class="m-i-table-cell">Time</div>
            <div class="m-i-table-cell">User Id</div>
            <div class="m-i-table-cell">Action</div>
          </div>
          <div class="m-i-table-body">

          <?php foreach($data['interviews'] as $interview) : ?>

            <div class="m-i-table-row">
              <div class="m-i-table-cell"><?php echo $interview->interview_id; ?></div>

              <div class="m-i-table-cell status-<?php echo ($interview->status); ?>"><?php echo $interview->status; ?></div>

              <div class="m-i-table-cell"><?php echo date_format(date_create($interview->request_date), 'j M Y'); ?></div>
              <div class="m-i-table-cell"><?php echo date_format(date_create($interview->interview_time), 'h:i A'); ?></div>
              <div class="m-i-table-cell"><?php echo $interview->provider_id; ?></div>
              <div class="m-i-table-cell">
              <button class="m-i-view-req-action-btn" onclick="window.location.href='<?php echo URLROOT; ?>/moderator/interview/<?php echo $interview->request_id; ?>'">Details</button>
              </div>
            </div>
      
          <?php endforeach; ?>

          </div>
        </div>
      </div>

    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>