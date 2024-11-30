// These functions are for Accept
// Get the modal, accept button, and cancel button
const modal = document.getElementById("acceptModal");
const acceptButton = document.getElementById("btn-accept");

// Open the modal and blur the background
acceptButton.addEventListener("click", function () {
  modal.style.display = "block"; // Show the modal
  document.body.style.overflow = "hidden"; // Prevent scrolling
});

// Function to close the modal
function closeModal() {
  modal.style.display = "none"; // Hide the modal
  document.body.style.overflow = "auto"; // Re-enable scrolling
}

// Function for the "Yes, Sure" button
function showConfirmation() {
  closeModal();
  alert("Request Accepted Successfully!"); // Show confirmation message
  window.location.href = "<?php echo URLROOT; ?>/caregivers/request";
}

// Close the modal if the user clicks outside the modal content
window.addEventListener("click", function (event) {
  if (event.target === modal) {
    closeModal();
  }
});

// These are for Reject
