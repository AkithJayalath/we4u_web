<?php 
    $required_styles = [
        'admin/viewannouncements',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
</div>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
  
  <div class="dashboard-container">
    <!-- Dashboard-style header -->
    <header class="dashboard-header">
      <h1>Announcements</h1>
      <button class="export-btn" onclick="window.location.href='<?php echo URLROOT; ?>/operator/addannouncement'">
        <i class="fas fa-plus"></i> Add Announcement
      </button>
    </header>

    <!-- Main content area -->
    <div class="announcement-content">
      <div class="announcement-table-container">
        <h2>Announcement Information</h2>
        <div class="announcement-table">
          <div class="announcement-table-header">
            <div class="announcement-table-cell">Idea Board</div>
            <div class="announcement-table-cell">Updated Date</div>
            <div class="announcement-table-cell">Status</div>
            <div class="announcement-table-cell">Action</div>
          </div>
          <div class="announcement-table-body">
            <?php foreach($data['announcements'] as $announcement): ?>
                <div class="announcement-table-row">
                    <div class="announcement-table-cell title-cell"><?php echo $announcement->title; ?></div>
                    <div class="announcement-table-cell"><?php echo date('d M Y', strtotime($announcement->updated_at)); ?></div>
                    <div class="announcement-table-cell status-<?php echo strtolower($announcement->status); ?>"><?php echo $announcement->status; ?></div>
                    <div class="announcement-table-cell">
                        <button class="announcement-action-btn" onclick="window.location.href='<?php echo URLROOT; ?>/operator/editannouncement/<?php echo $announcement->announcement_id; ?>'">Details</button>
                    </div>
                </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
