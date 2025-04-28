<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/rate&reviewNew.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="container">
        <div class="header">
            <h2>Ratings & reviews</h2>
            <div class="consultant-rating">
                <?php 
                $avgRating = 0;
                if (!empty($data['rateandreview'])) {
                    $totalRating = 0;
                    $count = 0;
                    foreach ($data['rateandreview'] as $review) {
                        $totalRating += $review->rating;
                        $count++;
                    }
                    $avgRating = $count > 0 ? round($totalRating / $count, 1) : 0;
                }
                ?>
                <?php for($i=1; $i<=5; $i++) : ?>
                    <i class="fa-solid fa-star <?php echo ($i <= $avgRating) ? 'active' : ''; ?>"></i>
                <?php endfor; ?>
            </div>
            <button class="add-review-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/addreview'">
                <i class="fas fa-plus"></i> Add Review
            </button>
        </div>

        <div class="review">
            <?php if (empty($data['rateandreview'])): ?>
                <div class="no-reviews">
                    <img src="<?php echo URLROOT; ?>/public/images/Empty-cuate.png" alt="No Reviews">
                    <p>You don't have any reviews yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($data['rateandreview'] as $review): ?>
                    <div class="review-card">
                        <div class="user-details">
                            <img src="<?php echo !empty($review->profile_picture) ? URLROOT . '/images/profile_imgs/'. $review->profile_picture : URLROOT .'/images/def_profile_pic.jpg'; ?>" alt="Profile Image" class="pro-img"/>
                            <h3 class="name"><?php echo $review->username; ?></h3>
                            <div class="review-date">
                                <span>
                                    <?php 
                                    echo !empty($review->updated_date) 
                                        ? date('d M Y', strtotime($review->updated_date)) 
                                        : date('d M Y', strtotime($review->review_date)); 
                                    ?>
                                </span>
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
                        <?php if($review->reviewer_id == $_SESSION['user_id']): ?>
                            <div class="review-actions">
                                <a href="<?php echo URLROOT; ?>/consultant/editreview/<?php echo $review->review_id; ?>" class="edit-btn">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/consultant/deletereview/<?php echo $review->review_id; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this review?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>