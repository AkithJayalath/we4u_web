// Select all stars
const stars = document.querySelectorAll('.star');
let rating = 0;

// Add mouseover event for hover effect
stars.forEach(star => {
    star.addEventListener('mouseover', function() {
        const ratingValue = this.getAttribute('data-value');
        highlightStars(ratingValue);
    });

    // Remove hover effect when mouse leaves
    star.addEventListener('mouseout', function() {
        highlightStars(rating);
    });

    // Add click event to save the rating
    star.addEventListener('click', function() {
        rating = this.getAttribute('data-value');
        highlightStars(rating);
        saveRating(rating);
    });
});

// Function to highlight stars based on hover or selection
function highlightStars(ratingValue) {
    stars.forEach(star => {
        if (star.getAttribute('data-value') <= ratingValue) {
            star.classList.add('selected');
        } else {
            star.classList.remove('selected');
        }
    });
}

// Save the selected rating (optional)
function saveRating(rating) {
    window.alert(`You gave me ${rating} stars. Thank you!!`);
}
