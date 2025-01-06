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

        <form class="a-e-a-form" method="POST" action="<?php echo URLROOT; ?>/admin/editannouncement/<?php echo $data['announcement']->announcement_id; ?>">
          <div class="a-e-a-form-group">
            <label for="title">Announcement Title</label>
            <input
              type="text"
              id="title"
              name="title"
              class="a-e-a-input"

              value="<?php echo $data['announcement']->title; ?>"
              required
            />
          </div>

          <div class="a-e-a-form-group">
            <label for="content">Announcement Content</label>










            <textarea id="content" name="content" class="a-e-a-input" rows="10" required><?php echo $data['announcement']->content; ?></textarea>
          </div>

          <div class="a-e-a-form-group">
            <label for="status">Publication Status</label>



            <select id="status" name="status" class="a-e-a-input">
                <option value="Published" <?php echo ($data['announcement']->status == 'Published') ? 'selected' : ''; ?>>Published</option>
                <option value="Draft" <?php echo ($data['announcement']->status == 'Draft') ? 'selected' : ''; ?>>Draft</option>
            </select>
          </div>

          <div class="a-e-a-form-buttons">
            <button type="button" class="a-e-a-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/viewannouncement'">Cancel</button>

            <button type="submit" class="a-e-a-btn-save">Save Changes</button>
          </div>
        </form>
      </div>
    </main>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
