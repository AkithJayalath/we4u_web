<?php require APPROOT.'/views/includes/components/header.php'; ?>


  <div class="form-container">
    <div class="form_header">
    <center><h1>User Login</h1></center>
    <p><b>Please Fill the form to login.</b></p>
    </div>

    <form action="" method="POST" >

      <!--email -->
      <div class="form-input-title">Email</div>
      <input type="email" name="email" id="email" class="email" value="<?php echo $data['email']; ?>">
      <span class="form-invalid" ><?php echo $data['email_err'] ?></span>

      <!--password -->
      <div class="form-input-title">Password</div>
      <input type="password" name="password" id="password" class="password" <?php echo $data['password']; ?>>
      <span class="form-invalid" ><?php echo $data['password_err'] ?></span> 
      
      <!--submit -->
      <br>
      <input type="submit" value="Login" class="reg-form-btn">

    </form>
  </div>

<?php require APPROOT.'/views/includes/components/footer.php'?>

