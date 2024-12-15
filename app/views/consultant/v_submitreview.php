<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/submit_review.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    
    <div class="container">
        <div class="review-form">
            <h2>Submit Your Review</h2>
            
            <div class="star-rating">
                <i class="fa-solid fa-star" data-rating="1"></i>
                <i class="fa-solid fa-star" data-rating="2"></i>
                <i class="fa-solid fa-star" data-rating="3"></i>
                <i class="fa-solid fa-star" data-rating="4"></i>
                <i class="fa-solid fa-star" data-rating="5"></i>
            </div>
            
            <div class="review-input">
                <textarea placeholder="Share your experience..."></textarea>
            </div>
            
            <button class="submit-btn">Submit Review</button>
        </div>
    </div>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/rateAndReviews.js"></script>
