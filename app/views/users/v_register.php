<?php require APPROOT.'/views/includes/components/header.php'; ?>



  <div class="form-container">
    <div class="form_header">
      <center><h1>Register</h1></center>
      <p>Please Fill the form below to Register</p>
    </div>

    <form action="<?php echo URLROOT; ?>/users/register" method="POST">

      <!--user-name-->
      <div class="form-input-title">Name</div>
      <input type="text" name="name" placeholder="Enter your name" value="<?php echo $data['name']; ?>">
      <span class="form-invalid"><?php echo $data['name_err']; ?></span>

    <!--user-email-->
      <div class="form-input-title">Email</div>
      <input type="email" name="email" id="email" class="email" value="<?php echo $data['email']; ?>">
      <span class="form-invalid"><?php echo $data['email_err']; ?></span>

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


<?php require APPROOT.'/views/includes/components/footer.php'; ?>



