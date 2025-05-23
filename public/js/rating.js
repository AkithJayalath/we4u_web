function addRatingsAndReviews(data) {
    const container = document.querySelector(".rating-section-content");

    const totalReviews = data.reviews.length;
    const ratingCounts = { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 };

    // Count each rating
    data.reviews.forEach((review) => {
        const rating = Math.floor(review.rating);
        if (rating >= 1 && rating <= 5) {
            ratingCounts[rating]++;
        }
    });

    // Calculate percentages
    const ratingBars = Object.entries(ratingCounts)
        .sort((a, b) => b[0] - a[0]) // Sort by rating (5 to 1)
        .map(([rating, count]) => {
            const percentage =
                totalReviews > 0 ? (count / totalReviews) * 100 : 0;
            return `
            <div class="rating-bar">
                <span>${rating}</span>
                <div class="bar">
                    <div class="filled" style="width: ${percentage}%;"></div>
                </div>
            </div>
        `;
        })
        .join("");

    const ratingHTML = `
      <div class="rating-reviews">
          <div class="rating-summary">
              <div class="rating-score">
                <h2>${parseFloat(data.rating).toFixed(1)}</h2>  
                  <div class="stars">
                      ${generateStars(data.rating)}
                  </div>
                  <p>${totalReviews} reviews</p>
                  
                  <p class="rating-description">Rating and reviews are verified and are from people who use the service</p>  

              </div>
          </div>
          <div class="rating-breakdown">
                ${ratingBars}
            </div>
          </div>
  `;

    container.innerHTML = ratingHTML;
}

function generateStars(rating) {
    const fullStars = Math.floor(rating); // Number of full stars
    const hasHalfStar = rating % 1 >= 0.5; // Check if there's a half-star
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0); // Remaining empty stars

    return (
        `${'<i class="fas fa-star active"></i>'.repeat(fullStars)}` +
        `${hasHalfStar ? '<i class="fas fa-star-half-alt active"></i>' : ""}` +
        `${'<i class="far fa-star"></i>'.repeat(emptyStars)}`
    );
}
