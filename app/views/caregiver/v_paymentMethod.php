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
            <button>Payment History</button>
        </div>

        <div class="details">
            <div class="card">
                <lable>*Email</lable>
                <div class="content-box">
                    <p>Jerome Bell@gmail.com</p>
                </div>
            </div>

            <div class="card">
                <lable>*Mobile Number</lable>
                <div class="content-box">
                    <p>071-1234545</p>
                </div>
            </div>

            <div class="card">
                <lable>*Account Holders Name</lable>
                <div class="content-box">
                    <p>Tanuri Mandini</p>
                </div>
            </div>

            <div class="card">
                <lable>*Bank Name</lable>
                <div class="content-box">
                    <select id="branch" name="branch">
                        <option value="Matara">peoples bank</option>
                        <option value="Colombo">sampath bank</option>
                    </select>
                
                </div>
            </div>

            <div class="card">
                <lable>*Branch Name</lable>
                <div class="content-box">
                   <p>Matara city</p>
                </div>
            </div>

            <div class="card">
                <lable>*Account Number</lable>
                <div class="content-box">
                   <p>15489874684684</p>
                </div>
            </div>

            <div class="card">
            <lable>*Payment type for short term (ST)</label>
                    <div class="content-box">
                        <select id="payment-short-term" name="payment-short-term">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
            </div>

            <div class="card">
            <lable for="payment-long-term">*Payment type for long term (LT)</label>
                    <div class="content-box">
                        <select id="payment-short-term" name="payment-short-term">
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
                            <option value="5%">5%</option>
                            <option value="10%">10%</option>
                            <option value="20%">20%</option>
                            <option value="50%">50%</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    



</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';