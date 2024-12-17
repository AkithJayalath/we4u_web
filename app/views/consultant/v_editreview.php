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
            <h2>Edit Your Review</h2>
            <form class="a-a-a-form" action="<?php echo URLROOT; ?>/consultant/editreview/<?php echo $data['review_id']; ?>" method="POST">
                <div class="a-a-a-form-group">
                    <label for="rating">Rating</label>
                    <div class="star-rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-solid fa-star <?php echo ($data['rating'] >= $i) ? 'active' : ''; ?>" 
                            data-rating="<?php echo $i; ?>"></i>
                        <?php endfor; ?>
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
                    ><?php echo htmlspecialchars($data['review_text']); ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['review_err']; ?></span>
                </div>

                <div class="a-a-a-form-buttons">
                    <button type="button" class="a-a-a-btn-delete" onclick="confirmDelete()">Delete Review</button>
                    <button type="button" class="a-a-a-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/rateandreview'">Cancel</button>
                    <button type="button" class="a-a-a-btn-save">Update Review</button>
                </div>
            </form>
        </div>
    </main>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/rateAndReviews.js"></script>

<script>
    // Form submission handler
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

    // Delete confirmation handler
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            window.location.href = '<?php echo URLROOT; ?>/consultant/deletereview/<?php echo $data['review_id']; ?>';
        }
    }

    // Initialize rating stars
    window.onload = function() {
        const currentRating = <?php echo $data['rating']; ?>;
        const stars = document.querySelectorAll('.star-rating i');
        stars.forEach(star => {
            if (star.dataset.rating <= currentRating) {
                star.classList.add('active');
            }
        });
    }
</script>

<?php require APPROOT.'/views/includes/footer.php'; ?>
