<?php 
    $required_styles = [
        'moderator/carerequests',
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
      <h1>Caregiver & Consultant Requests</h1>
      <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Search By UserName Or Email" />
      </div>
    </header>

    <!-- Main content area -->
    <div class="request-content">
    <div class="request-table-container">
  <h2>Request Information</h2>
  <table class="request-table">
    <thead>
      <tr>
        <th>Request ID</th>
        <th>Date</th>
        <th>Care Seeker</th>
        <th>Service Provider</th>
        <th>Service</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data['requests'] as $request) : ?>
        <tr>
          <td>
            <?php
            $prefix = ($request->service_category === 'Caregiving') ? 'CG' : (($request->service_category === 'Consultation') ? 'CT' : '');
            echo '#' . $prefix . $request->request_id;
            ?>
          </td>
          <td><?php echo date_format(date_create($request->created_at ?? $request->request_date), 'j M Y'); ?></td>
          <td><?php echo $request->careseeker_name ?? 'N/A'; ?></td>
          <td><?php echo $request->provider_name ?? 'N/A'; ?></td>
          <td><?php echo $request->service_category; ?></td>
          <td class="status-<?php echo strtolower($request->status); ?>"><?php echo ucfirst($request->status); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

    </div>
  </div>
</page-body-container>


<?php require APPROOT.'/views/includes/footer.php'?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const tableRows = document.querySelectorAll('.request-table tbody tr');
  
  searchInput.addEventListener('keyup', function() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    
    tableRows.forEach(row => {
      // Get the care seeker name (3rd column) and service provider name (4th column)
      const careseeker = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
      const provider = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
      
      // Check if either the care seeker or provider name contains the search term
      if (careseeker.includes(searchTerm) || provider.includes(searchTerm)) {
        row.style.display = ''; // Show the row
      } else {
        row.style.display = 'none'; // Hide the row
      }
    });
    
    // Show a message if no results are found
    const visibleRows = document.querySelectorAll('.request-table tbody tr[style=""]').length;
  });
});
</script>
