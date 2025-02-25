<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/addpaymentMethod.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    <div class="container">
        <div class="header">
            <h2>Payment Method</h2> 
        </div>

        <div class="btn-history">
            <button onClick="navigateToDetails()">Payment History</button>
        </div>

        <div class="details">
            <form id="paymentMethodForm" action="<?php echo URLROOT; ?>/caregivers/<?php echo isset($data['paymentMethod']) ? 'updatePaymentMethod' : ''; ?>" method="POST">
                <div class="card-wrapper">
                    <div class="card">
                            <label><span class="required">*</span>Email</label>
                            <div class="content-box">
                                <input type="email" value="<?php echo $data['email']; ?>" readonly>
                            </div>
                        </div>
                
                    <div class="card">
                        <label><span class="required">*</span>Mobile Number</label>
                        <div class="content-box">
                            <input type="text" id="mobile_number" name="mobile_number" value="<?php echo isset($data['paymentMethod']->mobile_number) ? $data['paymentMethod']->mobile_number : ''; ?> "
                            <?php echo (!isset($data['paymentMethod']) || !$data['paymentMethod']) ? 'readonly' : ''; ?>>
                            <span class="error"><?php echo isset($data['mobile_number_err']) ? $data['mobile_number_err'] : ''; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-wrapper">
                    <div class="card">
                        <label><span class="required">*</span>Account Holders Name</label>
                        <div class="content-box">
                            <input type="text" id="account_holder_name" name="account_holder_name" value="<?php echo isset($data['paymentMethod']->account_holder_name) ? $data['paymentMethod']->account_holder_name : ''; ?>"
                            <?php echo (!isset($data['paymentMethod']) || !$data['paymentMethod']) ? 'readonly' : ''; ?>
                            >
                            <span class="error"><?php echo isset($data['account_holder_name_err']) ? $data['account_holder_name_err'] : ''; ?></span>
                        </div>
                    </div>

                    <div class="card">
                        <label><span class="required">*</span>Bank Name</label>
                        <div class="content-box">
                            <input type="text" id="bank_name" name="bank_name" value="<?php echo isset($data['paymentMethod']->bank_name) ? $data['paymentMethod']->bank_name : ''; ?>"
                            <?php echo (!isset($data['paymentMethod']) || !$data['paymentMethod']) ? 'readonly' : ''; ?>
                            >
                            <span class="error"><?php echo isset($data['bank_name_err']) ? $data['bank_name_err'] : ''; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-wrapper">
                    <div class="card">
                        <label><span class="required">*</span>Branch Name</label>
                        <div class="content-box">
                            <input type="text" id="branch_name" name="branch_name" value="<?php echo isset($data['paymentMethod']->branch_name) ? $data['paymentMethod']->branch_name : ''; ?>"
                            <?php echo (!isset($data['paymentMethod']) || !$data['paymentMethod']) ? 'readonly' : ''; ?>
                            >
                            <span class="error"><?php echo isset($data['branch_name_err']) ? $data['branch_name_err'] : ''; ?></span>
                        </div>
                    </div>

                    <div class="card"> 
                        <label><span class="required">*</span>Account Number</label>
                        <div class="content-box">
                            <input type="text" id="account_number" name="account_number" value="<?php echo isset($data['paymentMethod']->account_number) ? $data['paymentMethod']->account_number : ''; ?>"
                            <?php echo (!isset($data['paymentMethod']) || !$data['paymentMethod']) ? 'readonly' : ''; ?>
                            >
                            <span class="error"><?php echo isset($data['account_number_err']) ? $data['account_number_err'] : ''; ?></span>
                    </div>
                </div>
            </div>

        
        <div class="card-wrapper">  
                <div class="card">
                    <label>Payment type for short term (ST)</label>
                    <div class="content-box">
                        <select id="payment_type_st" name="payment_type_st">
                            <option value="no" <?php echo (isset($data['paymentMethod']->payment_type_st) && $data['paymentMethod']->payment_type_st == 'no') ? 'selected' : ''; ?>>Not Selected</option>
                            <option value="daily" <?php echo (isset($data['paymentMethod']->payment_type_st) && $data['paymentMethod']->payment_type_st == 'Daily') ? 'selected' : ''; ?>>Daily</option>
                            <option value="Weekly" <?php echo (isset($data['paymentMethod']->payment_type_st) && $data['paymentMethod']->payment_type_st == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                            <option value="Monthly" <?php echo (isset($data['paymentMethod']->payment_type_st) && $data['paymentMethod']->payment_type_st == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                        </select>
                    </div>
                </div>

                <div class="card">
                    <label>Payment type for long term (LT)</label>
                    <div class="content-box">
                        <select id="payment_type_lt" name="payment_type_lt">
                            <option value="no" <?php echo (isset($data['paymentMethod']->payment_type_lt) && $data['paymentMethod']->payment_type_lt == 'no') ? 'selected' : ''; ?>>Not Selected</option>
                            <option value="daily" <?php echo (isset($data['paymentMethod']->payment_type_lt) && $data['paymentMethod']->payment_type_lt == 'daily') ? 'selected' : ''; ?>>Daily</option>
                            <option value="weekly" <?php echo (isset($data['paymentMethod']->payment_type_lt) && $data['paymentMethod']->payment_type_lt == 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                            <option value="monthly" <?php echo (isset($data['paymentMethod']->payment_type_lt) && $data['paymentMethod']->payment_type_lt == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="card-wrapper">
                <div class="card">
                    <label>Advance Amount</label>
                    <div class="content-box">
                        <select id="advance_amount" name="advance_amount">
                            <option value="no" <?php echo (isset($data['paymentMethod']->advance_amount) && $data['paymentMethod']->advance_amount == 'no') ? 'selected' : ''; ?>>Not Selected</option>
                            <option value="5%" <?php echo (isset($data['paymentMethod']->advance_amount) && $data['paymentMethod']->advance_amount == '5') ? 'selected' : ''; ?>>5%</option>
                            <option value="10%" <?php echo (isset($data['paymentMethod']->advance_amount) && $data['paymentMethod']->advance_amount == '10') ? 'selected' : ''; ?>>10%</option>
                            <option value="20%" <?php echo (isset($data['paymentMethod']->advance_amount) && $data['paymentMethod']->advance_amount == '20') ? 'selected' : ''; ?>>20%</option>
                            <option value="50%" <?php echo (isset($data['paymentMethod']->advance_amount) && $data['paymentMethod']->advance_amount == '50') ? 'selected' : ''; ?>>50%</option>
                        </select>
                    </div>
                </div>

                <div class="btn-container">
                    <div class="btn-container">
                        <?php if(isset($data['paymentMethod']) && $data['paymentMethod']): ?>
                            <button class="btn-edit" onclick="handleEdit(event)">Edit</button>
                            <button class="btn-delete" onclick="deleteAll(event)">Delete All</button>
                        <?php endif; ?>
                        <?php if(!isset($data['paymentMethod']) || !$data['paymentMethod']): ?>
                            <!-- <button class="btn-delete" onclick="window.location.href='<?php echo URLROOT; ?>/caregivers/addPaymentMethod'">Add Payment Method</button> -->
                            <a href="<?php echo URLROOT; ?>/caregivers/addPaymentMethod">
                                <button type="button" class="btn-delete">Add Payment Method</button>
                            </a>

                        <?php endif; ?>
                    </div>
                </div>


            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>ARE YOU SURE?</h2>
            <p>You are going to delete informations about payment method!</p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="submitDelete()">Yes, Delete</button>
                <button class="modal-cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>CONFIRM UPDATE</h2>
            <p>You are about to update your payment method details!</p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="submitEdit()">Yes, Update</button>
                <button class="modal-cancel-btn" onclick="closeEditModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
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
    </div>

</page-body-container>

<?php require APPROOT.'/views/includes/footer.php';?>


<script>
    function navigateToAdd() {
    window.location.assign('<?php echo URLROOT; ?>/caregivers/addPaymentMethod');
    }


    function handleEdit(event) {
        event.preventDefault();
        const modal = document.getElementById("editModal");
        modal.style.display = "block";
        document.body.style.overflow = "hidden";
    }

    function handleAdd(event) {
        event.preventDefault();
        const modal = document.getElementById("addModal");
        modal.style.display = "block";
        document.body.style.overflow = "hidden";
    }

    function submitAdd() {
        document.getElementById('paymentMethodForm').submit();
        closeAddModal();
    }

    function closeAddModal() {
        const modal = document.getElementById("addModal");
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    function closeEditModal() {
        const modal = document.getElementById("editModal");
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    function submitEdit() {
        document.getElementById('paymentMethodForm').submit();
        closeEditModal();
    }

    function navigateToDetails() {
        window.location.href = '<?php echo URLROOT; ?>/caregivers/paymentHistory';
    }

    function submitDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo URLROOT; ?>/caregivers/deletePaymentMethod';
        document.body.appendChild(form);
        form.submit();
        closeModal();
    }

    function closeModal() {
        const modal = document.getElementById("deleteModal");
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    function deleteAll(event) {
        event.preventDefault();
        const modal = document.getElementById("deleteModal");
        modal.style.display = "block";
        document.body.style.overflow = "hidden";
    }

    // Outside click handlers for modals
    window.addEventListener("click", function(event) {
        const addModal = document.getElementById("addModal");
        const editModal = document.getElementById("editModal");
        const deleteModal = document.getElementById("deleteModal");

        if (event.target === addModal) {
            closeAddModal();
        }
        if (event.target === editModal) {
            closeEditModal();
        }
        if (event.target === deleteModal) {
            closeModal();
        }
    });
</script>
