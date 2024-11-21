<?php require APPROOT.'/views/includes/header.php'; ?>
<body>
<!--Top navbar  -->
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<div class="top-container">
    <div class="container">
   
        <div class="gradient-bar"> <a href="<?php echo URLROOT; ?>/users/editProfile" class="edit-button">Edit</a></div> <!-- Gradient bar behind the profile section -->
        
        <header>
            <h2>Welcome,Consultant Senesh</h2>
            <!-- php echo $data['profileData'][0]->username; -->
            <p>Joined on (Date)</p>
            <!-- $createdAt = $data['profileData'][0]->created_at;
                                $date = new DateTime($createdAt);
                                $formattedDate = $date->format('jS F Y');
                                echo $formattedDate;  -->
            
        </header>

        <div class="profile-section">
            <div class="profile-pic">
            <img src="./def_profile_pic.jpg" 
                        alt="Profile Picture" class="profile-pic" id="profile_image_placeholder">
                        <!-- isset($data['profileData'][0]->profile_picture) && $data['profileData'][0]->profile_picture 
                        ? URLROOT . '/images/profile_imgs/' . $data['profileData'][0]->profile_picture 
                        : URLROOT . '/images/def_profile_pic.jpg'; -->


            </div>
            
            <div class="profile-nameEmail">
                <h3 style="display:inline">Senesh Dinelka</h3>
                <p style="display:inline">/#15</p>
                <p style="display:inline">/Consultant</p>

                <!-- php echo $data['profileData'][0]->username; ?>
                <php echo $data['profileData'][0]->user_id;>
                <php echo $data['profileData'][0]->role; -->

                
                            <!-- // $dateOfBirth = $data['profileData'][0]->date_of_birth;
                            // $dob = new DateTime($dateOfBirth);
                            // $today = new DateTime('today');
                            // $age = $dob->diff($today)->y;
                            // echo $age; >  -->
                            <!-- yrs</p> -->
                
            </div>
        </div>
        <form class="profile-form">
            <div class="form-left">
                <label>Full Name</label>
                <input type="text" value="<Full name>" readonly>
                <!-- php echo $data['profileData'][0]->username; -->

                <label>Email</label>
                <input type="email" value="<email>" readonly>
                <!-- php echo $data['profileData'][0]->email; -->

                <label>Date of Birth</label>
                <input type="date" value="<D/O/B>" readonly>
                <!-- php echo $data['profileData'][0]->date_of_birth; -->

                <label>Password</label>
                <input type="password" placeholder="****************">

                <br></br>
                <label>Specifications</label>
                <ul>
                    <li><input type="text" value="<Qualification>" readonly></li>
                    <li><input type="text" value="<Qualification>" readonly></li>
                    <li><input type="text" value="<Qualification>" readonly></li>
                </ul>

                <br>
                <label>Bio</label>
                <ul>
                    <li><input type="text" value="<Qualification>" readonly></li>
                    <li><input type="text" value="<Qualification>" readonly></li>
                    <li><input type="text" value="<Qualification>" readonly></li>
                </ul>
            </div>

            <div class="form-right">
                <label>Address</label>
                <input type="text" value="<address>" readonly>
                <!-- php echo $data['profileData'][0]->address; -->

                <label>Contact No</label>
                <input type="text"value="<contact-no.>" readonly>
                <!-- php echo $data['profileData'][0]->contact_info; -->

                <label>Gender</label>
                <input type="text" value="<gender>" readonly>
                <!-- php echo $data['profileData'][0]->gender; -->

                <label>Rating</label>
                <input type="text" value="<myrate>" readonly>
                <!-- php echo $data['profileData'][0]->rating -->
                 
                <br></br>
                <label>Qualifications</label>
                <ul>
                    <li><input type="text" value="<Qualification>" readonly></li>
                    <li><input type="text" value="<Qualification>" readonly></li>
                    <li><input type="text" value="<Qualification>" readonly></li>
                </ul>

                <br>
                <br>
                <ul>
                    <input type="text" value="<Qualification>" readonly>
                    <input type="text" value="<Qualification>" readonly>
                    <input type="text" value="<Qualification>" readonly>
                </ul>

            </div>
            
            
        </form>
        
            <div class="save-cancel">
                    <button type="submit" class="save-btn">View Requests</button>
                    <button type="submit" class="save-btn">Search Careseekers (tempory)</button>
                    <a href="<?php echo URLROOT; ?>/users/deleteUser" class="cancel-button">Delete User</a>
                </div>

    </div>
</div>
 
<?php require APPROOT.'/views/includes/footer.php' ?>








