<?php 
    $required_styles = [
        'admin/edit-blog',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

  <main class="a-b-e-main-content">
      <div class="a-b-e-stat-card">
        <h2>Edit Blog Post</h2>
        <form class="a-b-e-form">
          <div class="a-b-e-form-group">
            <label for="title">Blog Title</label>
            <input
              type="text"
              id="title"
              class="a-b-e-input"
              value="What to Look for in a Caregiver"
              required
            />
          </div>

          <div class="a-b-e-form-group">
            <label for="image">Blog Image</label>
            <div class="a-b-e-image-container">
              <img id="preview-image" src="<?php echo URLROOT; ?>/public/images/temp-images/1.png" alt="Blog preview" />
              <input
                type="file"
                id="image"
                accept="image/*"
                class="a-b-e-input"
              />
            </div>
          </div>

          <div class="a-b-e-form-group">
            <label for="content">Blog Content</label>
            <textarea id="content" class="a-b-e-input" rows="10" required>When hiring a caregiver, it's important to consider their qualifications, experience, and personality. Look for someone who is compassionate, reliable, and able to provide the care your loved one needs.</textarea>
          </div>

          <div class="a-b-e-form-buttons">
            <button type="button" class="a-b-e-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/blog'">Cancel</button>
            <button type="submit" class="a-b-e-btn-save">Save Changes</button>
          </div>
        </form>
      </div>
    </main>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>