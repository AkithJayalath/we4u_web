<?php 
    $required_styles = [
        'admin/viewannouncements',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="a-v-a-container">
      <div class="a-v-a-search-section">
        <input type="text" placeholder="Search By Title" />
        <button>Search</button>
      </div>

      <div class="a-v-a-table-container">
        <h2>Announcement Information</h2>
        <div class="a-v-a-add-button">
          <button class="a-v-a-btn-add">
            <i class="fas fa-plus"></i> Add Announcement
          </button>
        </div>
        <div class="a-v-a-table">
          <div class="a-v-a-table-header">
            <div class="a-v-a-table-cell">Idea Board</div>
            <div class="a-v-a-table-cell">Publish Date</div>
            <div class="a-v-a-table-cell">Status</div>
            <div class="a-v-a-table-cell">Action</div>
          </div>
          <div class="a-v-a-table-body">
              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">New Feature Release: Chat System</div>
                <div class="a-v-a-table-cell">15 Jun 2023</div>
                <div class="a-v-a-table-cell status-published">Published</div>
                <div class="a-v-a-table-cell">
                  <button class="a-v-a-view-action-btn" onclick="window.location.href='<?php echo URLROOT; ?>/admin/editannouncement'" >Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">System Maintenance Notice</div>
                <div class="a-v-a-table-cell">20 Jun 2023</div>
                <div class="a-v-a-table-cell status-draft">Draft</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">Updated Privacy Policy</div>
                <div class="a-v-a-table-cell">25 Jun 2023</div>
                <div class="a-v-a-table-cell status-published">Published</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">Holiday Schedule Changes</div>
                <div class="a-v-a-table-cell">30 Jun 2023</div>
                <div class="a-v-a-table-cell status-published">Published</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">Community Guidelines Update</div>
                <div class="a-v-a-table-cell">05 Jul 2023</div>
                <div class="a-v-a-table-cell status-draft">Draft</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">New User Rewards Program</div>
                <div class="a-v-a-table-cell">10 Jul 2023</div>
                <div class="a-v-a-table-cell status-published">Published</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">Platform Security Enhancement</div>
                <div class="a-v-a-table-cell">15 Jul 2023</div>
                <div class="a-v-a-table-cell status-published">Published</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">Mobile App Launch</div>
                <div class="a-v-a-table-cell">20 Jul 2023</div>
                <div class="a-v-a-table-cell status-draft">Draft</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">Customer Support Hours Extension</div>
                <div class="a-v-a-table-cell">25 Jul 2023</div>
                <div class="a-v-a-table-cell status-published">Published</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>

              <div class="a-v-a-table-row">
                <div class="a-v-a-table-cell">Website Redesign Preview</div>
                <div class="a-v-a-table-cell">30 Jul 2023</div>
                <div class="a-v-a-table-cell status-draft">Draft</div>
                <div class="a-v-a-table-cell"><button class="a-v-a-view-action-btn">Details</button></div>
              </div>
          </div>
        </div>
      </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
