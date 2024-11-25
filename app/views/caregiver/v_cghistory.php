<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/cghistory.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="container">
        <div class="header">
            <h2>Caregiving History<h2>
                <div class="sort">
                    <select id="sort-by">
                        <option value="default">Sort by</option>
                        <option value="rating">Rating</option>
                        <option value="date">Date</option>
                        <option value="payment">Payment</option>
                    </select>
                </div>
         
        </div>

        <div class="history-list">
                <!-- First Entry -->
                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #123321</p>
                        <p><strong>Care Giver:</strong> Nirmi Kaumada <span class="id">CG125875</span></p>
                        <p><strong>Care Seeker:</strong> Tanuri Mandini <span class="id">CS123456</span> </p>
                        <p><strong>From:</strong> 12th Jan <strong>To:</strong> 18th Jan </p>
                        <p><strong>Request Accepted Date:</strong> 08th Aug 2024</p>
                        <p><strong>Status:</strong> <span class="status completed">Completed</span></p>
                        <p><strong>Completed Date & Time:</strong> 18th Jan 6:00PM</p>
                        
                    </div>
                    <div class="payment-info">
                        <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Picture" class="profile-pic">
                        <p><strong>Reviews for this session:</strong> <a href="#" class="reviews-link">View Reviews</a></p>
                        <p><strong>Total Payment:</strong> Rs.10,000</p>
                        <p><strong>Paid Amount:</strong> Rs.0</p>
                        
                        <button class="view-profile-btn">View Profile</button>
                    </div>
                    
                </div>

                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #123321</p>
                        <p><strong>Care Giver:</strong> Nirmi Kaumada <span class="id">CG125875</span></p>
                        <p><strong>Care Seeker:</strong> Tanuri Mandini <span class="id">CS123456</span> </p>
                        <p><strong>From:</strong> 12th Jan <strong>To:</strong> 18th Jan </p>
                        <p><strong>Request Accepted Date:</strong> 08th Aug 2024</p>
                        <p><strong>Status:</strong> <span class="status ongoing">On Going</span></p>
                        <p><strong>Completed Date & Time:</strong> 18th Jan 6:00PM</p>
                        
                    </div>
                    <div class="payment-info">
                        <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Picture" class="profile-pic">
                        <p><strong>Reviews for this session:</strong> <a href="#" class="reviews-link">View Reviews</a></p>
                        <p><strong>Total Payment:</strong> Rs.10,000</p>
                        <p><strong>Paid Amount:</strong> Rs.0</p>
                        
                        <button class="view-profile-btn">View Profile</button>
                    </div>
                    
                </div>

                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #123321</p>
                        <p><strong>Care Giver:</strong> Nirmi Kaumada <span class="id">CG125875</span></p>
                        <p><strong>Care Seeker:</strong> Tanuri Mandini <span class="id">CS123456</span> </p>
                        <p><strong>From:</strong> 12th Jan <strong>To:</strong> 18th Jan </p>
                        <p><strong>Request Accepted Date:</strong> 08th Aug 2024</p>
                        <p><strong>Status:</strong> <span class="status completed">Completed</span> <span class="status ongoing">On Going</span></p>
                        <p><strong>Date & Time to Complete:</strong> 18th Jan 6:00PM</p>
                        
                    </div>
                    <div class="payment-info">
                        <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Picture" class="profile-pic">
                        <p><strong>Reviews for this session:</strong> <a href="#" class="reviews-link">View Reviews</a></p>
                        <p><strong>Total Payment:</strong> Rs.10,000</p>
                        <p><strong>Paid Amount:</strong> Rs.0</p>
                        
                        <button class="view-profile-btn">View Profile</button>
                    </div>
                    
                </div>

                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #123321</p>
                        <p><strong>Care Giver:</strong> Nirmi Kaumada <span class="id">CG125875</span></p>
                        <p><strong>Care Seeker:</strong> Tanuri Mandini <span class="id">CS123456</span> </p>
                        <p><strong>From:</strong> 12th Jan <strong>To:</strong> 18th Jan </p>
                        <p><strong>Request Accepted Date:</strong> 08th Aug 2024</p>
                        <p><strong>Status:</strong> <span class="status completed">Completed</span> <span class="status ongoing">On Going</span></p>
                        <p><strong>Completed Date & Time:</strong> 18th Jan 6:00PM</p>
                        
                    </div>
                    <div class="payment-info">
                        <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Picture" class="profile-pic">
                        <p><strong>Reviews for this session:</strong> <a href="#" class="reviews-link">View Reviews</a></p>
                        <p><strong>Total Payment:</strong> Rs.10,000</p>
                        <p><strong>Paid Amount:</strong> Rs.0</p>
                        
                        <button class="view-profile-btn">View Profile</button>
                    </div>
                    
                </div>

                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #123321</p>
                        <p><strong>Care Giver:</strong> Nirmi Kaumada <span class="id">CG125875</span></p>
                        <p><strong>Care Seeker:</strong> Tanuri Mandini <span class="id">CS123456</span> </p>
                        <p><strong>From:</strong> 12th Jan <strong>To:</strong> 18th Jan </p>
                        <p><strong>Request Accepted Date:</strong> 08th Aug 2024</p>
                        <p><strong>Status:</strong> <span class="status completed">Completed</span> <span class="status ongoing">On Going</span></p>
                        <p><strong>Completed Date & Time:</strong> 18th Jan 6:00PM</p>
                        
                    </div>
                    <div class="payment-info">
                        <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Picture" class="profile-pic">
                        <p><strong>Reviews for this session:</strong> <a href="#" class="reviews-link">View Reviews</a></p>
                        <p><strong>Total Payment:</strong> Rs.10,000</p>
                        <p><strong>Paid Amount:</strong> Rs.0</p>
                        
                        <button class="view-profile-btn">View Profile</button>
                    </div>
                    
                </div>
    </div>
</div>
    
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>
