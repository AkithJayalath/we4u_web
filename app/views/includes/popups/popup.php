<?php 
    $required_styles = [
        'components/popups/popup',
    ];
    echo loadCSS($required_styles);
?>

<script src="<?php echo APPROOT;?>/js/popup.js"></script>

    <!--popoup-->
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