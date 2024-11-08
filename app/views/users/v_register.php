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

    <form action="<?php echo URLROOT; ?>/users/register" method="POST">

      <!--user-name-->
      <div class="form-input-title">Name</div>
      <input type="text" name="username" placeholder="Enter your name" value="<?php echo $data['username']; ?>">
      <span class="form-invalid"><?php echo $data['username_err']; ?></span>

      <!--user-email-->
      <div class="form-input-title">Email</div>
      <input type="email" name="email" id="email" class="email" value="<?php echo $data['email']; ?>">
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

      <!--user-password-->
      <div class="form-input-title">Password</div>
      <input type="password" name="password" id="password" class="password" value="<?php echo $data['password']; ?>">
      <span class="form-invalid"><?php echo $data['password_err']; ?></span>

      <!--confirm-password-->
      <div class="form-input-title">Confirm Password</div>
      <input type="password" name="confirm_password" id="confirm_password" class="confirm_password" value="<?php echo $data['confirm_password']; ?>">
      <span class="form-invalid"><?php echo $data['password_err']; ?></span> 

      <!--submit -->
      <br>
      <input type="submit" value="Register" class="reg-form-btn">

    </form>
  </div>
</div>
</div>


<?php require APPROOT.'/views/includes/footer.php'; ?>
