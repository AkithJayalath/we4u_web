<?php
$required_styles = [
  'careseeker/viewRequests',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
  <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
  <div class="request-info">


    <!-- Container -->
    <div class="view-requests-m-c-r-container">

      <div class="view-requests-m-c-r-table-container">
        <h2>Requests</h2>
        <div class="view-requests-m-c-r-filter-section">
          <label for="filter-date">Date:</label>
          <select id="filter-date" class="filter-select">
            <option value="newest">Newest</option>
            <option value="oldest">Oldest</option>
          </select>

          <label for="filter-status">Status:</label>
          <select id="filter-status" class="filter-select">
            <option value="all">All</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
            <option value="completed">Completed</option>
          </select>

          <label for="filter-service">Service:</label>
          <select id="filter-service" class="filter-select">
            <option value="all">All</option>
            <option value="consultation">Consultation</option>
            <option value="caregiving">Caregiving</option>
          </select>

          <button id="apply-filters-btn" class="view-requests-m-c-r-view-req-action-btn">
            Apply
          </button>
        </div>
        <div class="view-requests-m-c-r-table">
          <div class="view-requests-m-c-r-table-header">
            <div class="view-requests-m-c-r-table-cell">Request ID</div>
            <div class="view-requests-m-c-r-table-cell">Date</div>
            <div class="view-requests-m-c-r-table-cell">Service Provider</div>
            <div class="view-requests-m-c-r-table-cell">Service</div>
            <div class="view-requests-m-c-r-table-cell">Status</div>
            <div class="view-requests-m-c-r-table-cell">Action</div>
          </div>
          <div class="view-requests-m-c-r-table-body">



            <?php foreach ($data['requests'] as $request): ?>
              <div class="view-requests-m-c-r-table-row">
                <div class="view-requests-m-c-r-table-cell">
                  <?php
                  $prefix = ($request->service_category === 'Caregiving') ? 'CG' : (($request->service_category === 'Consultation') ? 'CT' : '');
                  echo '#' . $prefix . $request->request_id;
                  ?>
                </div>

                <div class="view-requests-m-c-r-table-cell" data-date="<?= date('Y-m-d', strtotime($request->created_at)) ?>">
                  <?= date('d/m/Y', strtotime($request->created_at)) ?>
                </div>

                <div class="view-requests-m-c-r-table-cell">
                  <?= htmlspecialchars($request->caregiver_name ?? $request->consultant_name ?? 'N/A') ?>
                </div>

                <div class="view-requests-m-c-r-table-cell"><?= $request->service_category ?></div> 
                <div class="view-requests-m-c-r-table-cell">
                  <span class="tag <?= strtolower($request->status) ?>">
                    <?= ucfirst($request->status) ?>
                  </span>
                </div>
                <div class="view-requests-m-c-r-table-cell">
                  <?php
                  $baseUrl = URLROOT;
                  $requestId = $request->request_id;
                  $category = strtolower($request->service_category); // make it lowercase for consistency

                  // Determine the path based on category
                  if ($category === 'caregiving') {
                    $url = "$baseUrl/careseeker/viewRequestInfo/$requestId";
                  } elseif ($category === 'consultation') {
                    $url = "$baseUrl/careseeker/viewConsultRequestInfo/$requestId";
                  } else {
                    $url = "#"; // fallback in case of unknown category
                  }
                  ?>

                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn"
                    onclick="window.location.href='<?= $url ?>'">
                    View request
                  </button>

                </div>
              </div>
            <?php endforeach; ?>




            <!-- Your existing table rows here -->
          </div>
        </div>
      </div>
    </div>


  </div>

</page-body-container>
<script src="<?php echo URLROOT; ?>/js/requestsFilter.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>