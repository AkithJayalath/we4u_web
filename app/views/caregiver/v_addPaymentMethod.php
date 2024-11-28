
<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/addpaymentMethod.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    <div class="container">
        <div class="header">
            <h2>Add Payment Method</h2>
        </div>

        <div class="details">
            <form id="PaymentMethodForm" action="<?php echo URLROOT; ?>/caregivers/addPaymentMethod" method="POST">
                <div class="card-wrapper">
                    <div class="card">
                        <label><span class="required">*</span>Email</label>
                        <div class="content-box">
                            <input type="email" value="<?php echo $_SESSION['user_email']; ?>" readonly>
                        </div>
                    </div>
                
                    <div class="card">
                        <label><span class="required">*</span>Mobile Number</label>
                        <div class="content-box">
                            <input type="text" id="mobile_number" name="mobile_number" required>
                            <span class="error"><?php echo isset($data['mobile_number_err']) ? $data['mobile_number_err'] : ''; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-wrapper">
                    <div class="card">
                        <label><span class="required">*</span>Account Holders Name</label>
                        <div class="content-box">
                            <input type="text" id="account_holder_name" name="account_holder_name" required>
                            <span class="error"><?php echo isset($data['account_holder_name_err']) ? $data['account_holder_name_err'] : ''; ?></span>
                        </div>
                    </div>

                    <div class="card">
                        <label><span class="required">*</span>Bank Name</label>
                        <div class="content-box">
                            <input type="text" id="bank_name" name="bank_name" required>
                            <span class="error"><?php echo isset($data['bank_name_err']) ? $data['bank_name_err'] : ''; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-wrapper">
                    <div class="card">
                        <label><span class="required">*</span>Branch Name</label>
                        <div class="content-box">
                            <input type="text" id="branch_name" name="branch_name" required>
                            <span class="error"><?php echo isset($data['bank_name_err']) ? $data['bank_name_err'] : ''; ?></span>
                        </div>
                    </div>

                    <div class="card">
                        <label><span class="required">*</span>Account Number</label>
                        <div class="content-box">
                            <input type="text" id="account_number" name="account_number" required>
                            <span class="error"><?php echo isset($data['account_number_err']) ? $data['account_number_err'] : ''; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-wrapper">  
                    <div class="card">
                        <label>Payment type for short term (ST)</label>
                        <div class="content-box">
                            <select id="payment_type_st" name="payment_type_st">
                                <option value="No">Not Selected</option>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <label>Payment type for long term (LT)</label>
                        <div class="content-box">
                            <select id="payment_type_lt" name="payment_type_lt">
                                <option value="No">Not Selected</option>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-wrapper">
                    <div class="card">
                        <label>Advance Amount</label>
                        <div class="content-box">
                            <select id="advance_amount" name="advance_amount">
                                <option value="No">Not Selected</option>
                                <option value="5">5%</option>
                                <option value="10">10%</option>
                                <option value="20">20%</option>
                                <option value="50">50%</option>
                            </select>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button class="btn-delete" onclick="handleAdd(event)">Add Payment Method</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Confirmation Modal -->
    <?php if(!isset($data['paymentMethod']) || !$data['paymentMethod']): ?>
    <div id="addModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>CONFIRM DETAILS</h2>
            <p>You are about to add your payment method details!</p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="submitAdd()">Yes, Add</button>
                <button class="modal-cancel-btn" onclick="closeAddModal()">Cancel</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>


<script>
    function handleAdd(event) {
        event.preventDefault();
        const modal = document.getElementById("addModal");
        modal.style.display = "block";
        document.body.style.overflow = "hidden";
    }

    function submitAdd() {
        document.getElementById('PaymentMethodForm').submit();
        closeAddModal();
    }

    function closeAddModal() {
        const modal = document.getElementById("addModal");
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    // Outside click handler for modal
    window.addEventListener("click", function(event) {
        const addModal = document.getElementById("addModal");
        if (event.target === addModal) {
            closeAddModal();
        }
    });
</script>


