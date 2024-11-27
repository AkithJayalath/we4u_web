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
        <button class="a-v-a-btn-add" onclick="window.location.href='<?php echo URLROOT; ?>/admin/addannouncement'">
          <i class="fas fa-plus"></i> Add Announcement
      </button>
        </div>
        <div class="a-v-a-table">
          <div class="a-v-a-table-header">
            <div class="a-v-a-table-cell">Idea Board</div>
            <div class="a-v-a-table-cell">Updated Date</div>
            <div class="a-v-a-table-cell">Status</div>
            <div class="a-v-a-table-cell">Action</div>
          </div>
        <div class="a-v-a-table-body">
            <?php foreach($data['announcements'] as $announcement): ?>
                <div class="a-v-a-table-row">
                    <div class="a-v-a-table-cell"><?php echo $announcement->title; ?></div>
                    <div class="a-v-a-table-cell"><?php echo date('d M Y', strtotime($announcement->updated_at)); ?></div>
                    <div class="a-v-a-table-cell status-<?php echo strtolower($announcement->status); ?>"><?php echo $announcement->status; ?></div>
                    <div class="a-v-a-table-cell">
                        <button class="a-v-a-view-action-btn" onclick="window.location.href='<?php echo URLROOT; ?>/admin/editannouncement/<?php echo $announcement->announcement_id; ?>'">Details</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
      </div>

    </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
