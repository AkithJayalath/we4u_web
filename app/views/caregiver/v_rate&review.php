<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/rate&review.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    <div class="container">
        <div class="header">
            <h2>Ratings and Reviews</h2>
            <button class="add-review-btn" onclick="window.location.href='<?php echo URLROOT; ?>/careseeker/addReview/<?php echo $data['caregiver_id']; ?>/Caregiver'">
                Add Review
            </button>
        </div>

        <div class="review">
            <?php if(!empty($data['reviews'])) : ?>
            <?php foreach ($data['reviews'] as $review) : ?>
                <div class="review-card">
                    <div class="user-details">
                        <img src="<?php echo !empty($review->profile_picture) ? URLROOT . '/images/profile_imgs/'. $review->profile_picture : URLROOT .'/images/def_profile_pic.jpg'; ?>" alt="Profile Image" class="pro-img"/>
                        <h3 class="name"><?php echo $review->username; ?></h3>
                        <div class="review-date">
                            <span><?php echo date('d M Y', strtotime($review->review_date)); ?></span>
                        </div>
                    </div>
                    <p class="review-cont">
                        <?php echo $review->review_text; ?>
                    </p>
                    <div class="rating">
                        <?php for($i=1; $i<=5; $i++) : ?>
                            <i class="fa-solid fa-star <?php echo ($i <= $review->rating) ? 'active' : ''; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    
                    <!-- Edit and Delete Buttons -->
                    <div class="review-actions-icons">
                        <button class="edit-btn-pen" onclick="window.location.href='<?php echo URLROOT; ?>/careseeker/editReview/<?php echo $review->review_id; ?>'">
                            <i class="fa-solid fa-pen-to-square"></i> 
                        </button>
                        <button class="delete-btn-trash" onclick="if(confirm('Are you sure you want to delete this review?')) { window.location.href='<?php echo URLROOT; ?>/careseeker/deleteReview/<?php echo $review->review_id; ?>'; }">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else : ?>
            <div class="no-reviews">
                <img src="/we4u/public/images/Empty-cuate.png" alt="No Request">
                <p>No reviews yet</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';?>