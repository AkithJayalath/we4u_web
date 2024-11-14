<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
</div>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

  <div class="m-container">
  <div class="search-section">
    <input type="text" placeholder="Search" />
    <div>
      <img src="notification-icon.png" class="notification-icon" />
      <img src="profile-pic.png" class="profile-pic" />
    </div>
  </div>

  <div class="table-container">
    <h2>Request Information</h2>
    <div class="table">
      <div class="table-header">
        <div class="table-cell">Request ID</div>
        <div class="table-cell">Date</div>
        <div class="table-cell">Service Provider</div>
        <div class="table-cell">Status</div>
        <div class="table-cell">Action</div>
      </div>
      <div class="table-body">

      <?php foreach($data['requests'] as $request) : ?>

        <div class="table-row">
          <div class="table-cell"><a href="#"><?php echo $request->request_id; ?></a></div>
          <div class="table-cell"><?php echo $request->request_date; ?></div>
          <div class="table-cell"><?php echo $request->careseeker_id; ?></div>
          <div class="table-cell"><?php echo $request->status; ?></div>
          <div class="table-cell">
            <button class="accept">Accept</button>
          </div>
        </div>

      <?php endforeach; ?>

        
        <!-- Your existing table rows here -->
      </div>
    </div>
  </div>


</div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>