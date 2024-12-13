document.addEventListener('DOMContentLoaded', function() {
    // Get all required elements
    const starContainer = document.querySelector('.star-rating');
    const stars = document.querySelectorAll('.star-rating i');
    const submitBtn = document.querySelector('.submit-btn');
    const reviewTextarea = document.querySelector('textarea');
    let selectedRating = 0;

    // Handle star hover effects
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            updateStars(index + 1);
        });
    });

    // Handle star container mouse leave
    starContainer.addEventListener('mouseleave', () => {
        updateStars(selectedRating);
    });

    // Handle star selection
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            selectedRating = index + 1;
            updateStars(selectedRating);
        });
    });

    // Function to update star colors
    function updateStars(count) {
        stars.forEach((star, index) => {
            star.style.color = index < count ? '#ffc107' : '#ddd';
        });
    }

    // Handle review submission
    submitBtn.addEventListener('click', () => {
        if (selectedRating === 0) {
            alert('Please select a rating');
            return;
        }

        if (!reviewTextarea.value.trim()) {
            alert('Please write a review');
            return;
        }

        const reviewData = {
            rating: selectedRating,
            review: reviewTextarea.value.trim()
        };

        // Log the submission data
        alert('Review submitted! \nRating: ' + reviewData.rating + ' stars\nReview: ' + reviewData.review);


        // Reset form after submission
        selectedRating = 0;
        reviewTextarea.value = '';
        updateStars(0);
    });
});
