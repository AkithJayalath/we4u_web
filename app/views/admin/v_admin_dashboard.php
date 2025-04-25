<?php 
    $required_styles = [
        'admin/admin_dashboard',
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
          <h1>Dashboard Overview</h1>
      </div>
      <main class="ad-dashboard-main-content">
          <!-- Stats cards section -->
        <div class="ad-dashboard-stats-container">
            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Completed Jobs</h3>
                        <div class="ad-dashboard-stat-value"><?php echo number_format($data['completedJobs']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-blue">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Rejected Jobs</h3>
                        <div class="ad-dashboard-stat-value"><?php echo number_format($data['rejectedJobs']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-red">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Pending Jobs</h3>
                        <div class="ad-dashboard-stat-value"><?php echo number_format($data['pendingJobs']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-yellow">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Cancelled Jobs</h3>
                        <div class="ad-dashboard-stat-value"><?php echo number_format($data['cancelledJobs']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-yellow">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Last Week Completed</h3>
                        <div class="ad-dashboard-stat-value"><?php echo number_format($data['lastWeekCompleted']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-green">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                </div>
            </div>

            <div class="ad-dashboard-stat-card">
                <div class="ad-dashboard-stat-main">
                    <div class="ad-dashboard-stat-details">
                        <h3>Last Month Completed</h3>
                        <div class="ad-dashboard-stat-value"><?php echo number_format($data['lastMonthCompleted']); ?></div>
                    </div>
                    <div class="ad-dashboard-stat-icon ad-dashboard-blue">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>

        </div>


          <!-- Filters section -->
          <div class="ad-dashboard-filters">
              <div class="ad-dashboard-table-controls">
                  <select id="job-filter" class="ad-dashboard-filter-select">
                      <option value="">All Jobs</option>
                      <option value="Caregiving">Caregiver Service</option>
                      <option value="Consultation">Consultation</option>
                  </select>
                    <!-- Search wrapper -->
                    <div class="ad-dashboard-search-wrapper">
                        <i data-lucide="search" class="ad-dashboard-search-icon"></i>
                        <input type="search" id="job-search" class="ad-dashboard-job-search" placeholder="Search by name" />
                    </div>
              </div>
              <!-- <div class="ad-dashboard-action-group">
                  <button class="ad-dashboard-btn-export" id="export-jobs">
                      <i data-lucide="download" class="ad-dashboard-btn-icon"></i>
                      Export
                  </button>
                  <button class="ad-dashboard-btn-add">
                      <i data-lucide="refresh-cw" class="ad-dashboard-btn-icon"></i>
                      Refresh Data
                  </button>
              </div> -->
          </div>

          <!-- Table section with horizontal scroll -->
          <div class="ad-dashboard-table-container">
              <table class="ad-dashboard-jobs-table">
                  <thead>
                      <tr>
                          <th>REQUEST ID</th>
                          <th>DATE</th>
                          <th>CARE SEEKER</th>
                          <th>SERVICE PROVIDER</th>
                          <th>SERVICE TYPE</th>
                          <th>STATUS</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach($data['Requests'] as $request): ?>
                      <tr>
                          <td>
                              <?php 
                              $prefix = ($request->service_category == 'Caregiving') ? 'CG' : 'CO';
                              echo $prefix . $request->request_id; 
                              ?>
                          </td>
                          <td><?php echo date('j M Y', strtotime($request->created_at ?? $request->request_date)); ?></td>
                          <td><?php echo $request->careseeker_name ?? 'N/A'; ?></td>
                          <td><?php echo $request->provider_name ?? 'N/A'; ?></td>
                          <td><?php echo $request->service_category; ?></td>
                          <td>
                              <?php 
                              $statusClass = '';
                              switch(strtolower($request->status)) {
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
                              <span class="ad-dashboard-status <?php echo $statusClass; ?>"><?php echo ucfirst($request->status); ?></span>
                          </td>
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>

              <!-- No results message -->
              <div id="no-results-message" style="display: none; text-align: center; padding: 20px; color: #666;">
                  No Request found matching your search criteria.
              </div>
          </div>
      </main>
  </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const jobSearch = document.getElementById('job-search');
    const jobFilter = document.getElementById('job-filter');
    const tableBody = document.getElementById('jobs-table-body');
    const noResultsMessage = document.getElementById('no-results-message');
    const sortableHeaders = document.querySelectorAll('th.sortable');
    
    // Current sort state
    let currentSort = {
        column: null,
        direction: 'asc'
    };
    
    // Add event listener for search input
    jobSearch.addEventListener('input', filterJobs);
    
    // Add event listener for job filter
    jobFilter.addEventListener('change', filterJobs);
    
    // Add event listeners for sortable headers
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.getAttribute('data-sort');
            sortJobs(column);
        });
    });
    
    // Function to filter jobs based on search input and job filter
    function filterJobs() {
        const searchTerm = jobSearch.value.toLowerCase().trim();
        const selectedService = jobFilter.value;
        const rows = tableBody.querySelectorAll('tr.job-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const careseeker = row.getAttribute('data-careseeker').toLowerCase();
            const provider = row.getAttribute('data-provider').toLowerCase();
            const service = row.getAttribute('data-service');
            
            // Check if row matches search term and service filter
            const matchesSearch = searchTerm === '' || 
                                 careseeker.includes(searchTerm) || 
                                 provider.includes(searchTerm);
            
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
    
    // Function to sort jobs
    function sortJobs(column) {
        const rows = Array.from(tableBody.querySelectorAll('tr.job-row'));
        
        // Determine sort direction
        let direction = 'asc';
        if (currentSort.column === column) {
            direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        }
        
        // Update sort icons
        updateSortIcons(column, direction);
        
        // Sort the rows
        rows.sort((a, b) => {
            let valueA = a.getAttribute('data-' + column).toLowerCase();
            let valueB = b.getAttribute('data-' + column).toLowerCase();
            
            // Special handling for date (convert to timestamp for comparison)
            if (column === 'date') {
                valueA = new Date(valueA).getTime();
                valueB = new Date(valueB).getTime();
                return direction === 'asc' ? valueA - valueB : valueB - valueA;
            }
            
            // String comparison for other columns
            if (direction === 'asc') {
                return valueA.localeCompare(valueB);
            } else {
                return valueB.localeCompare(valueA);
            }
        });
        
        // Reorder the rows in the table
        rows.forEach(row => {
            tableBody.appendChild(row);
        });
        
        // Update current sort state
        currentSort.column = column;
        currentSort.direction = direction;
    }
    
    // Function to update sort icons
    function updateSortIcons(activeColumn, direction) {
        sortableHeaders.forEach(header => {
            const column = header.getAttribute('data-sort');
            const icon = header.querySelector('.sort-icon');
            
            if (column === activeColumn) {
                // Set the active sort icon
                icon.setAttribute('data-lucide', direction === 'asc' ? 'chevron-up' : 'chevron-down');
            } else {
                // Reset other sort icons
                icon.setAttribute('data-lucide', 'chevron-down');
            }
        });
        
        // Refresh Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
});
</script>
