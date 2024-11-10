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

    <!-- Header -->
    <div><h2>Interview Details</h2></div>

    <!-- contant  -->
    <div class="main-interview-container">
      <div class="name-and-profile-pic-container">
        
      </div>
    </div>



  </div>

</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>