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
   
            <!-- <label for="filter-service">Service:</label>
            <select id="filter-service" class="filter-select">
                <option value="all">All</option>
                <option value="consultation">Consultation</option>
                <option value="caregiving">Caregiving</option> -->
            </select>
   
            <button id="apply-filters-btn" class="view-requests-m-c-r-view-req-action-btn">
                Apply
            </button>
        </div>

        <div class="view-requests-m-c-r-table">
          <div class="view-requests-m-c-r-table-header">
            <div class="view-requests-m-c-r-table-cell">Request ID</div>
            <div class="view-requests-m-c-r-table-cell">Date</div>
            <div class="view-requests-m-c-r-table-cell">Care Seeker</div>
            <div class="view-requests-m-c-r-table-cell">Action</div>
          </div>
          <div class="view-requests-m-c-r-table-body">
            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12354</div>
              <div class="view-requests-m-c-r-table-cell">03/04/2024</div>
              <div class="view-requests-m-c-r-table-cell">Dr.Amal Perera</div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View Session</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12354</div>
              <div class="view-requests-m-c-r-table-cell">03/04/2024</div>
              <div class="view-requests-m-c-r-table-cell">Dr.Amal Perera</div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View Session</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12454</div>
              <div class="view-requests-m-c-r-table-cell">03/05/2020</div>
              <div class="view-requests-m-c-r-table-cell">Dr.Supun Jayawardhana</div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View Session</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#32334</div>
              <div class="view-requests-m-c-r-table-cell">03/02/2022</div>
              <div class="view-requests-m-c-r-table-cell">Kalum Silva</div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View Session</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#5684</div>
              <div class="view-requests-m-c-r-table-cell">03/09/2023</div>
              <div class="view-requests-m-c-r-table-cell">Dilon Soysa</div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View Session</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12354</div>
              <div class="view-requests-m-c-r-table-cell">03/04/2019</div>
              <div class="view-requests-m-c-r-table-cell">Kasun Rathnayake</div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View Session</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
</page-body-container>

<script src="<?php echo URLROOT; ?>/js/requestsFilter.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>
