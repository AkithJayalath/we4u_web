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
        <form class="a-b-e-form" action="<?php echo URLROOT; ?>/admin/editBlog/<?php echo $data['blog_id']; ?>" method="POST" enctype="multipart/form-data">
  <div class="a-b-e-form-group">
    <label for="title">Blog Title</label>
    <input
      type="text"
      id="title"
      name="title"
      class="a-b-e-input"
      placeholder="Enter blog title"
      value="<?php echo $data['title']; ?>"
      required
    />
    <span class="error"><?php echo $data['title_err']; ?></span>
  </div>

  <div class="a-b-e-form-group">
    <label for="image_path">Blog Image</label>
    <div class="a-b-e-image-container">
      <img id="preview-image" src="<?php echo !empty($data['image_path']) ? URLROOT . '/public/' . $data['image_path'] : ''; ?>" alt="Preview" />
      <input
        type="file"
        id="image_path"
        name="image_path"
        accept="image/*"
        class="a-b-e-input"
      />
    </div>
    <input type="hidden" name="existing_image_path" value="<?php echo $data['image_path']; ?>">
    <span class="error"><?php echo $data['image_path_err']; ?></span>
  </div>

  <div class="a-b-e-form-group">
    <label for="content">Blog Content</label>
    <textarea
      id="content"
      name="content"
      class="a-b-e-input"
      rows="10"
      placeholder="Enter blog content"
      required><?php echo $data['content']; ?></textarea>
    <span class="error"><?php echo $data['content_err']; ?></span>
  </div>

  <div class="a-b-e-form-buttons">
    <button type="button" class="a-b-e-btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/blog'">Cancel</button>
    <button type="submit" class="a-b-e-btn-save">Save Changes</button>
  </div>
</form>

      </div>
    </main>
</page-body-container>
