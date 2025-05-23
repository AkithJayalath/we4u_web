<?php require APPROOT.'/views/includes/header.php'; ?>
<body class="login">
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

    <div class="top-container">
    <div class="main-container">
    <div class="form-container">
    <div class="form_header">
    <center><h1>User Sign in</h1></center>
    <p><b>Please Fill the form to Sign in.</b></p>
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
<div class="forgot-password">
  <a href="#" id="forgotPasswordLink">Forgot Password?</a>
</div>
      
      <!--submit -->
      <br>
      <input type="submit" value="Login" class="reg-form-btn">

    </form>
    <div class="form-footer">
      <p>Not Registered? <a href="<?php echo URLROOT; ?>/home/">Register</a></p>
    </div>
  </div>

  </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Forgot Your Password?</h2>
    <p>Enter your email address and we'll send you a code to reset your password</p>
    <form id="forgotPasswordForm" action="<?php echo URLROOT; ?>/users/sendResetCode" method="POST">
      <div class="form-input-title">Email</div>
      <input type="email" name="email" id="resetEmail" required>
      <div id="resetEmailError" class="form-invalid"></div>
      <input type="submit" value="Send Reset Code" class="reg-form-btn">
    </form>
  </div>
</div>

<style>
  /* Modal Styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 500px;
  border-radius: 5px;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
}

.forgot-password {
  text-align: right;
  margin-top: 5px;
  margin-bottom: 15px;
  font-size: 0.9em;
}
  </style>
<script>
// Get the modal
const modal = document.getElementById("forgotPasswordModal");
// Get the button that opens the modal
const btn = document.getElementById("forgotPasswordLink");
// Get the <span> element that closes the modal
const span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function(e) {
  e.preventDefault();
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

<?php require APPROOT.'/views/includes/footer.php'?>

