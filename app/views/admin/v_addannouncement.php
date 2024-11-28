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
        <form class="a-a-a-form" action="<?php echo URLROOT; ?>/operator/addannouncement" method="POST">
          <div class="a-a-a-form-group">
            <label for="title">Announcement Title</label>
            <input
              type="text"
              id="title"
              name="title"
              class="a-a-a-input <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>"
              value="<?php echo $data['title']; ?>"
              placeholder="Enter announcement title"
              required
            />
            <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
          </div>

          <div class="a-a-a-form-group">
            <label for="content">Announcement Content</label>
            <textarea 
              id="content" 
              name="content" 
              class="a-a-a-input <?php echo (!empty($data['content_err'])) ? 'is-invalid' : ''; ?>"
              rows="10" 
              placeholder="Enter announcement content" 
              required
            ><?php echo $data['content']; ?></textarea>
            <span class="invalid-feedback"><?php echo $data['content_err']; ?></span>
          </div>

          <div class="a-a-a-form-group">
            <label for="status">Publication Status</label>
            <select 
              id="status" 
              name="status" 
              class="a-a-a-input <?php echo (!empty($data['status_err'])) ? 'is-invalid' : ''; ?>"
            >
              <option value="Published" <?php echo ($data['status'] == 'Published') ? 'selected' : ''; ?>>Published</option>
              <option value="Draft" <?php echo ($data['status'] == 'Draft') ? 'selected' : ''; ?>>Draft</option>
            </select>
            <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
          </div>

          <div class="a-a-a-form-buttons">
            <button type="button" class="a-a-a-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/operator/viewannouncement'">Cancel</button>
            <!-- <button type="submit" class="a-a-a-btn-save">Create Announcement</button> -->
            <button type="button" class="a-a-a-btn-save">Create Announcement</button>

          </div>
        </form>
      </div>
    </main>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>

<script>
    document.querySelector('.a-a-a-btn-save').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to create this announcement?')) {
            document.querySelector('.a-a-a-form').submit();
        }
    });
</script>
