<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/paymentHistory.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    <div class="container">
        <div class="header">
                <h2>Payment History<h2>
                <i class="fa-solid fa-bell"></i>
        </div>

        <div class="btn-Method">
            <button onclick="navigateToDetails()">Payment Method</button>
        </div>

        <div class="details">
            <table>
                <thead>
                    <tr>
                    <th>PAYMENT ID</th>
                    <th>DATE</th>
                    <th>PAYMENT FROM</th>
                    <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-id">#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td class="p-id">#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg"alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status pending">Pending</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status failed">Failed</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg"alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg"alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    <tr>
                        <td>#AHGA68</td>
                        <td>10/12/2023</td>
                        <td>
                            <div class="user-info">
                            <img src="/we4u/public/images/def_profile_pic.jpg" alt="profile">
                            <span>Jordan Stevenson</span>
                            </div>
                        </td>
                        <td class="status success">Success</td>
                    </tr>

                    
            </table>
        </div>
    </div>

</page-body-container>
<?php require APPROOT.'/views/includes/footer.php';?>

<script>
function navigateToDetails() {
    window.location.href = '<?php echo URLROOT; ?>/cgpayments/paymentMethod';
}
</script>