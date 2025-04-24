<?php 
    $required_styles = [
        'moderator/payments',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
  
  <div class="request-container">
    <!-- Dashboard-style header -->
    <header class="request-header">
      <h1>Consultant Payments</h1>
      <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Search by name or bank..." />
      </div>
    </header>

    <!-- Main content area -->
    <div class="request-content">
      <div class="request-table-container">
        <h2>Payment Information</h2>
        <table class="request-table">
          <thead>
            <tr>
              <th>Request ID</th>
              <th>Consultant Name</th>
              <th>Bank</th>
              <th>Account Number</th>
              <th>Account Holder</th>
              <th>Phone Number</th>
              <th>Amount (Rs)</th>
              <th>WE4U Earnings</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($data['consultantPayments'])): ?>
              <?php foreach($data['consultantPayments'] as $payment): ?>
                <tr>
                  <td>#CT<?php echo $payment->consultant_request_id; ?></td>
                  <td><?php echo $payment->consultant_name; ?></td>
                  <td><?php echo $payment->bank_name; ?></td>
                  <td><?php echo $payment->account_number; ?></td>
                  <td><?php echo $payment->account_holder_name; ?></td>
                  <td><?php echo $payment->mobile_number; ?></td>
                  <td><?php echo number_format($payment->amount, 2); ?></td>
                  <td><?php echo number_format($payment->we4u_earn, 2); ?></td>
                  <td>
                    <?php if($payment->is_paid == 0 || $payment->is_paid === null): ?>
                      <!-- Show clickable button if payment is not paid -->
                      <form action="<?php echo URLROOT; ?>/moderator/markConsultantAsPaid" method="POST">
                        <input type="hidden" name="payment_id" value="<?php echo $payment->consultant_request_id; ?>">
                        <input type="hidden" name="consultant_id" value="<?php echo $payment->consultant_id; ?>">
                        <button type="submit" class="payment-action-btn">Mark as Paid</button>
                      </form>
                    <?php else: ?>
                      <!-- Show "Already Paid" indicator if payment is already paid -->
                      <span class="payment-status-paid">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M20 6L9 17l-5-5"></path>
                        </svg>
                        Already Paid
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="9" class="no-results-message">No payment records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</page-body-container>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const tableRows = document.querySelectorAll('.request-table tbody tr');
  
  searchInput.addEventListener('keyup', function() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    let visibleRows = 0;
    
    tableRows.forEach(row => {
      // Skip the "no results" row if it exists
      if(row.querySelector('.no-results-message')) {
        return;
      }
      
      // Get the consultant name (2nd column), bank name (3rd column), and account holder (5th column)
      const consultantName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
      const bankName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
      const accountHolder = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
      
      // Check if any of the fields contain the search term
      if (consultantName.includes(searchTerm) || 
          bankName.includes(searchTerm) || 
          accountHolder.includes(searchTerm)) {
        row.style.display = ''; // Show the row
        visibleRows++;
      } else {
        row.style.display = 'none'; // Hide the row
      }
    });
    
    // Show a message if no results are found
    const noResultsRow = document.querySelector('.no-results-message')?.parentElement;
    
    if (visibleRows === 0 && !noResultsRow) {
      const tbody = document.querySelector('.request-table tbody');
      const newRow = document.createElement('tr');
      newRow.innerHTML = '<td colspan="9" class="no-results-message">No matching payment records found.</td>';
      tbody.appendChild(newRow);
    } else if (visibleRows > 0 && noResultsRow && noResultsRow.querySelector('.no-results-message')) {
      noResultsRow.style.display = 'none';
    }
  });
});
</script>

<?php require APPROOT.'/views/includes/footer.php'?>