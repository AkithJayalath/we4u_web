<?php
    $required_styles = [
        'caregiver/submit_review',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
 
<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

    <main class="a-a-a-main-content">
        <div class="a-a-a-stat-card">
            <h2>Submit Your Review</h2>
            <form class="a-a-a-form" action="<?php echo URLROOT; ?>/consultant/addreview" method="POST" id="review-form">
                <div class="a-a-a-form-group">
                    <label for="rating">Rating</label>
                    <div class="star-rating">
                        <i class="fa-solid fa-star" data-rating="1"></i>
                        <i class="fa-solid fa-star" data-rating="2"></i>
                        <i class="fa-solid fa-star" data-rating="3"></i>
                        <i class="fa-solid fa-star" data-rating="4"></i>
                        <i class="fa-solid fa-star" data-rating="5"></i>
                    </div>
                    <input type="hidden" name="rating" id="rating" value="<?php echo $data['rating']; ?>">
                    <span class="invalid-feedback"><?php echo $data['rating_err']; ?></span>
                </div>

                <div class="a-a-a-form-group">
                    <label for="review">Review Content</label>
                    <textarea
                        id="review"
                        name="review"
                        class="a-a-a-input <?php echo (!empty($data['review_err'])) ? 'is-invalid' : ''; ?>"
                        rows="10"
                        placeholder="Share your experience..."
                        required
                    ><?php echo $data['review']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['review_err']; ?></span>
                </div>

                <div class="a-a-a-form-buttons">
                    <button type="button" class="a-a-a-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/viewrateandreview'">Cancel</button>
                    <button type="button" class="a-a-a-btn-save">Submit Review</button>
                </div>
            </form>
        </div>
    </main>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/rateAndReviews.js"></script>

<script>
    // Set initial rating
    document.addEventListener('DOMContentLoaded', function() {
        const rating = <?php echo $data['rating']; ?>;
        const stars = document.querySelectorAll('.star-rating i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            }
        });
    });

    document.querySelector('.a-a-a-btn-save').addEventListener('click', function(e) {
        e.preventDefault();
        let rating = document.getElementById('rating').value;
        let review = document.getElementById('review').value;

        if (rating === '0' || rating === '') {
            alert('Please select a rating');
            return;
        }

        if (review.trim() === '') {
            alert('Please enter your review');
            return;
        }

        if (confirm('Are you sure you want to update this review?')) {
            document.querySelector('.a-a-a-form').submit();
        }
    });
</script>
