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
            
    <?php if (empty($data['requests'])): ?>
        <p class="no-requests">No requests found.</p>
    <?php else: ?>
        <?php foreach ($data['requests'] as $request): ?>
            <div class="request-item">
                <img src="<?php echo URLROOT . (!empty($request->profile_picture) ? '/public/images/profile_imgs/' . $request->profile_picture : '/public/images/def_profile_pic2.jpg'); ?>" 
                     alt="Profile Image" class="pro-img"/>
                <p class="name"><?php echo $request->requester_name; ?></p>
                <p class="req-date"><i class="fa-regular fa-calendar-days"></i>&nbsp;
                <?php echo $request->formatted_date; ?></p>
                <p class="req-time"><i class="fa-solid fa-clock"></i>&nbsp;<?php echo $request->formatted_time; ?></p>
                    
                <div class="req-action">
                    <span class="tag <?php echo strtolower($request->status); ?>">
                        <?php echo ucfirst($request->status); ?>
                    </span>
                    <button class="view-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/viewreqinfo/<?php echo $request->request_id; ?>'">
    View
</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
        

    </div>

</page-body-container>

<script src="<?php echo URLROOT; ?>/js/requestsfilterConsultant.js"></script>
<?php require APPROOT.'/views/includes/footer.php';?>










