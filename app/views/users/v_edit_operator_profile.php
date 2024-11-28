<?php require APPROOT.'/views/includes/header.php'; ?>
<body>
<!-- Top navbar -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>


<page-body-container>
<?php require APPROOT.'/views/includes/components/sidebar.php'; ?>



<div class="top-container">
    <div class="container-edit">
   
       
        
        <header>
            <h2>Edit Profile</h2>
        </header>
        <div class="gradient-bar-edit"></div> <!-- Gradient bar behind the profile section -->

        <!-- Profile form -->
        <form action="<?php echo URLROOT; ?>/operatorProfile/editProfile" method="POST" class="profile-form" enctype="multipart/form-data">
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
            <span class="form-invalid"><?php echo $data['profile_picture_err']; ?></span>

            <!-- Form fields in two columns -->
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
                <input type="text" name="address" value="none"></span>

                <label>Contact No</label>
                <input type="text" name="contact_info" value="none">
                <!-- <span class="form-invalid"><?php echo $data['contact_info_err']; ?></span> -->

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
</page-body-container>


<script src="<?php echo URLROOT; ?>/js/profilePicUpload.js"></script>
<?php require APPROOT.'/views/includes/footer.php' ?>
