<?php 
    $required_styles = [
        'components/popups/popup',
    ];
    echo loadCSS($required_styles);
?>

<script src="<?php echo APPROOT;?>/js/popup.js"></script>

    <!--accept popoup-->
    <div id="acceptModal" class="modal">
        <div class="modal-content">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>ARE YOU SURE?</h2>
            <p>you will not be able to make any changes!</p>
            <div class="modal-buttons">
                <button class="modal-confirm-btn" onclick="showConfirmation()">Yes,Sure</button>
                <button class="modal-cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- reject popup  -->
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