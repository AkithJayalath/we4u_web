<?php 
    $required_styles = [
        'admin/addannouncement',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

  <main class="a-a-a-main-content">
      <div class="a-a-a-stat-card">
        <h2>Add New Announcement</h2>
        <form class="a-a-a-form">
          <div class="a-a-a-form-group">
            <label for="title">Announcement Title</label>
            <input
              type="text"
              id="title"
              class="a-a-a-input"
              placeholder="Enter announcement title"
              required
            />
          </div>

          <div class="a-a-a-form-group">
            <label for="content">Announcement Content</label>
            <textarea id="content" class="a-a-a-input" rows="10" placeholder="Enter announcement content" required></textarea>
          </div>

          <div class="a-a-a-form-group">
            <label for="status">Publication Status</label>
            <select id="status" class="a-a-a-input">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
          </div>

          <div class="a-a-a-form-buttons">
            <button type="button" class="a-a-a-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/viewannouncements'">Cancel</button>
            <button type="submit" class="a-a-a-btn-save">Create Announcement</button>
          </div>
        </form>
      </div>
    </main>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
