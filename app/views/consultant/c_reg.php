<link rel="stylesheet" href="/public/css/style.css">
<?php require APPROOT.'/views/includes/header.php'; ?>
<body class="register">
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<div class="top-container">
<div class="main-container">
  <div class="form-container">
    <div class="form_header">
      <center><h1>Register</h1></center>
      <p>Please Fill the form below to Register as a consultant</p>
    </div>

    <form action="<?php echo URLROOT; ?>/consultant/consultantreg" method="POST" enctype="multipart/form-data">

      <!--user-name-->
      <div class="form-input-title">Name</div>
      <input type="text" name="username" placeholder="Enter your name" value="">
      <!-- php echo $data['username']; -->
      <span class="form-invalid">username_error</span>
      <!-- php echo $data['username_err'];  -->

      <!--user-email-->
      <div class="form-input-title">Email</div>
      <input type="email" name="email" id="email" class="email" placeholder="example@email.com" value="">
      <!-- php echo $data['email'];  -->
      <span class="form-invalid">email-error</span>
      <!-- php echo $data['email_err']; -->

      <!--user-name-->
      <div class="form-input-title">National ID No</div>
      <input type="text" name="national_id" placeholder="Enter your id no" value="">
      <!-- php echo $data['national_id']; -->
      <span class="form-invalid">NID error</span>
      <!-- php echo $data['national_id_err']; -->


       <!--gender-->
       <div class="form-input-title">Gender</div>
      <select name="gender">
        <option value="Male">Male</option>
        <!-- php echo ($data['gender'] == 'Male') ? 'selected' : '';  -->
        <option value="Female">Female</option>
        <!-- php echo ($data['gender'] == 'Female') ? 'selected' : '';  -->
      </select>
      <span class="form-invalid">gender_error</span>
      <!-- php echo $data['gender_err'];  -->

      <!--date-of-birth-->
      <div class="form-input-title">Date of Birth</div>
      <input type="date" name="dob" value="">
      <!-- php echo $data['dob'];  -->
      <span class="form-invalid">dob_error</span>
      <!-- php echo $data['dob_err']; -->

      <!--address-->
      <div class="form-input-title">Address</div>
      <input type="text" name="address" placeholder="Enter your address" value="">
      <!-- php echo $data['address'];  -->
      <span class="form-invalid">address_error</span>
      <!-- php echo $data['address_err'];  -->

      <!--contact-no.-->
      <div class="form-input-title">Contact No</div>
      <input type="text" name="contact_info" placeholder="Enter contact no." value="">
      <!-- php echo $data['contact_info'];  -->
      <span class="form-invalid">contact no. error</span>
      <!-- php echo $data['contact_info_err'];  -->

      <!--gender-->
      <div class="form-input-title">Your Occupation</div>
      <select name="occupation">
        <option value="Doctor">Doctor</option>
        <!-- php echo ($data['gender'] == 'Male') ? 'selected' : '';  -->
        <option value="Therapist">Therapist</option>
        <!-- php echo ($data['gender'] == 'Female') ? 'selected' : '';  -->
        <option value="Psychologist">Psychologist</option>
        <!-- php echo ($data['gender'] == 'Female') ? 'selected' : '';  -->
      </select>
      <span class="form-invalid">Occ_error</span>
      <!-- php echo $data['gender_err'];  -->

      <!--user-password-->
      <div class="form-input-title">Password</div>
      <input type="password" name="password" id="password" class="password" value="">
      <!-- php echo $data['password'];  -->
      <span class="form-invalid">pwd_error</span>
      <!-- php echo $data['password_err'];  -->

      <!--confirm-password-->
      <div class="form-input-title">Confirm Password</div>
      <input type="password" name="confirm_password" id="confirm_password" class="confirm_password" value="">
      <!-- php echo $data['confirm_password'];  -->
      <span class="form-invalid">pwd_error</span>
      <!-- php echo $data['password_err'];  -->

      <!-- Qualifications -->
      <div class="form-input-title">Please enter your medical qualifications here</div>
      <ul>
        <li><input type="text" name="qualifications" placeholder="Enter MBBs registration No." value=""></li>
        <li><input type="text" name="qualifications" placeholder="Enter qualifiacations" value=""></li>
        <li><input type="text" name="qualifications" placeholder="Enter qualifiacations" value=""></li>
      </ul>
      <!-- php echo $data['address'];  -->
      <span class="form-invalid">details_error</span>
      <!-- php echo $data['address_err'];  -->

      <!-- Specifications -->
      <div class="form-input-title">Please enter your medical speciifcations here</div>
      <ul>
        <li><input type="text" name="qualifications" placeholder="Enter specifications" value=""></li>
        <li><input type="text" name="qualifications" placeholder="Enter qualifiacations" value=""></li>
        <li><input type="text" name="qualifications" placeholder="Enter qualifiacations" value=""></li>
      </ul>
      <!-- php echo $data['address'];  -->
      <span class="form-invalid">details_error</span>
      <!-- php echo $data['address_err'];  -->

      <!-- form instructions
      <div class="form-instructions">
        <p>Please upload pdf copies of mentioned documents below for security and verification purposes. Not uploading or uploading fake or edited files will result in rejecting your application. (The uploaded document won't be shared outside the organization)</p>
        <ul>
          <li>Police Report</li>
          <li>Degree/course Certificate/Qualifications</li>
          <li>Birth Certificate</li>
          <li>A photo</li>
          <li>Experience</li>
        </ul>
      </div> -->

      <!-- upload files -->
      
      <!-- <div class="file-upload-section">
      
        <label for="documents">Upload Documents:</label>
        <input type="file" name="documents[]" id="documents" multiple accept=".pdf,.jpg,.jpeg,.png">
        <div id="file-list" class="file-list"> -->
          <!-- Selected file names will be displayed here -->



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