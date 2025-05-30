<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/careseeker/viewConsultHistory.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="container">
        <div class="header">
            <h2>Consultant History</h2>
            <div class="sort">
                <select id="sort-by">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>
        </div>

        <div class="history-list">
        <?php if (empty($data['history'])): ?>
            <div class="no-history">
                <img src="<?php echo URLROOT; ?>/public/images/Empty-cuate.png" alt="No History">
                <p>You don't have any Consultant history yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($data['history'] as $entry): ?>
                <div class="history-entry">
                    <div class="service-info">
                        <p class="s-id"><strong>Service ID:</strong> #<?php echo $entry->request_id; ?></p>
                        <p><strong>Elder Profile:</strong> <?php echo ucfirst($entry->first_name. ' ' .$entry->middle_name.' '.$entry->last_name); ?> <span class="id"><?php echo $entry->relationship_to_careseeker; ?></span> </p>
                        <p><strong>Caregiver:</strong> <?php echo ucfirst($entry->username); ?> <span class="id">CG<?php echo $entry->consultant_id; ?></span> </p>

                        <p><strong>Appointment Date:</strong> 
                            <?php 
                            $appointmentDay = new DateTime($entry->appointment_date);
                            echo $appointmentDay->format('jS M Y');
                            ?> 
                        </p>
                        <p><strong>Time Slots:</strong> 
    <?php 
        // Format start time
        $start = DateTime::createFromFormat('H:i:s', $entry->start_time);
        $start_formatted = $start ? $start->format('g:i A') : $entry->start_time;
        
        // Format end time
        $end = DateTime::createFromFormat('H:i:s', $entry->end_time);
        $end_formatted = $end ? $end->format('g:i A') : $entry->end_time;
        
        echo $start_formatted . ' - ' . $end_formatted;
    ?>
</p>
                        

                        <p><strong>Request Accepted Date:</strong> 
                            <?php 
                            $accepted_date = new DateTime($entry->updated_at);
                            echo $accepted_date->format('jS M Y'); 
                            ?>                            
                        </p>
                        <p><strong>Status:</strong> <?php
                            
                            ?><span class="tag <?php echo $entry->status; ?>"> <?php echo $entry->status; ?></span></p>
                    </div>
                    <div class="payment-info">
                        <?php 
                            $profilePic = !empty($entry->profile_picture) ? URLROOT . '/public/images/profile_imgs/' . $entry->profile_picture : URLROOT . '/public/images/def_profile_pic.jpg';
                        ?>
                        <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-pic">
                        <p><strong>Total Payment:</strong> Rs.<?php echo number_format($entry->payment_details ?? 0); ?></p>
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

                        
                        <div class="btn-class">
                            <button class="view-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/Careseekers/viewCaregiver/<?php echo $entry->caregiver_id; ?>'">Review</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>        
        <?php endif; ?>       
        </div>
    </div>
</page-body-container>
<script>
    // Sort functionality for newest/oldest
    document.getElementById('sort-by').addEventListener('change', function() {
    const sortBy = this.value;
    const historyEntries = Array.from(document.querySelectorAll('.history-entry'));
    const historyList = document.querySelector('.history-list');
    
    historyEntries.sort((a, b) => {
        // Extract date from service date field - use the 5th paragraph, not the 4th
        const dateTextA = a.querySelector('.service-info p:nth-child(5)').textContent;
        const dateTextB = b.querySelector('.service-info p:nth-child(5)').textContent;
        
        // Extract the date part and handle the complex format
        const datePartA = dateTextA.split(': ')[1].trim();
        const datePartB = dateTextB.split(': ')[1].trim();
        
        // Get the last date mentioned (in case of date ranges)
        const finalDateA = datePartA.includes('-') ? datePartA.split('-')[1].trim() : datePartA;
        const finalDateB = datePartB.includes('-') ? datePartB.split('-')[1].trim() : datePartB;
        
        // Convert to date objects - handle the format like "25th Mar 2023"
        const dateA = new Date(finalDateA.replace(/(\d+)(st|nd|rd|th)/, '$1'));
        const dateB = new Date(finalDateB.replace(/(\d+)(st|nd|rd|th)/, '$1'));
        
        // For newest first, sort in descending order (b - a)
        // For oldest first, sort in ascending order (a - b)
        return sortBy === 'newest' ? dateB - dateA : dateA - dateB;
    });
    
    // Remove all current entries
    while (historyList.firstChild) {
        historyList.removeChild(historyList.firstChild);
    }
    
    // Add the sorted entries
    historyEntries.forEach(entry => {
        historyList.appendChild(entry);
    });
});
</script>

<?php require APPROOT.'/views/includes/footer.php';?>
