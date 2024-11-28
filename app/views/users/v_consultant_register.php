<?php require APPROOT.'/views/includes/header.php'; ?>
<body class="register">
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<div class="top-container">
<div class="main-container">
  <div class="form-container">
    <div class="form_header">
      <center><h1>Register</h1></center>
      <p>Please Fill the form below to Register</p>
    </div>

    <form action="<?php echo URLROOT; ?>/consultants/register" method="POST" enctype="multipart/form-data">

      <!--user-name-->
      <div class="form-input-title">Name</div>
      <input type="text" name="username" placeholder="Enter your name" value="<?php echo $data['username']; ?>">
      <span class="form-invalid"><?php echo $data['username_err']; ?></span>

      <!--user-email-->
      <div class="form-input-title">Email</div>
      <input type="email" name="email" id="email" class="email" placeholder="example@email.com" value="<?php echo $data['email']; ?>">
      <span class="form-invalid"><?php echo $data['email_err']; ?></span>

     

       <!--gender-->
       <div class="form-input-title">Gender</div>
      <select name="gender">
        <option value="Male" <?php echo ($data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
      </select>
      <span class="form-invalid"><?php echo $data['gender_err']; ?></span>

      <!--date-of-birth-->
      <div class="form-input-title">Date of Birth</div>
      <input type="date" name="dob" value="<?php echo $data['dob']; ?>">
      <span class="form-invalid"><?php echo $data['dob_err']; ?></span>

      
      <!--user-name-->
      <div class="form-input-title">Contact No</div>
      <input type="text" name="contact_info" placeholder="Enter valide contact" value="<?php echo $data['contact_info']; ?>">
      <span class="form-invalid"><?php echo $data['contact_info_err']; ?></span>

      <div class="form-input-title">SLMC Registration No</div>
      <input type="text" name="slmc_no" placeholder="Enter your SLMC registration no" value="<?php echo $data['slmc_no']; ?>">
      <span class="form-invalid"><?php echo $data['slmc_no_err']; ?></span>

      <div class="form-input-title">Category/Specialization</div>
      <input type="text" name="specialization" placeholder="As per SLMC" value="<?php echo $data['specialization']; ?>">
      <span class="form-invalid"><?php echo $data['specialization_err']; ?></span> 


      <!--user-password-->
      <div class="form-input-title">Password</div>
      <input type="password" name="password" id="password" class="password" value="<?php echo $data['password']; ?>">
      <span class="form-invalid"><?php echo $data['password_err']; ?></span>

      <!--confirm-password-->
      <div class="form-input-title">Confirm Password</div>
      <input type="password" name="confirm_password" id="confirm_password" class="confirm_password" value="<?php echo $data['confirm_password']; ?>">
      <span class="form-invalid"><?php echo $data['password_err']; ?></span> 

      <!-- form instructions -->
      <div class="form-instructions">
        <p>Please upload pdf copies of mentioned documents below for security and verification purposes. Not uploading or uploading fake or edited files will result in rejecting your application. (The uploaded document won't be shared outside the organization)</p>
        <ul>
          <li>Degree/course Certificate/Qualifications</li>
          <li>A photo</li>
          <li>Experience</li>
        </ul>
      </div>

      <!-- upload files -->
      
      <div class="file-upload-section">
      
        <label for="documents">Upload Documents:</label>
        <input type="file" name="documents[]" id="documents" multiple accept=".pdf,.jpg,.jpeg,.png">
        <div id="file-list" class="file-list">
          <!-- Selected file names will be displayed here -->
          <span class="form-invalid"><?php echo $data['documents_err']; ?></span> 
        </div>
      </div>


      <!--submit -->
      <br>
      <input type="submit" value="Register" class="reg-form-btn">

    </form>
  </div>
</div>
</div>

<script src="<?php echo URLROOT; ?>/js/approvalFileUpload.js"></script>
<?php require APPROOT.'/views/includes/footer.php'; ?>
