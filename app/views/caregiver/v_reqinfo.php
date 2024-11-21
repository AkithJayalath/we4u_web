<?php require APPROOT.'/views/includes/header.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/viewreqinfo.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">


<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="container">
        <div class="header">
            <h2>Request Information</h2>
            <p class="id">Request ID #12345</p>
            <span class="sts-pending">Pending</span>
            <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>

        </div>

        <div class="request-info">
            <div>
                <lable>Care Seeker's Name & ID</lable>
                <div class="content-box">
                    <p class="name">Jerome Bell</p>
                    <p class="id">CS12345</p>
                </div>
            </div>

            <div>
                <lable>Location</lable>
                <div class="content-box">
                    <p class="location">12345, Colombo</p>
                </div>
            </div>

            <div>
                <lable>Care Seeker's Contact Details</lable>
                <div class="content-box">
                    <p class="contact"><i class="fa-solid fa-phone">&nbsp;</i>0712345678</p>
                    <p class="email"><i class="fa-solid fa-envelope">&nbsp;</i>jerome@gmail.com</p>
                </div>
            </div>

            <div>
                <lable>Requested Time Slot</lable>
                <div class="content-box">
                    <p class="date"><i class="fa-regular fa-calendar-days">&nbsp;</i>From:18th Jan To:19th Jan</p>
                    <p class="time"><i class="fa-solid fa-clock"></i>&nbsp;</i>From:8:00 AM To:6:00 PM</p>
                </div>
            </div>

            <div>
                <lable>Requested Services</lable>
                <div class="content-box">
                    <details>
                        <summary>Care Giving</summary>
                        <ul>
                            <li>Assistance with daily living activities</li>
                            <li>Medication reminders</li>
                            <li>Contact with consultant</li>
                            <li>Meal Preparation</li>
                        </ul>
                    </details>
                </div>
            </div>

            <div>
                <lable>Additional Notes &nbsp;<i class="fa-solid fa-circle-info"></i></lable>
                <div class="content-box">
                    <p class="notes">Lorem ipsum dolor sit amet, consectetur adipiscing elit.&nbsp;<i class="fa-solid fa-plus"></i></p>
        
                </div>
            </div>

            <div>
                <lable>Payment Details</lable>
                <div class="content-box">
                    <details>
                        <summary><strong>Payment Method:</strong> Cash</summary>
                        <ul>
                            <li><strong>Daily Fee:</strong> Rs.2000</li>
                            <li><strong>Number of Days:</strong> 2</li>
                            <li><strong>Total Amount:</strong> Rs.4000</li>
                            <li><strong>Payment Status:</strong> 10% paid</li>
                        </ul>
                    </details>
                </div>
            </div>

            <div>
                <lable>Update Your calendar</lable>
                <div class="content-box">
                    <button class="btn-update"><i class="fa-regular fa-calendar-days"></i>&nbsp;Update Calendar</button>
                </div>
            </div>

            <div class="btn-container">
                <button class="btn-accept">Accept</button>
                <div class="popup-accept">
                   <div class="popup-box">
                   <i class="fa-solid fa-circle-exclamation"></i>                    
                    <h2>Are you sure?</h2>
                    <p>You will not be able to make any changes!</p>
                    <button class="btn-accept">Yes,Sure</button>
                    <button class="btn-cancel">Cancel</button>
                   </div>
                </div>

                <button class="btn-reject">Reject</button>
            </div>

        </div>
    </div>
</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';?>