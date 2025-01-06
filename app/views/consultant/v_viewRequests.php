<?php
$required_styles = [
    'consultant/viewRequests1',
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
        <h2>Consultant Sessions</h2>
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
   
            <button id="apply-filters-btn" class="view-requests-m-c-r-view-req-action-btn">
                Apply
            </button>
        </div>

        <div class="view-requests-m-c-r-table">
          <div class="view-requests-m-c-r-table-header">
            <div class="view-requests-m-c-r-table-cell">Request ID</div>
            <div class="view-requests-m-c-r-table-cell">Date</div>
            <div class="view-requests-m-c-r-table-cell">Care Seeker</div>
            <div class="view-requests-m-c-r-table-cell">Status</div>
            <div class="view-requests-m-c-r-table-cell">Action</div>
          </div>
          <div class="view-requests-m-c-r-table-body">
            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12354</div>
              <div class="view-requests-m-c-r-table-cell">03/04/2024</div>
              <div class="view-requests-m-c-r-table-cell">Dr.Amal Perera</div>
              <div class="view-requests-m-c-r-table-cell"><span class="tag pending">Pending</span></div>
              <div class="view-requests-m-c-r-table-cell">
                <button type="submit" class="view-requests-m-c-r-view-req-action-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/viewrequestinfo'">View request</button>

              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12354</div>
              <div class="view-requests-m-c-r-table-cell">03/04/2024</div>
              <div class="view-requests-m-c-r-table-cell">Dr.Amal Perera</div>
              <div class="view-requests-m-c-r-table-cell"><span class="tag pending">Pending</span></div>
              <div class="view-requests-m-c-r-table-cell">
                <button type="submit" class="view-requests-m-c-r-view-req-action-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/viewrequestinfo'">View request
                </button>

              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12454</div>
              <div class="view-requests-m-c-r-table-cell">03/05/2020</div>
              <div class="view-requests-m-c-r-table-cell">Dr.Supun Jayawardhana</div>
              <div class="view-requests-m-c-r-table-cell"><span class="tag completed">Completed</span></div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View request</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#32334</div>
              <div class="view-requests-m-c-r-table-cell">03/02/2022</div>
              <div class="view-requests-m-c-r-table-cell">Kalum Silva</div>
              <div class="view-requests-m-c-r-table-cell"><span class="tag accepted">Accepted</span></div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View request</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#5684</div>
              <div class="view-requests-m-c-r-table-cell">03/09/2023</div>
              <div class="view-requests-m-c-r-table-cell">Dilon Soysa</div>
              <div class="view-requests-m-c-r-table-cell"><span class="tag rejected">Rejected</span></div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View request</button>
              </div>
            </div>

            <div class="view-requests-m-c-r-table-row">
              <div class="view-requests-m-c-r-table-cell">#12354</div>
              <div class="view-requests-m-c-r-table-cell">03/04/2019</div>
              <div class="view-requests-m-c-r-table-cell">Kasun Rathnayake</div>
              <div class="view-requests-m-c-r-table-cell"><span class="tag pending">Pending</span></div>
              <div class="view-requests-m-c-r-table-cell">
                  <button type="submit" class="view-requests-m-c-r-view-req-action-btn">View request</button>
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
