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
        
        <?php flash('blog_error'); ?>
        
        <form class="a-b-e-form" method="post" action="<?php echo URLROOT; ?>/admin/addblog" enctype="multipart/form-data">
          <div class="a-b-e-form-group">
            <label for="title">Blog Title</label>
            <input
              type="text"
              id="title"
              name="title"
              class="a-b-e-input"
              placeholder="Enter blog title"
              value="<?php echo isset($data['title']) ? $data['title'] : ''; ?>"
              required
            />
            <span class="error"><?php echo isset($data['title_err']) ? $data['title_err'] : ''; ?></span>
          </div>

          <div class="a-b-e-form-group">
            <label for="image_path">Blog Image</label>
            <div class="a-b-e-image-container">
              <?php if(!empty($data['image_path'])): ?>
                <img id="preview-image" src="<?php echo URLROOT . '/' . $data['image_path']; ?>" alt="Preview" />
              <?php else: ?>
                <img id="preview-image" src="<?php echo URLROOT; ?>/images/placeholder.jpg" alt="Preview" />
              <?php endif; ?>
              <input
                type="file"
                id="image_path"
                name="image_path"
                accept="image/*"
                class="a-b-e-input"
                required
              />
              <span class="error"><?php echo isset($data['image_path_err']) ? $data['image_path_err'] : ''; ?></span>
            </div>
          </div>

          <div class="a-b-e-form-group">
            <label for="content">Blog Content</label>
            <textarea 
                id="content" 
                name="content" 
                class="a-b-e-input" 
                rows="10" 
                placeholder="Enter blog content" 
                required
            ><?php echo isset($data['content']) ? $data['content'] : ''; ?></textarea>
            <span class="error"><?php echo isset($data['content_err']) ? $data['content_err'] : ''; ?></span>
          </div>

          <div class="a-b-e-form-buttons">
            <button type="button" class="a-b-e-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/blog'">Cancel</button>
            <button type="submit" class="a-b-e-btn-save">Create Blog</button>
          </div>
        </form>
      </div>
  </main>
</page-body-container>

<script>
// Script to preview the image before upload
document.getElementById("image_path").onchange = function() {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById("preview-image").src = e.target.result;
    };
    reader.readAsDataURL(this.files[0]);
};
</script>

<?php require APPROOT.'/views/includes/footer.php'; ?>