function addReviewsForConsultant(reviews) {
    const container = document.querySelector(".reviews-section-content");

    if (!reviews || reviews.length === 0) {
        container.innerHTML = "<p>No reviews yet</p>";
        return;
    }

    // Get latest 2 reviews
    const latestReviews = reviews.slice(0, 2);

    const reviewsHTML = latestReviews
        .map(
            (review) => `
      <div class="review-item">
          <div class="review-avatar">
                <img src="${
                    review.profile_picture
                        ? URLROOT +
                          "/images/profile_imgs/" +
                          review.profile_picture
                        : URLROOT + "/images/def_profile_pic.jpg"
                }" 
                   alt="Profile Image" class="pro-img"/>
          </div>
          <div class="review-content">
              <div class="review-header">
                  <h3 class="name">${review.username}</h3>
              </div>
              <p class="review-text">${review.review_text}</p>
              <div class="review-date">${formatDate(review.review_date)}</div>
          </div>
      </div>
  `
        )
        .join("");

    container.innerHTML = reviewsHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
}
