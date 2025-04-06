<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>
 


<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/cgrequest.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>

<?php require APPROOT.'/views/includes/components/sidebar.php';?>


    <div class="container">
        
        <div class="header">
            <h2>Requests<h2>
            <div class="view-request-filter-section">
                <label for="filter-date">Date:</label>
                <select id="filter-date" class="filter-select">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                </select>

                <label for="filter-status">Status:</label>
                <select id="filter-status" class="filter-select">
                    <option value="all">All</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                </select>

                <button class="view-request-action-btn" id="apply-filters-btn">
                    Apply
                </button>
            </div>
        </div>

        <div class="request-list">
            <div class="request-item">
                <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>
                <p class="name">Jerome Bell</p>
                <p class="req-date"><i class="fa-regular fa-calendar-days"></i>&nbsp;
                12th Aug 2024</p>
                <p class="req-time"><i class="fa-solid fa-clock"></i>&nbsp;12:53 PM</p>
                    
                <div class="req-action">
                    <span class="sts-pending">Pending</span>
                    <button class="view-btn" onclick="navigateToDetails()">View</button>


                </div>
            </div>

            <div class="request-item">
                <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>
                <p class="name">Jerome Bell</p>
                <p class="req-date"><i class="fa-regular fa-calendar-days"></i>&nbsp;
                12th Aug 2024</p>
                <p class="req-time"><i class="fa-solid fa-clock"></i>&nbsp;12:53 PM</p>
                    
                <div class="req-action">
                    <span class="sts-accept">Accepted</span>
                    <button class="view-btn">View</button>
                </div>
            </div>

            <div class="request-item">
                <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>
                <p class="name">Jerome Bell</p>
                <p class="req-date"><i class="fa-regular fa-calendar-days"></i>&nbsp;
                12th Aug 2024</p>
                <p class="req-time"><i class="fa-solid fa-clock"></i>&nbsp;12:53 PM</p>
                    
                <div class="req-action">
                    <span class="sts-reject">rejected</span>
                    <button class="view-btn">View</button>
                </div>
            </div>

            <div class="request-item">
                <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>
                <p class="name">Jerome Bell</p>
                <p class="req-date"><i class="fa-regular fa-calendar-days"></i>&nbsp;
                12th Aug 2024</p>
                <p class="req-time"><i class="fa-solid fa-clock"></i>&nbsp;12:53 PM</p>
                    
                <div class="req-action">
                    <span class="sts-pending">Pending</span>
                    <button class="view-btn">View</button>
                </div>
            </div>

            <div class="request-item">
                <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>
                <p class="name">Jerome Bell</p>
                <p class="req-date"><i class="fa-regular fa-calendar-days"></i>&nbsp;
                12th Aug 2024</p>
                <p class="req-time"><i class="fa-solid fa-clock"></i>&nbsp;12:53 PM</p>
                    
                <div class="req-action">
                    <span class="sts-pending">Pending</span>
                    <button class="view-btn">View</button>
                </div>
            </div>

        </div>
        

    </div>

</page-body-container>


<?php require APPROOT.'/views/includes/footer.php';?>

<script>
function navigateToDetails() {
    window.location.href = '<?php echo URLROOT; ?>/caregivers/viewreqinfo';
}




</script>









