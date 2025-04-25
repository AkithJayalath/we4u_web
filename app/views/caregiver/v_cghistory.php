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
                        <option value="status">Status</option>
                        <option value="date">Date</option>
                        <option value="payment">Payment</option>
                    </select>
                </div>
        </div>

        <div class="history-list">
        <?php if (empty($data['history'])): ?>
            <div class="no-history">
                <img src="<?php echo URLROOT; ?>/public/images/Empty-cuate.png" alt="No History">
                <p>You don't have any caregiving history yet.</p>
            </div>
        <?php else: ?>
        <?php foreach ($data['history'] as $entry): ?>
                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #<?php echo $entry->request_id; ?></p>
                        <p><strong>Care Seeker:</strong> <?php echo ucfirst($entry->username); ?> <span class="id">CS<?php echo $entry->requester_id; ?></span> </p>
                        <p><strong>Service Type:</strong> <?php echo ucfirst($entry->duration_type);; ?></p>

                        <p><strong>Service Date:</strong> 
                            <?php 
                            $start_date = new DateTime($entry->start_date);
                            $end_date = new DateTime($entry->end_date);
                            
                            if ($start_date->format('Y-m-d') === $end_date->format('Y-m-d')) {
                                
                                echo $start_date->format('jS M Y');
                            } else {
                                echo $start_date->format('jS M') . ' - ' . $end_date->format('jS M Y'); 
                            }
                            ?> 
                        </p>
                        <p><strong>Request Accepted Date:</strong> 
                            <?php 
                            $start_date = new DateTime($entry->updated_at);
                            echo $start_date->format('jS M Y'); 
                            ?>                            
                        
                        </p>
                        <p><strong>Status:</strong> <?php
                            $current_date = new DateTime();
                            $end_date = new DateTime($entry->end_date);

                            if($entry->status == 'accepted'){
                                if($end_date > $current_date){
                                    $badge = 'ongoing';
                                }
                                else{
                                    $badge = 'completed';
                                }
                            }else{
                                $badge = 'Request ' . $entry->status;
                            }

                            $badgeClass = strtolower(str_replace(' ', '-', $badge));
                           

                            ?><span class="status <?php echo $badgeClass; ?>"> <?php echo ($badge); ?></span></p>
                        
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
                            <button class="view-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/Caregivers/viewCareseeker/<?php echo $entry->elder_id; ?>'">View Profile</button>

                        <?php
                        $current_date = new DateTime();
                        $end_date = new DateTime($entry->end_date);
                        
                        $status = '';
                        if($entry->status == 'accepted'){
                            if($end_date > $current_date){
                                $status = 'ongoing';
                            } else {
                                $status = 'completed';
                            }
                        }
                        
                        if($status == 'ongoing' || $status == 'completed'): 
                        ?>
                            <button class="review-careseeker-btn" onClick="openRejectModal()">Add Review</button>
                        <?php endif; ?>
                        </div>
                    </div>
                    
                </div>

        <?php endforeach; ?>        
                    
                </div>

         <?php endif; ?>       


               
    </div>
</div>

<div id="rejecttModal" class="r-modal">
        <div class="r-modal-content">
            <div class="modal-header">
                <img src="/we4u/public/images/Online Review-rafiki.png" class="modal-img"/>
                <h2>Leave a Review</h2>
            </div>
            
            
            <form id="reviewForm">
                <input type="hidden" name="reviewed_user_id" id="reviewed_user_id" >
                <p>Your Review</p>
                <textarea id="rejectReason" placeholder="Write a review for this careseeker" rows="4" cols="50"></textarea>

                <div class="modal-buttons">
                    <button class="btn-submit" onclick="submitReview()">Submit</button>
                    <button class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>
<script>
    // Function to handle the Submit button
function submitReview() {
    const reviewText = document.getElementById('review_text').value.trim();
    const reviewedUserId = document.getElementById('reviewed_user_id').value;

    if (reviewText === '') {
        // Alert if the textarea is empty
        alert('Please write a review before submitting.');
        return;
    } 
    // Perform the AJAX request to submit the review
    const formData = new FormData();
    formData.append('review_text', reviewText);
    formData.append('reviewed_user_id', reviewedUserId);

    fetch('<?php echo URLROOT; ?>/caregivers/submitReview', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Handle success, e.g., show a success message
            alert(`Thank you for your review!`);
            closeRejectModal(); 
            location.reload();
        }
    
        else {
            alert('Error: ' + (data.error || 'Failed to submit review'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the review.');
    });
}


    


// Function to handle the Cancel button
function closeRejectModal() {
    const modal = document.getElementById('rejecttModal');
    modal.style.display = 'none'; // Hide the modal
}

// Function to open the modal (you can call this when you want to display the modal)
function openRejectModal() {
    const modal = document.getElementById('rejecttModal');
    modal.style.display = 'block'; // Show the modal
}

</script>