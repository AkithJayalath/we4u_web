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
        
        <?php if(isset($data['announcement']) && $data['announcement']): ?>
          <form class="a-e-a-form" method="POST" action="<?php echo URLROOT; ?>/operator/editannouncement/<?php echo $data['announcement']->announcement_id; ?>">
            <div class="a-e-a-form-group">
                <label for="title">Announcement Title</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="a-e-a-input <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $data['announcement']->title; ?>"
                    required
                />
                <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
            </div>

            <div class="a-e-a-form-group">
                <label for="content">Announcement Content</label>
                <textarea 
                    id="content" 
                    name="content" 
                    class="a-e-a-input <?php echo (!empty($data['content_err'])) ? 'is-invalid' : ''; ?>"
                    rows="10" 
                    required
                ><?php echo $data['announcement']->content; ?></textarea>
                <span class="invalid-feedback"><?php echo $data['content_err']; ?></span>
            </div>

            <div class="a-e-a-form-group">
                <label for="status">Publication Status</label>
                <select 
                    id="status" 
                    name="status" 
                    class="a-e-a-input <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>"
                >
                    <option value="Published" <?php echo ($data['announcement']->status == 'Published') ? 'selected' : ''; ?>>Published</option>
                    <option value="Draft" <?php echo ($data['announcement']->status == 'Draft') ? 'selected' : ''; ?>>Draft</option>
                </select>
                <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
            </div>
            <div class="a-e-a-form-buttons">
                  <button type="button" class="a-e-a-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/operator/viewannouncement'">Cancel</button>
                  <button type="button" class="a-e-a-btn-delete" data-delete-url="<?php echo URLROOT; ?>/operator/deleteannouncement/<?php echo $data['announcement']->announcement_id; ?>">Delete</button>
                  <button type="submit" class="a-e-a-btn-save">Save Changes</button>
              </div>
            </form>
        <?php else: ?>
            <div class="announcement-not-found">
                <p>Announcement does not exist.</p>
            </div>
        <?php endif; ?>
    </div>
</main>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
  <script>
      // Confirmation for Save Changes
      document.querySelector('.a-e-a-btn-save').addEventListener('click', function(e) {
          e.preventDefault();
          if (confirm('Are you sure you want to save these changes?')) {
              document.querySelector('.a-e-a-form').submit();
          }
      });

      // Confirmation for Delete
      document.querySelector('.a-e-a-btn-delete').addEventListener('click', function(e) {
          e.preventDefault();
          if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
              window.location.href = this.getAttribute('data-delete-url');
          }
      });
  </script>
