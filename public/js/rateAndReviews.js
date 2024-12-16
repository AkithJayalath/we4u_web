document.addEventListener('DOMContentLoaded', function() {
    const starContainer = document.querySelector('.star-rating');
    const stars = document.querySelectorAll('.star-rating i');
    const ratingInput = document.getElementById('rating');
    const reviewForm = document.querySelector('.a-a-a-form');
    const reviewTextarea = document.getElementById('review');
    let selectedRating = parseInt(ratingInput.value) || 0;

    // Initialize stars based on existing rating
    updateStars(selectedRating);

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
            ratingInput.value = selectedRating;
            updateStars(selectedRating);
        });
    });

    // Function to update star colors
    function updateStars(count) {
        stars.forEach((star, index) => {
            if (index < count) {
                star.classList.add('active');
                star.style.color = '#ffc107';
            } else {
                star.classList.remove('active');
                star.style.color = '#ddd';
            }
        });
    }

    // Handle form submission
    reviewForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (selectedRating === 0) {
            alert('Please select a rating');
            return;
        }

        if (!reviewTextarea.value.trim()) {
            alert('Please write a review');
            return;
        }

        if (confirm('Are you sure you want to submit this review?')) {
            reviewForm.submit();
        }
    });
});
