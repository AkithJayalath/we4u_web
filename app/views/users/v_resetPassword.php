<?php require APPROOT.'/views/includes/header.php'; ?>
<body class="login">
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<div class="top-container">
    <div class="main-container">
        <div class="form-container">
            <div class="form_header">
                <center><h1>Reset Your Password</h1></center>
                <p><b>Please enter your new password.</b></p>
            </div>

            <form action="<?php echo URLROOT; ?>/users/resetPassword" method="POST">
                <!-- New password -->
                <div class="form-input-title">New Password</div>
                <input type="password" name="password" id="password" class="password">
                <span class="form-invalid"><?php echo $data['password_err'] ?></span>
                
                <!-- Confirm password -->
                <div class="form-input-title">Confirm Password</div>
                <input type="password" name="confirm_password" id="confirm_password" class="password">
                <span class="form-invalid"><?php echo $data['confirm_password_err'] ?></span>
                
                <!-- Submit -->
                <br>
                <input type="submit" value="Reset Password" class="reg-form-btn">
            </form>
        </div>
    </div>
</div>

<?php require APPROOT.'/views/includes/footer.php'?>