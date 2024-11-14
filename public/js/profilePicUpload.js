function triggerUpload() {
    document.getElementById('profileImageUpload').click();
}

function previewImage(event) {
    const file = event.target.files[0];
    const validImageTypes = ["image/jpeg", "image/png", "image/gif"];
    
    if (file && validImageTypes.includes(file.type)) {
        // Add active class to increase opacity when a valid image is selected
        const validationDiv = document.querySelector('.profile-image-validation');
        validationDiv.classList.add('active');

        const reader = new FileReader();
        
        reader.onload = function () {
            // Update the profile picture preview
            const placeholder = document.getElementById('profile_image_placeholder');
            placeholder.src = reader.result;
        };
        
        reader.readAsDataURL(file);
    } else {
        alert("Please upload a valid image file (JPEG, PNG, GIF).");
    }
}
