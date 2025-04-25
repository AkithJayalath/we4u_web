<?php 
    $required_styles = [
        'admin/admin_dashboard',
        'admin/payments',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</div>
  <page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
  <div class="ad-dashboard-wrapper">
  <div class="ad-dashboard-header">
          <h1>Payment Overview</h1>
      </div>
      <main class="ad-dashboard-main-content">
          <!-- Stats cards section -->
        <div class="ad-dashboard-stats-container">
            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Total User Earnings</h3>
                        <div class="ad-dashboard-stat-value">Rs. <?php echo number_format($data['totalUserEarnings']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-green">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Total WE4U Earnings</h3>
                        <div class="ad-dashboard-stat-value">Rs. <?php echo number_format($data['totalWE4UEarnings']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-blue">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Last Month User Earnings</h3>
                        <div class="ad-dashboard-stat-value">Rs. <?php echo number_format($data['lastMonthUserEarnings']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-green">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Last Month WE4U Earnings</h3>
                        <div class="ad-dashboard-stat-value">Rs. <?php echo number_format($data['lastMonthWE4UEarnings']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-blue">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Fine Collected</h3>
                        <div class="ad-dashboard-stat-value">Rs. <?php echo number_format($data['totalFineAmount']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-red">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>


          <!-- Filters section -->
          <div class="ad-dashboard-filters">
              <div class="ad-dashboard-table-controls">
                  <select id="payment-filter" class="ad-dashboard-filter-select">
                      <option value="">All Services</option>
                      <option value="Caregiving">Caregiver Service</option>
                      <option value="Consultation">Consultation</option>
                  </select>
                    <!-- Search wrapper -->
                    <div class="ad-dashboard-search-wrapper">
                        <i data-lucide="search" class="ad-dashboard-search-icon"></i>
                        <input type="search" id="payment-search" class="ad-dashboard-job-search" placeholder="Search by name" />
                    </div>
              </div>
              <div class="ad-dashboard-action-group">
                  <button class="ad-dashboard-btn-export" id="export-payments">
                      <i data-lucide="download" class="ad-dashboard-btn-icon"></i>
                      Export
                  </button>
                  <button class="ad-dashboard-btn-add">
                      <i data-lucide="refresh-cw" class="ad-dashboard-btn-icon"></i>
                      Refresh Data
                  </button>
              </div>
          </div>

          <!-- Table section with horizontal scroll -->
          <div class="ad-dashboard-table-container">
              <table class="ad-dashboard-jobs-table">
                  <thead>
                      <tr>
                          <th>REQUEST ID</th>
                          <th>DATE</th>
                          <th>SERVICE PROVIDER</th>
                          <th>TOTAL PAYMENT</th>
                          <th>WE4U COMMISSION</th>
                          <th>SERVICE TYPE</th>
                          <th>STATUS</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if(empty($data['payments'])): ?>
                          <tr>
                              <td colspan="7" class="text-center">No payment records found</td>
                          </tr>
                      <?php else: ?>
                          <?php foreach($data['payments'] as $payment): ?>
                          <tr>
                              <td><?php echo ($payment->service_type == 'Caregiving' ? 'CG' : 'CO') . $payment->request_id; ?></td>
                              <td><?php echo date('j M Y', strtotime($payment->created_at)); ?></td>
                              <td><?php echo $payment->provider_name; ?></td>
                              <td>Rs. <?php echo number_format($payment->payment_details); ?></td>
                              <td>Rs. <?php echo number_format($payment->we4u_commission); ?></td>
                              <td><?php echo $payment->service_type; ?></td>
                              <td>
                                  <?php 
                                  $statusClass = '';
                                  switch(strtolower($payment->status)) {
                                      case 'completed':
                                          $statusClass = 'ad-dashboard-active';
                                          break;
                                      case 'pending':
                                          $statusClass = 'ad-dashboard-pending';
                                          break;
                                      case 'cancelled':
                                      case 'rejected':
                                          $statusClass = 'ad-dashboard-inactive';
                                          break;
                                      case 'accepted':
                                          $statusClass = 'ad-dashboard-active';
                                          break;
                                  }
                                  ?>
                                  <span class="ad-dashboard-status <?php echo $statusClass; ?>"><?php echo ucfirst($payment->status); ?></span>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      <?php endif; ?>
                  </tbody>
              </table>

              <!-- No results message -->
              <div id="no-results-message" style="display: none; text-align: center; padding: 20px; color: #666;">
                  No payments found matching your search criteria.
              </div>
          </div>
      </main>
  </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const paymentSearch = document.getElementById('payment-search');
    const paymentFilter = document.getElementById('payment-filter');
    const tableBody = document.querySelector('.ad-dashboard-jobs-table tbody');
    const noResultsMessage = document.getElementById('no-results-message');
    
    // Add event listener for search input
    paymentSearch.addEventListener('input', filterPayments);
    
    // Add event listener for payment filter
    paymentFilter.addEventListener('change', filterPayments);
    
    // Function to filter payments based on search input and service filter
    function filterPayments() {
        const searchTerm = paymentSearch.value.toLowerCase().trim();
        const selectedService = paymentFilter.value;
        const rows = tableBody.querySelectorAll('tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            // Skip the "No payment records found" row if it exists
            if (row.cells.length === 1) {
                row.style.display = 'none';
                return;
            }
            
            const provider = row.cells[2].textContent.toLowerCase();
            const service = row.cells[5].textContent;
            
            // Check if row matches search term and service filter
            const matchesSearch = searchTerm === '' || provider.includes(searchTerm);
            const matchesService = selectedService === '' || service === selectedService;
            
            // Show or hide row based on filters
            if (matchesSearch && matchesService) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show or hide no results message
        if (visibleCount === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }
    
    // Export functionality
    document.getElementById('export-payments').addEventListener('click', function() {
        // Get visible rows
        const rows = Array.from(tableBody.querySelectorAll('tr')).filter(row => row.style.display !== 'none' && row.cells.length > 1);
        
        if (rows.length === 0) {
            alert('No data to export');
            return;
        }
        
        // Create CSV content
        let csv = 'Request ID,Date,Service Provider,Total Payment,WE4U Commission,Service Type,Status\n';
        
        rows.forEach(row => {
            const cells = Array.from(row.cells);
            const rowData = cells.map(cell => {
                // Remove currency symbol and commas from payment amounts
                let text = cell.textContent.trim();
                if (text.startsWith('Rs.')) {
                    text = text.replace('Rs. ', '').replace(/,/g, '');
                }
                // Wrap text in quotes to handle commas in text
                return `"${text}"`;
            });
            csv += rowData.join(',') + '\n';
        });
        
        // Create download link
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'payment_data_' + new Date().toISOString().slice(0, 10) + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
    
    // Refresh button functionality
    document.querySelector('.ad-dashboard-btn-add').addEventListener('click', function() {
        location.reload();
    });
});
</script>