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
        <h2>Add New Blog Post</h2>
        <form class="a-b-e-form">
          <div class="a-b-e-form-group">
            <label for="title">Blog Title</label>
            <input
              type="text"
              id="title"
              class="a-b-e-input"
              placeholder="Enter blog title"
              required
            />
          </div>

          <div class="a-b-e-form-group">
            <label for="image">Blog Image</label>
            <div class="a-b-e-image-container">
              <img id="preview-image" src="" alt="Preview" />
              <input
                type="file"
                id="image"
                accept="image/*"
                class="a-b-e-input"
                required
              />
            </div>
          </div>

          <div class="a-b-e-form-group">
            <label for="content">Blog Content</label>
            <textarea id="content" class="a-b-e-input" rows="10" placeholder="Enter blog content" required></textarea>
          </div>

          <div class="a-b-e-form-buttons">
            <button type="button" class="a-b-e-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/blog'">Cancel</button>
            <button type="submit" class="a-b-e-btn-save">Create Blog</button>
          </div>
        </form>
      </div>
    </main>
</page-body-container>
