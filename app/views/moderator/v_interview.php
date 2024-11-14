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
        <div class="interview-profile-container-image">
          <img src="/we4u/public/images/image1.jpg" />
        </div>
        <div class="interview-name-container">
          <span>Tanuri</span>
          <br />
          <span>hello@gmail.com</span>
        </div>
      </div>

      <h2>Requsest Id 1234</h2>

      <div class="interview-details-container">
        <div class="interview-details-left">
          <!-- date  -->
          <div class="name_detail">
            <span>Request Date</span>
            <input type="date" value="Date" />
          </div>

          <!-- time  -->
          <div class="name_detail">
            <span>Interview Time</span>
            <input type="time" value="Date" />
          </div>

          <!-- service  -->
          <div class="name_detail">
            <span>Service</span>
            <input disabled type="text" value="Consultant" />
          </div>

          <!-- platform -->
          <div class="name_detail">
            <span>Platform</span>
            <input type="text" value="Zoom-link" />
          </div>

          <!-- View Request -->
          <div class="name_detail">
            <span>View Request</span>
            <button>
              <input disabled type="text" value="#1234" />
            </button>
          </div>
        </div>

        <div class="interview-details-right">
          <!-- service-provider-id  -->
          <div class="name_detail">
            <span>Service Provider Id</span>
            <input disabled type="text" value="#cons233" />
          </div>

          <!-- Service Provider Name  -->
          <div class="name_detail">
            <span>Service Provider Name</span>
            <input disabled type="text" value="#cons233" />
          </div>

          <!-- Service-Provider-Email  -->
          <div class="name_detail">
            <span>Service Provider Email</span>
            <input disabled type="email" value="nadun@gmail.com" />
          </div>

          <!-- submit-button  -->
          <div class="moderator-interview-submit-btn-div">
            <button class="moderator-interview-submit-btn">Submit</button>
            <button class="moderator-interview-cansel-btn">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>

