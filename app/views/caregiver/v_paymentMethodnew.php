<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/paymentMethodnew.css">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>

    <div class="view-requests-m-c-r-container">
        <div class="view-requests-m-c-r-table-container">
            <h2>Payment Method</h2>
            
            <!-- View Mode -->
            <div id="viewMode" class="<?php echo $data['edit_mode'] ? 'hidden' : ''; ?>">
                <div class="form-section">
                    <h3>Contact Information</h3>
                    <div class="display-group">
                        <label>Email Address</label>
                        <input type="email" value=<?php echo $data['email']; ?> readonly>
                    </div>
                    <div class="display-group">
                        <label>Mobile Number</label>
                        <p><?php echo $data['mobile_number']; ?></p>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Bank Details</h3>
                    <div class="display-group">
                        <label>Account Holder</label>
                        <p><?php echo $data['account_holder_name']; ?></p>
                    </div>
                    <div class="display-group">
                        <label>Bank Name</label>
                        <p><?php echo $data['bank_name']; ?></p>
                    </div>
                    <div class="display-group">
                        <label>Branch Name</label>
                        <p><?php echo $data['branch_name']; ?></p>
                    </div>
                    <div class="display-group">
                        <label>Account Number</label>
                        <p><?php echo $data['account_number']; ?></p>
                    </div>
                    
                   
                </div>

                <div class="editbutton">
                    <button onclick="enableEdit()" class="edit">Edit Payment Method</button>
                    <form action="<?php echo URLROOT; ?>/caregivers/deletePayMethod" method="post" style="display:inline;">
                        <button type="submit" class="delete">Delete Details</button>
                    </form>

                </div>
            </div>

            <!-- Edit Mode -->
            <form id="editMode" action="<?php echo URLROOT; ?>/caregivers/updatePayMethod" method="post" class="<?php echo !$data['edit_mode'] ? 'hidden' : ''; ?>">
                <div class="form-section">
                    <h3>Contact Information</h3>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number <span class="required">*</span></label>
                        <input type="tel" id="mobile_number" name="mobile_number" value="<?php echo $data['mobile_number']; ?>">
                        <span class="invalid-feedback"><?php echo $data['mobile_err']; ?></span>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Bank Details</h3>
                    <div class="form-group">
                        <label for="account_holder">Account Holder <span class="required">*</span></label>
                        <input type="text" id="account_holder_name" name="account_holder_name" value="<?php echo $data['account_holder_name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['account_holder_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="bank_name">Bank Name <span class="required">*</span></label>
                        <input type="text" id="bank_name" name="bank_name" value="<?php echo $data['bank_name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['bank_name_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="branch_name">Branch Name <span class="required">*</span></label>
                        <input type="text" id="branch_name" name="branch_name" value="<?php echo $data['branch_name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['branch_name_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="account_number">Account Number <span class="required">*</span></label>
                        <input type="text" id="account_number" name="account_number" value="<?php echo $data['account_number']; ?>">
                        <span class="invalid-feedback"><?php echo $data['account_number_err']; ?></span>
                        </div>
                    
                    
                </div>

                <div class="form-actions">
                    <button type="button" class="save" onclick="handleEdit()">Save</button>
                    <button type="button" onclick="cancelEdit()" class="cancel">Cancel</button>

                </div>
            </form>
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
</page-body-container>

<script>
function enableEdit() {
    document.getElementById('viewMode').classList.add('hidden');
    document.getElementById('editMode').classList.remove('hidden');
}

function cancelEdit() {
    document.getElementById('editMode').classList.add('hidden');
    document.getElementById('viewMode').classList.remove('hidden');
}

function submitAdd() {
        document.getElementById('paymentMethodForm').submit();
        closeAddModal();
    }

    function closeEditModal() {
        const modal = document.getElementById("editModal");
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    function handleEdit() {
    const modal = document.getElementById("editModal");
    modal.style.display = "block";
    document.body.style.overflow = "hidden";
}


function submitEdit() {
    document.getElementById('editMode').submit();
    closeEditModal();
}
</script>

<?php require APPROOT.'/views/includes/footer.php';?>
