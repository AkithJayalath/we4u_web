<?php 
    $required_styles = [
        'admin/admin_dashboard',
        // 'components/sidebar',
        
    ];
    echo loadCSS($required_styles);
?>



<link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet"
    />

<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
</div>

<page-body-container>
<?php require APPROOT.'/views/includes/components/sidebar.php'; ?>




<div class="dashboard-container">
  <header class="dashboard-header">
    <h1>Dashboard Overview</h1>
    <button class="export-btn">Export Report</button>
  </header>

  <div class="dashboard-metrics">
    <div class="metric">
      <h3 class="metric-title">Jobs Completed</h3>
      <div class="metric-value">2,000</div>
      <div class="metric-change">+15%</div>
    </div>
    <div class="metric">
      <h3 class="metric-title">Total Visitors</h3>
      <div class="metric-value">22,435</div>
      <div class="metric-change" style="color: red;">-3.5%</div>
    </div>
    <div class="metric">
      <h3 class="metric-title">User Registrations</h3>
      <div class="metric-value">2,400</div>
      <div class="metric-change">+15%</div>
    </div>
    <div class="metric">
      <h3 class="metric-title">Complaints</h3>
      <div class="metric-value">8</div>
      <div class="metric-change" style="color: red;">+10%</div>
    </div>
  </div>
  <div class="dashboard-charts">
      <div class="chart-container">
          <h2>User Registrations</h2>
          <canvas id="userRegistrationsChart"></canvas>
      </div>
      <div class="chart-container">
          <h2>Caregiver Activity</h2>
          <canvas id="caregiverActivityChart"></canvas>
      </div>
      <div class="chart-container">
          <h2>Active Jobs Overview</h2>
          <canvas id="activeJobsChart"></canvas>
      </div>
      <div class="chart-container">
          <h2>User Engagement Metrics</h2>
          <canvas id="userEngagementChart"></canvas>
      </div>
  </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="script.js"></script>
</body>
</html>

</page-body-container>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Link the dashboard.js file -->
  <script src="<?php echo URLROOT; ?>/js/admin_dashboard.js"></script>


<?php require APPROOT.'/views/includes/footer.php'; ?>
