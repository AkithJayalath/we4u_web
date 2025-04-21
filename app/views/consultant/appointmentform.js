function showConfirmation(event) {
    // Get the file input element
    var fileInput = document.getElementById("paymentSlip");

    // Check if a file has been selected
    if (fileInput.files.length === 0) {
        // Prevent form submission
        event.preventDefault();

        // Show an error message
        alert("Please upload the payment slip before submitting the form.");
    } else {
        // If a file is uploaded, show the confirmation message
        alert("We received your payment details. We'll let you know about your appointment within today.");
    }
}
