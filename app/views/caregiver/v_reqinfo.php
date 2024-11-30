<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>


<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/viewreqinfo.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">


<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="container">
        <div class="header">
            <h2>Request Information</h2>
            <p class="id">Request ID #12345</p>
            <span class="sts-pending">Pending</span>
            <img src="/we4u/public/images/def_profile_pic.jpg" alt="Profile Image" class="pro-img"/>

        </div>

        <div class="request-info">
            <div>
                <lable>Care Seeker's Name & ID</lable>
                <div class="content-box">
                    <p class="name">Jerome Bell</p>
                    <p class="id">CS12345</p>
                </div>
            </div>

            <div>
                <lable>Location</lable>
                <div class="content-box">
                    <p class="location">12345, Colombo</p>
                </div>
            </div>

            <div>
                <lable>Care Seeker's Contact Details</lable>
                <div class="content-box">
                    <p class="contact"><i class="fa-solid fa-phone">&nbsp;</i>0712345678</p>
                    <p class="email"><i class="fa-solid fa-envelope">&nbsp;</i>jerome@gmail.com</p>
                </div>
            </div>

            <div>
                <lable>Requested Time Slot</lable>
                <div class="content-box">
                    <p class="date"><i class="fa-regular fa-calendar-days">&nbsp;</i>From:18th Jan To:19th Jan</p>
                    <p class="time"><i class="fa-solid fa-clock"></i>&nbsp;</i>From:8:00 AM To:6:00 PM</p>
                </div>
            </div>

            <div>
                <lable>Requested Services</lable>
                <div class="content-box">
                    <details>
                        <summary class="dropdown">Care Giving<i class="fa-solid fa-chevron-down"></i></summary>
                        
                        <ul>
                            <li>Assistance with daily living activities</li>
                            <li>Medication reminders</li>
                            <li>Contact with consultant</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            <li>Meal Preparation</li>
                            
                        </ul>
                    </details>
                </div>
            </div>

            
            <div>
                <lable>Additional Notes </lable>
                <div class="content-box">
                    <button class="collapsible-btn">
                        <span>View Notes</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="col-content">
                        <p class="notes">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fuga ipsam voluptates cum aliquid ea veniam at earum architecto quis, aliquam hic iste facilis? Eligendi quam explicabo vero accusantium! Vel, placeat?</p>
                    </div>
                </div>
            </div>
           

            <div>
                <lable>Payment Details</lable>
                <div class="content-box">
                    <details>
                        <summary class="dropdown"><strong>Payment Method:</strong> Cash<i class="fa-solid fa-chevron-down"></i></summary>
                        
                        <ul>
                            <li><strong>Daily Fee:</strong> Rs.2000</li>
                            <li><strong>Number of Days:</strong> 2</li>
                            <li><strong>Total Amount:</strong> Rs.4000</li>
                            <li><strong>Payment Status:</strong> 10% paid</li>
                        </ul>
                    </details>
                </div>
            </div>

            <div>
                <lable>Update Your calendar</lable>
                <div class="content-box">
                    <button class="btn-update"><i class="fa-regular fa-calendar-days"></i>&nbsp;Update Calendar</button>
                </div>
            </div>

           
            <div class="btn-container">
                <button id="btn-accept" class="btn-accept">Accept</button>
                <button class="btn-reject">Reject</button>
            </div>

        </div>
        
    </div>

    <?php require APPROOT.'/views/includes/popups/popup.php'; ?>


    <!--popoup-->
    <!-- <div id="acceptModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>ARE YOU SURE?</h2>
            <p>you will not be able to make any changes!</p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="showConfirmation()">Yes,Sure</button>
                <button class="modal-cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div> -->

    <div id="rejecttModal" class="r-modal">
        <div class="r-modal-content">
            <div class="modal-header">
                <img src="/we4u/public/images/Reminders-pana.png" class="modal-img"/>
                <h2>Request Denied!</h2>
            </div>
            <p>Could you let us know why you declined?</p>
            <textarea id="rejectReason" placeholder="Provide your reason (optional)" rows="4" cols="50"></textarea>

            <div class="modal-buttons">
                <button class="btn-submit" onclick="submitRejection()">Submit</button>
                <button class="btn-cancel" onclick="closeRejectModal()">Reject</button>
            </div>
        </div>
    </div>



</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';?>

<script>
 
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


        // Get the reject modal and its buttons
    const rejectModal = document.getElementById("rejecttModal");
    const rejectButton = document.querySelector(".btn-reject"); // Reject button that opens the modal
    const cancelRejectButton = document.querySelector(".btn-cancel"); // Cancel button in the modal
    const submitButton = document.querySelector(".btn-submit"); // Submit button in the modal

    // Open the Reject Modal
    rejectButton.addEventListener("click", function () {
        rejectModal.style.display = "block"; // Show the modal
        document.body.style.overflow = "hidden"; // Prevent background scrolling
    });

    // Function to close the Reject Modal
    function closeRejectModal() {
        window.location.href = "<?php echo URLROOT; ?>/caregivers/request"; 
    }

    // Add event listener for the Cancel button
    cancelRejectButton.addEventListener("click", closeRejectModal);

    // Function for the Submit button
    function submitRejection() {
        const reason = document.getElementById("rejectReason").value.trim(); // Get the reason entered
        //closeRejectModal(); // Close the modal

        if (reason) {
            alert(`Request Rejected. Reason: ${reason}`);
            closeRejectModal();
            window.location.href = "<?php echo URLROOT; ?>/caregivers/request"; 
        } else {
            alert(`Please provide a reason before submitting!`); 
            
        }

        
    }

    // Close the modal if the user clicks outside the modal content
    window.addEventListener("click", function (event) {
        if (event.target === rejectModal) {
            closeRejectModal(); // Close modal when clicking outside it
        }
    });




    document.querySelectorAll('.collapsible-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Toggle the 'active' class to show/hide the content
        this.classList.toggle('active');

        // Toggle the content visibility
        const content = this.nextElementSibling;
        if (content.style.display === 'block') {
            content.style.display = 'none';
        } else {
            content.style.display = 'block';
        }
    });
});

 

</script>

