<?php require APPROOT.'/views/includes/header.php'; ?>
<body>
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<div class="top-container">
    <div class="container">
   
        <div class="gradient-bar"> </div> <!-- Gradient bar behind the profile section -->
        
        <header>
            <h2>Edit Profile</h2>
            
        </header>

        <div class="profile-section">
            <img src="https://via.placeholder.com/100" alt="Profile Picture" class="profile-pic">        
            </div>
        <form action="<?php echo URLROOT; ?>/users/editProfile" method="POST" class="profile-form">
        <div class="form-left">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo $data['username']; ?>">
        <span class="form-invalid"><?php echo $data['username_err']; ?></span>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $data['email']; ?>">
        <span class="form-invalid"><?php echo $data['email_err']; ?></span>

        <label>Date of Birth</label>
        <input type="date" name="date_of_birth" value="<?php echo $data['date_of_birth']; ?>">
        <span class="form-invalid"><?php echo $data['dob_err']; ?></span>

        <label>Password</label>
        <input type="password" name="password">
        <span class="form-invalid"><?php echo $data['password_err']; ?></span>

        <div class="save-cancel">
        <button type="submit" class="save-btn">Save</button>
        <a href="<?php echo URLROOT; ?>/users/viewCareseekerProfile" class="cancel-button">Cancel</a>
        </div>
        </div>

        <div class="form-right">
        <label>Address</label>
        <input type="text" name="address" value="<?php echo $data['address']; ?>">
        <span class="form-invalid"><?php echo $data['address_err']; ?></span>

        <label>Contact No</label>
        <input type="text" name="contact_info" value="<?php echo $data['contact_info']; ?>">
        <span class="form-invalid"><?php echo $data['contact_info_err']; ?></span>

        <label>Gender</label>
        <input type="text" name="gender" value="<?php echo $data['gender']; ?>">
        <span class="form-invalid"><?php echo $data['gender_err']; ?></span>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password">
        <span class="form-invalid"><?php echo $data['confirm_password_err']; ?></span>
        </div>
        
    </form>
    

    
    </div>
</div>
<?php require APPROOT.'/views/includes/footer.php' ?>
