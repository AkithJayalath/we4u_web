function renderRating(ratingValue) {
    const maxStars = 5; // Maximum number of stars
    const ratingStarsContainer = document.getElementById('rating-stars');
    ratingStarsContainer.innerHTML = ''; // Clear previous stars

    // Generate stars based on the rating value
    for (let i = 1; i <= maxStars; i++) {
        const star = document.createElement('i');

        // Full star
        if (i <= Math.floor(ratingValue)) {
            star.className = 'fas fa-star';
        }
        // Half star
        else if (i === Math.ceil(ratingValue) && !Number.isInteger(ratingValue)) {
            star.className = 'fas fa-star-half-alt';
        }
        // Empty star
        else {
            star.className = 'far fa-star';
        }

        // Append the star to the container
        ratingStarsContainer.appendChild(star);
    }
}

// Example usage: Render a 4.5-star rating
renderRating(4.5);
