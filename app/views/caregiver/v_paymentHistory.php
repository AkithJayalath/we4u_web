<<<<<<< HEAD
<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/paymentHistory.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="container">
        <div class="header">
            <h2>Payment History</h2>
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
                        <th>AMOUNT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['payments'])): ?>
                        <?php foreach ($data['payments'] as $payment): ?>
                            <tr>
                                <td class="p-id">#<?php echo htmlspecialchars($payment->payment_id); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($payment->created_at)); ?></td>
                                <td>
                                    <div class="user-info">
                                    <img src="<?php echo !empty($payment->profile_picture) ? URLROOT . '/images/profile_imgs/'. $payment->profile_picture : URLROOT .'/images/def_profile_pic.jpg'; ?>" alt="Profile Image" class="pro-img"/>
                                        <span><?php echo $payment->username; ?></span>
                                    </div>
                                </td>
                                <td>LKR. <?php echo number_format($payment->amount, 2); ?> </td>
                                <td class="status <?php echo $payment->is_refunded == 1 ? 'Refunded' : 'Success'; ?>">
                                    <?php echo $payment->is_refunded == 1 ? 'Refunded' : 'Success'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No payment history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>

<script>
function navigateToDetails() {
    window.location.href = '<?php echo URLROOT; ?>/caregivers/paymentMethod';
}
</script>
=======
<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php';?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/paymentHistory.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php';?>
    <div class="container">
        <div class="header">
                <h2>Payment History<h2>
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
                        <td class="status pending">Pending</td>
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
    window.location.href = '<?php echo URLROOT; ?>/caregivers/paymentMethod';
}
</script>
>>>>>>> c7291afc54e61c0fd4c872653c5e5fbe32b2a8b5
