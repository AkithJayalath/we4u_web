<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/paymentMethod.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    <div class="container">
        <div class="header">
            <h2>Payment Method<h2>
            <i class="fa-solid fa-bell"></i>
        </div>

        <div class="btn-history">
            <button onClick="navigateToDetails()">Payment History</button>
        </div>

        <div class="details">
            <div class="card">
                <lable><span class="required">*</span>Email</lable>
                <div class="content-box">
                    <p>Jerome Bell@gmail.com</p>
                </div>
            </div>

            <div class="card">
                <lable><span class="required">*</span>Mobile Number</lable>
                <div class="content-box">
                    <p>071-1234545</p>
                </div>
            </div>

            <div class="card">
                <lable><span class="required">*</span>Account Holders Name</lable>
                <div class="content-box">
                    <p>Tanuri Mandini</p>
                </div>
            </div>

            <div class="card">
                <lable><span class="required">*</span>Bank Name</lable>
                <div class="content-box">
                    <select id="branch" name="branch">
                        <option value="no">Not Selected</option>
                        <option value="Matara">peoples bank</option>
                        <option value="Colombo">sampath bank</option>
                    </select>
                
                </div>
            </div>

            <div class="card">
                <lable><span class="required">*</span>Branch Name</lable>
                <div class="content-box">
                   <p>Matara city</p>
                </div>
            </div>

            <div class="card">
                <lable><span class="required">*</span>Account Number</lable>
                <div class="content-box">
                   <p>15489874684684</p>
                </div>
            </div>

            <div class="card">
            <lable>Payment type for short term (ST)</label>
                    <div class="content-box">
                        <select id="payment-short-term" name="payment-short-term">
                            <option value="no">Not Selected</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
            </div>

            <div class="card">
            <lable for="payment-long-term">Payment type for long term (LT)</label>
                    <div class="content-box">
                        <select id="payment-short-term" name="payment-short-term">
                            <option value="no">Not Selected</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
            </div>

            <div class="card">
                <lable>Advance Amount</lable>
                <div class="content-box">
                    <select id="advance" name="advance">
                            <option value="no">Not Selected</option>
                            <option value="5%">5%</option>
                            <option value="10%">10%</option>
                            <option value="20%">20%</option>
                            <option value="50%">50%</option>
                    </select>
                </div>
            </div>

            <div class="btn-container">
                <button class="btn-edit" onclick="toggleEdit()">Edit</button>
                <button class="btn-delete" onclick="deleteAll()">Delete All</button>
            </div>
        </div>

        
    </div> 

    <!--popoup-->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>ARE YOU SURE?</h2>
            <p>You are going to delete informations about payment method!</p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="showConfirmation()">Yes,Delete</button>
                <button class="modal-cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>
    



</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';?>




<script>
    // Function to toggle between view-only and edit mode
    function toggleEdit() {
        const isDisabled = document.getElementById('email-input').disabled;
        const inputs = document.querySelectorAll('#paymentForm input, #paymentForm select');
        
        if (isDisabled) {
            // Enable editing
            inputs.forEach(input => input.disabled = false);
            document.querySelector('.btn-edit').innerText = 'Save';
        } else {
            // Save changes (you can add AJAX call here for backend update)
            inputs.forEach(input => input.disabled = true);
            document.querySelector('.btn-edit').innerText = 'Edit';
        }
    }

    // Function to delete all data
   /* function deleteAll() {
        if (confirm('Are you sure you want to delete all data?')) {
            // Clear all text and input fields
            const textElements = document.querySelectorAll('#paymentForm p');
            const inputElements = document.querySelectorAll('#paymentForm input, #paymentForm select');

            textElements.forEach(element => element.innerText = '');
            inputElements.forEach(element => element.value = '');
            
            alert('All data has been deleted!');
            // Optional: Add backend call here to remove data from the database
        }
    }*/

    function navigateToDetails() {
        window.location.href = '<?php echo URLROOT; ?>/cgpayments/paymentHistory';
    }

    // Function to delete all data and trigger the modal
function deleteAll() {
    const modal = document.getElementById("deleteModal");
    modal.style.display = "block"; // Show the modal
    document.body.style.overflow = "hidden"; // Prevent scrolling

    // Handle "Yes, Delete" button click
    const confirmButton = document.querySelector(".modal-confirm-btn");
    confirmButton.onclick = function () {
        // Clear all text and input fields
        const textElements = document.querySelectorAll(".content-box p");
        const inputElements = document.querySelectorAll(".content-box select");

        textElements.forEach(element => (element.innerText = ""));
        inputElements.forEach(element => (element.value = "no"));

        closeModal(); // Close the modal
        alert("All data has been deleted!");
    };
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById("deleteModal");
    modal.style.display = "none"; // Hide the modal
    document.body.style.overflow = "auto"; // Enable scrolling
}

// Close the modal if the user clicks outside of it
window.addEventListener("click", function (event) {
    const modal = document.getElementById("deleteModal");
    if (event.target === modal) {
        closeModal();
    }
});

</script>


