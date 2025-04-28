<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/cghistory.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="view-requests-m-c-r-container">

      <div class="view-requests-m-c-r-table-container">
        <h2>Consulting History</h2>
        <form method="GET" action="<?php echo URLROOT; ?>/consultant/consultingHistory" class="view-requests-m-c-r-filter-section">
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
            </select>

            <label for="payment-filter">Payment:</label>
            <select id="payment-filter" name="payment_filter" class="filter-select">
                <option value="all" <?= ($_GET['payment_filter'] ?? '') == 'all' ? 'selected' : '' ?>>All</option>
                <option value="pending" <?= ($_GET['payment_filter'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="done" <?= ($_GET['payment_filter'] ?? '') == 'done' ? 'selected' : '' ?>>Done</option>
                <option value="refunded" <?= ($_GET['payment_filter'] ?? '') == 'refunded' ? 'selected' : '' ?>>Refunded</option>
            </select>

            <button type="submit" id="apply-filters-btn" class="view-requests-m-c-r-view-req-action-btn">
                Apply
            </button>
        </form>
        <div class="view-requests-m-c-r-table">
          
          <div class="view-requests-m-c-r-table-body">
          <?php if (empty($data['history'])): ?>
            <div class="no-history">
                <img src="<?php echo URLROOT; ?>/public/images/Empty-cuate.png" alt="No History">
                <p>You don't have any caregiving history yet.</p>
            </div>
        <?php else: ?>


            <?php foreach ($data['history'] as $entry): ?>
                <div class="view-requests-m-c-r-table-row">
                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #<?php echo $entry->request_id; ?></p>
                        <p><strong>Care Seeker:</strong> <?php echo ucfirst($entry->username); ?> <span class="id">CS<?php echo $entry->requester_id; ?></span> </p>
                        <!-- <p><strong>Service Type:</strong> 
                            <?php echo isset($entry->duration_type) ? ucfirst($entry->duration_type) : 'N/A'; ?> -->
                        <!-- </p> -->

                        <p><strong>Service Time Slot:</strong> 
                            <?php
                            if (isset($entry->start_time) && isset($entry->end_time)) {
                                $start_time = new DateTime($entry->start_time);
                                $end_time = new DateTime($entry->end_time);

                                echo $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>

                        <p><strong>Request Accepted Date:</strong> 
                            <?php 
                            if (isset($entry->updated_at)) {
                                $start_date = new DateTime($entry->updated_at);
                                echo $start_date->format('jS M Y'); 
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>

                        <p><strong>Status:</strong> 
                            <?php
                            if (isset($entry->status)) {
                                $current_date = new DateTime();
                                $end_date = isset($entry->end_date) ? new DateTime($entry->end_date) : null;

                                if ($entry->status == 'accepted') {
                                    if ($end_date && $end_date > $current_date) {
                                        $badge = 'ongoing';
                                    } else {
                                        $badge = 'completed';
                                    }
                                } else {
                                    $badge = 'Request ' . $entry->status;
                                }

                                $badgeClass = strtolower(str_replace(' ', '-', $badge));
                                echo '<span class="status ' . $badgeClass . '">' . $badge . '</span>';
                            } else {
                                echo '<span class="status unknown">Unknown</span>';
                            }
                            ?>
                        </p>
                        
                    </div>
                    <div class="payment-info">
                    
                    <?php 
                            $profilePic = !empty($entry->profile_picture) ? URLROOT . '/public/images/profile_imgs/' . $entry->profile_picture : URLROOT . '/public/images/def_profile_pic.jpg';
                        ?>
                        <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-pic">
                        <p><strong>Total Payment:</strong> Rs.<?php echo number_format($entry->payment_details ?? 0); ?>  </p>
                        <p><strong>Paid Amount:</strong>
                        <?php
                            if($entry->is_paid==1){
                                $paid_amount = $entry->payment_details;
                            }
                            else{
                                $paid_amount = 0;
                            }
                            
                        ?>
                         Rs.<?php echo number_format($paid_amount); ?></p>

                         <?php if($entry->status == 'cancelled'): ?>
                            <p><strong>Refund Amount:</strong> Rs.<?php echo number_format($entry->refund_amount); ?></p>
                            <?php endif; ?>
                        
                        <div class="btn-class">
                            <button class="view-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/Consultant/viewConsultantProfile/<?php echo $entry->elder_id; ?>'">View Profile</button>

                        
                        </div>
                    </div>
                    
                </div>
                
                
                
                </div>
            <?php endforeach; ?>


            <?php endif; ?>

            <!-- Your existing table rows here -->
          </div>
        </div>
      </div>
    </div>


        

               
    </div>

    

</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>


