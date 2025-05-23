<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>
 
<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/consultant/consultantRequests.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>

<?php require APPROOT.'/views/includes/components/sidebar.php';?>

<div class="request-info">

    <!-- Container -->
    <div class="view-requests-m-c-r-container">

      <div class="view-requests-m-c-r-table-container">
        <h2>Requests</h2>
       
            <form method="GET" action="<?php echo URLROOT; ?>/Consultants/viewRequests" class="view-requests-m-c-r-filter-section">

                <label for="filter-date">Date:</label>
                <select id="filter-date" name="date_sort" class="filter-select">
                    <option value="newest" <?= ($_GET['date_sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Newest</option>
                    <option value="oldest" <?= ($_GET['date_sort'] ?? '') == 'oldest' ? 'selected' : '' ?>>Oldest</option>
                </select>

                <label for="filter-status">Status:</label>
                <select id="filter-status" name="status_filter" class="filter-select">
                    <option value="all" <?= ($_GET['status_filter'] ?? '') == 'all' ? 'selected' : '' ?>>All</option>
                    <option value="accepted" <?= ($_GET['status_filter'] ?? '') == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                    <option value="cancelled" <?= ($_GET['status_filter'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    <option value="pending" <?= ($_GET['status_filter'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="rejected" <?= ($_GET['status_filter'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>

                <button id="apply-filters-btn" class="view-requests-m-c-r-view-req-action-btn">
                    Apply
                </button>
            </form>
            
        <div class="view-requests-m-c-r-table">
          
          <div class="view-requests-m-c-r-table-body">
          <?php if (empty($data['requests'])): ?>
                <div class="no-requests">
                        <img src="/we4u/public/images/Empty-cuate.png" alt="No Request">
                        <p>No requests yet</p>
                </div>
            <?php else: ?>

            <?php foreach ($data['requests'] as $request): ?>
              <div class="view-requests-m-c-r-table-row">
                <div class="view-requests-m-c-r-table-cell">
                    <img src="<?php echo URLROOT . (!empty($request->profile_picture) ? '/public/images/profile_imgs/' . $request->profile_picture : '/public/images/def_profile_pic2.jpg'); ?>" 
                    alt="Profile Image" class="pro-img"/>
                </div>
                <div class="view-requests-m-c-r-table-cell">
                    <p class="name"><?php echo $request->requester_name; ?></p>
                </div>
                <div class="view-requests-m-c-r-table-cell">
                    <p class="req-date"><i class="fa-regular fa-calendar-days"></i>&nbsp;
                    <?php echo $request->formatted_date; ?></p>
                </div>
                <div class="view-requests-m-c-r-table-cell">
                    <p class="req-time"><i class="fa-solid fa-clock"></i>&nbsp;<?php echo $request->formatted_time; ?></p>
                </div>
                <div class="view-requests-m-c-r-table-cell">
                <div class="req-action">
                        <span class="tag <?php echo strtolower($request->status); ?>">
                            <?php echo ucfirst($request->status); ?>
                        </span>
                    </div>
                </div>
                <div class="view-requests-m-c-r-table-cell">
                <button class="view-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/viewreqinfo/<?php echo $request->request_id; ?>'">
    View Request
</button>
                </div>
              </div>
            <?php endforeach; ?>

            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/requestsfilterConsultantNew.js"></script>
<?php require APPROOT.'/views/includes/footer.php';?>