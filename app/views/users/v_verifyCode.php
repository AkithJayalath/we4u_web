<?php require APPROOT.'/views/includes/header.php'; ?>
<body class="login">

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<div class="top-container">
    <div class="main-container">
        <div class="form-container">
            <div class="form_header">
                <center><h1>Verify Reset Code</h1></center>
                <p><b>Please enter the 5-digit code sent to your email.</b></p>
            </div>

            <form action="<?php echo URLROOT; ?>/users/verifyResetCode" method="POST">
                
                <input type="hidden" name="email" value="<?php echo $data['email']; ?>">
                
                
                <div class="form-input-title">Reset Code</div>
                <input type="text" name="code" id="code" class="code" value="<?php echo $data['code']; ?>" maxlength="5">
                <span class="form-invalid"><?php echo $data['code_err'] ?></span>
                
                
                <br>
                <input type="submit" value="Verify Code" class="reg-form-btn">
            </form>
            <div class="form-footer">
                <p>Remember your password? <a href="<?php echo URLROOT; ?>/users/login">Login</a></p>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT.'/views/includes/footer.php'?>