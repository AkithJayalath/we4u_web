<?php 
    $required_styles = [
        'admin/editannouncement',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

  <main class="a-e-a-main-content">
      <div class="a-e-a-stat-card">
        <h2>Edit Announcement</h2>
        <form class="a-e-a-form">
          <div class="a-e-a-form-group">
            <label for="title">Announcement Title</label>
            <input
              type="text"
              id="title"
              class="a-e-a-input"
              value="System Maintenance Notice"
              required
            />
          </div>

          <div class="a-e-a-form-group">
            <label for="content">Announcement Content</label>
            <textarea id="content" class="a-e-a-input" rows="10" required>
Dear users,

We will be conducting system maintenance on July 25th, 2023, from 2:00 AM to 4:00 AM EST. During this time, the platform will be temporarily unavailable.

Thank you for your understanding.

Best regards,
Admin Team
            </textarea>
          </div>

          <div class="a-e-a-form-group">
            <label for="status">Publication Status</label>
            <select id="status" class="a-e-a-input">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
          </div>

          <div class="a-e-a-form-buttons">
            <button type="button" class="a-e-a-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/viewannouncement'">Cancel</button>
            <button type="button" class="a-e-a-btn-save" onclick="window.location.href='<?php echo URLROOT; ?>/admin/viewannouncement'">Save Changes</button>
          </div>
        </form>
      </div>
    </main>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
