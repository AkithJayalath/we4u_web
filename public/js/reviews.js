function addReviewsForConsultant(reviews) {
    const container = document.querySelector(".reviews-section-content");
  
    // Generate HTML for all reviews
    const reviewsHTML = reviews
      .map(
        (review) => `
          <div class="review-item">
            <div class="review-avatar">
              <img src="${review.avatar}" alt="${review.username}'s avatar" />
            </div>
            <div class="review-content">
              <div class="review-header">
                ${review.name} <span class="review-username">@${review.username}</span>
              </div>
              <p class="review-text">${review.text}</p>
              <div class="review-date">${review.date}</div>
            </div>
          </div>
        `
      )
      .join("");
  
    // Inject the reviews into the container
    container.innerHTML = reviewsHTML;
  }
  
  // Example reviews data (to be fetched from a database)
  const reviewsData = [
    {
      name: "Jess Santiago",
      username: "username",
      avatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8ZmFjZXxlbnwwfHwwfHx8MA%3D%3D", 
      text: "A really good person",
      date: "22.03.2021",
    },
    {
      name: "Cali Huffman",
      username: "username",
      avatar: "https://media.istockphoto.com/id/1335941248/photo/shot-of-a-handsome-young-man-standing-against-a-grey-background.jpg?s=612x612&w=0&k=20&c=JSBpwVFm8vz23PZ44Rjn728NwmMtBa_DYL7qxrEWr38=", 
      text: "Easy to talk to and friendly",
      date: "22.03.2021",
    },
  ];
  
  // Add reviews dynamically
  addReviewsForConsultant(reviewsData);
  