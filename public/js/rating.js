function addRatingsAndReviews(data) {
    // Target the container
    const container = document.querySelector(".rating-section-content");
  
    // Generate dynamic bar widths
    const barsHTML = data.ratings
      .map(
        (rating) => `
          <div class="rating-bar">
            <span>${rating.stars}</span>
            <div class="bar">
              <div class="filled" style="width: ${rating.percentage}%;"></div>
            </div>
          </div>
        `
      )
      .join("");
  
    // Create the full HTML structure
    const ratingReviewsHTML = `
      <div class="rating-reviews">
        <div class="rating-summary">
          <div class="rating-score">
            <h2>${data.averageRating}</h2>
            <div class="stars">${"★".repeat(Math.floor(data.averageRating)) + "☆".repeat(5 - Math.floor(data.averageRating))}</div>
            <p>${data.totalReviews} reviews</p>
          </div>
          <p>${data.reviewDescription}</p>
        </div>
        <div class="rating-breakdown">
          ${barsHTML}
        </div>
      </div>
    `;
  
    // Append the section dynamically
    container.insertAdjacentHTML("beforeend", ratingReviewsHTML);
  }
  
  // Example data from API or backend
  const ratingsData = {
    averageRating: 4.5,
    totalReviews: "225",
    reviewDescription: "Rating and reviews are verified and are from people who use the service",
    ratings: [
      { stars: 5, percentage: 90 },
      { stars: 4, percentage: 70 },
      { stars: 3, percentage: 30 },
      { stars: 2, percentage: 10 },
      { stars: 1, percentage: 5 },
    ],
  };
  
  // Add dynamically with data
  addRatingsAndReviews(ratingsData);
  