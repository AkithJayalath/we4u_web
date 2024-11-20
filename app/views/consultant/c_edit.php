<?php require APPROOT.'/views/includes/header.php'; ?>
<body>
<!-- Top navbar -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<div class="top-container">
    <div class="container-edit">
   
        
        <header>
            <h2>Edit My Profile</h2>
        </header>
        <div class="gradient-bar-edit"></div> <!-- Gradient bar behind the profile section -->

        <!-- Profile form -->
        <form action="<?php echo URLROOT; ?>/users/editProfile" method="POST" class="profile-form" enctype="multipart/form-data">
            <!-- Profile image section inside form with new class -->
            <div class="profile-image-wrapper">
                <div class="profile-pic-container" onclick="triggerUpload()">
                <img src="<?= isset($data['profile_picture']) && $data['profile_picture'] 
                            ? URLROOT . '/images/profile_imgs/' . $data['profile_picture'] 
                            : URLROOT . '/images/def_profile_pic.jpg'; ?>" 
                            alt="Profile Picture" class="profile-pic" id="profile_image_placeholder">

                    <div class="upload-overlay">
                        <span>Upload your photo</span>
                    </div>
                    <input type="file" id="profileImageUpload" name="profile_picture" accept="image/*" style="display: none;" onchange="previewImage(event)">
                </div>
                <div class="form-validation">
                    <div class="profile-image-validation">
                        <img src="<?php echo URLROOT;?>/images/green_tick.png" alt="green_tick" width="25px" height="40px" style="padding-top: 15px">
                        Upload image
                    </div>
                </div>
            </div>
            <span class="form-invalid"></span>
            <!-- php echo $data['profile_picture_err']; -->

            <!-- Form fields in two columns -->
            <div class="form-left">
                <label>Username</label>
                <input type="text" name="username" value="username">
                <!-- php echo $data['username'];  -->
                <span class="form-invalid">username_error</span>
                <!-- php echo $data['username_err'];  -->

                <label>Email</label>
                <input type="email" name="email" value="email">
                <!-- php echo $data['email']; -->
                <span class="form-invalid">email error</span>
                <!-- php echo $data['email_err'];  -->

                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" value="d/o/b">
                <!-- php echo $data['date_of_birth'];  -->
                <span class="form-invalid">dob_error</span>
                <!-- php echo $data['dob_err']; -->

                <label>Password</label>
                <input type="password" name="password">
                <span class="form-invalid">pwd_error</span>
                <!-- php echo $data['password_err'];  -->

                <div class="save-cancel">
                    <button type="submit" class="save-btn">Save</button>
                    <a href="<?php echo URLROOT; ?>/consultant/consulatantview" class="cancel-button">Cancel</a>
                </div>
            </div>

            <div class="form-right">
                <label>Address</label>
                <input type="text" name="address" value="address">
                <!-- php echo $data['address']; -->
                <span class="form-invalid">address_error</span>
                <!-- php echo $data['address_err'];  -->

                <label>Contact No</label>
                <input type="text" name="contact_info" value="<contact>">
                <!-- php echo $data['contact_info'];  -->
                <span class="form-invalid">contact_error</span>
                <!-- php echo $data['contact_info_err']; -->

                <label>Gender</label>
                <input type="text" name="gender" value="gender">
                <!-- php echo $data['gender']; -->
                <span class="form-invalid">gender_error</span>
                <!-- php echo $data['gender_err'];  -->

                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
                <span class="form-invalid">pwd_error</span>
                <!-- php echo $data['confirm_password_err'];  -->
            </div>
        </form>
    </div>
</div>
<script src="<?php echo URLROOT; ?>/js/profilePicUpload.js"></script>
<?php require APPROOT.'/views/includes/footer.php' ?>