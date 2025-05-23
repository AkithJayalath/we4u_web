<?php 
    $required_styles = [
        'moderator/interviews',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
  
  <div class="interview-main-container">
    <!-- Header section -->
    <header class="interview-header">
      <h1>Interview Details</h1>
      <div class="interview-search-wrapper">
        <input type="text" id="searchInput" placeholder="Search By UserName Or Email" />
      </div>
    </header>

    <!-- Main content area -->
    <div class="interview-content">
      <div class="interview-table-container">
        <h2>Interview Information</h2>
        <table class="interview-table">
          <thead>
            <tr>
              <th>Interview ID</th>
              <th>Status</th>
              <th>Date</th>
              <th>Time</th>
              <th>User Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="interviewTableBody">
            <?php foreach($data['interviews'] as $interview) : ?>
              <tr >
                <td><?php echo $interview->interview_id; ?></td>
                <td class="status-<?php echo strtolower($interview->status); ?>"><?php echo $interview->status; ?></td>
                <td><?php echo date_format(date_create($interview->request_date), 'j M Y'); ?></td>
                <td><?php echo date_format(date_create($interview->interview_time), 'h:i A'); ?></td>
                <td><?php echo $interview->provider_name; ?></td>
                <td>
                  <button class="interview-action-btn" onclick="window.location.href='<?php echo URLROOT; ?>/moderator/interview/<?php echo $interview->request_id; ?>'">Details</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</page-body-container>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const interviewRows = document.querySelectorAll('#interviewTableBody tr');
    
    // Function to perform the search
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        // If search term is empty, show all rows
        if (searchTerm === '') {
            interviewRows.forEach(row => {
                row.style.display = '';
            });
            return;
        }
        
        // Filter rows based on search term
        interviewRows.forEach(row => {
            const providerName = row.getAttribute('data-provider-name')?.toLowerCase() || '';
            const providerEmail = row.getAttribute('data-provider-email')?.toLowerCase() || '';
            const providerId = row.getAttribute('data-provider-id')?.toLowerCase() || '';
            
            // Check if search term matches provider name, email, or ID
            if (providerName.includes(searchTerm) || 
                providerEmail.includes(searchTerm) || 
                providerId.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listener for search input
    searchInput.addEventListener('input', performSearch);
    
    // Add event listener for search button click
    searchButton.addEventListener('click', performSearch);
    
    // Add event listener for Enter key in search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
});
</script>

<?php require APPROOT.'/views/includes/footer.php'?>
