<?php require APPROOT.'/views/includes/header.php'; ?>
<body>
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<div class="top-container">
    <div class="container">
   
        <div class="gradient-bar"> <a href="<?php echo URLROOT; ?>/users/editProfile" class="edit-button">Edit</a></div> <!-- Gradient bar behind the profile section -->
        
        <header>
            <h2>Welcome, <?php echo $data['profileData'][0]->username; ?></h2>
            <p>Joined on <?php 
                $createdAt = $data['profileData'][0]->created_at;
                $date = new DateTime($createdAt);
                $formattedDate = $date->format('jS F Y');
                echo $formattedDate; 
            ?></p>
        </header>

        <div class="profile-section">
            <div class="profile-pic">
                <img src="<?php echo isset($data['profileData'][0]->profile_picture) && $data['profileData'][0]->profile_picture 
                    ? URLROOT . '/images/profile_imgs/' . $data['profileData'][0]->profile_picture 
                    : URLROOT . '/images/def_profile_pic.jpg'; ?>" 
                    alt="Profile Picture" class="profile-pic" id="profile_image_placeholder">
            </div>
            
            <div class="profile-nameEmail">
                <h3 style="display:inline"><?php echo $data['profileData'][0]->username; ?></h3>
                <p style="display:inline">/#<?php echo $data['profileData'][0]->user_id; ?></p>
                <p style="display:inline">/<?php echo $data['profileData'][0]->role; ?></p>
            </div>
        </div>
        <form class="profile-form">
            <div class="form-left">
                <label>Full Name</label>
                <input type="text" value="<?php echo $data['profileData'][0]->username; ?>" readonly>

                <label>Email</label>
                <input type="email" value="<?php echo $data['profileData'][0]->email; ?>" readonly>

                <label>Date of Birth</label>
                <input type="date" value="<?php echo $data['profileData'][0]->date_of_birth; ?>" readonly>

                <label>Password</label>
                <input type="password" placeholder="****************">

                <br></br>
                <label>Specifications</label>
                <ul>
                    <?php foreach ($data['profileData'][0]->qualifications as $qualification): ?>
                        <li><input type="text" value="<?php echo $qualification; ?>" readonly></li>
                    <?php endforeach; ?>
                </ul>

                <br>
                <label>Bio</label>
                <ul>
                    <?php foreach ($data['profileData'][0]->bio as $bio): ?>
                        <li><input type="text" value="<?php echo $bio; ?>" readonly></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="form-right">
                <label>Address</label>
                <input type="text" value="<?php echo $data['profileData'][0]->address; ?>" readonly>

                <label>Contact No</label>
                <input type="text" value="<?php echo $data['profileData'][0]->contact_info; ?>" readonly>

                <label>Gender</label>
                <input type="text" value="<?php echo $data['profileData'][0]->gender; ?>" readonly>

                <label>Rating</label>
                <input type="text" value="<?php echo $data['profileData'][0]->rating; ?>" readonly>
                 
                <br></br>
                <label>Qualifications</label>
                <ul>
                    <?php foreach ($data['profileData'][0]->qualifications as $qualification): ?>
                        <li><input type="text" value="<?php echo $qualification; ?>" readonly></li>
                    <?php endforeach; ?>
                </ul>

                <br>
                <br>
                <ul>
                    <?php foreach ($data['profileData'][0]->qualifications as $qualification): ?>
                        <input type="text" value="<?php echo $qualification; ?>" readonly>
                    <?php endforeach; ?>
                </ul>

            </div>
        </form>
        
        <div class="save-cancel">
            <button type="submit" class="save-btn">View Requests</button>
            <button type="submit" class="save-btn">Search Careseekers (temporary)</button>
            <a href="<?php echo URLROOT; ?>/users/deleteUser" class="cancel-button">Delete User</a>
        </div>

    </div>
</div>
 
<?php require APPROOT.'/views/includes/footer.php'; ?>