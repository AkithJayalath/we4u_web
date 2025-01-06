<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/rate&review.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>


<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    <div class="container">
        <div class="header">
                <h2>Ratings and Reviews<h2>
                <button class="btn"><a href="<?php echo URLROOT; ?>/consultant/addreview" >Rate</a></button>
                <i class="fa-solid fa-bell"></i>
        </div>

        <div class="review">
            <?php foreach($data['rateandreview'] as $review): ?>
                <div class="review-card">
                    <div class="user-details">
                        <img src="<?php echo URLROOT; ?>/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>
                        <h3 class="name"><?php echo $review->username; ?></h3>
                        <div class="review-date">
                            <span><?php echo date('jS M Y', strtotime($review->review_date)); ?></span>
                        </div>
                    </div>
                    <p class="review-cont">
                        <?php echo htmlspecialchars($review->review_text); ?>
                    </p>
                    <div class="rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
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
        </div>
    </div>


</page-body-container>
