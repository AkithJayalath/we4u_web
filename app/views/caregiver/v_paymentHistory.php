
<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/caregiver/paymentHistory.css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="view-requests-m-c-r-container">

      <div class="view-requests-m-c-r-table-container">
            <h2>Payments History</h2>
        
        </div>

        <div class="income-summary">
            <div class="income-card">
                <span class="label">This Month</span>
                <span class="amount">LKR <?= number_format($data['income']['monthly'], 2) ?></span>
            </div>
            <div class="income-card">
                <span class="label">This Year</span>
                <span class="amount">LKR <?= number_format($data['income']['annual'], 2) ?></span>
            </div>
            <div class="income-card highlight">
                <span class="label">All-Time</span>
                <span class="amount">LKR <?= number_format($data['income']['total'], 2) ?></span>
            </div>
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
                                <td>LKR. <?php echo number_format($payment->amount * 0.92, 2); ?> </td>
                                <td class="status <?php echo $payment->is_paid == 1 ? 'Success' : 'Pending'; ?>">
                                    <?php echo $payment->is_paid == 1 ? 'Success' : 'Pending'; ?>
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

