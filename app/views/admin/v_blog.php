<?php 
    $required_styles = [
        'admin/admin_blog',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</div>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

  <div class="ad-blog-wrapper">
    <div class="ad-blog-header">
      <h1>Blog Management</h1>
      <div>
        <button class="ad-blog-btn-add" onclick="window.location.href='<?php echo URLROOT; ?>/admin/addblog'">
          <i class="fas fa-plus"></i>
          Create Blog
        </button>
      </div>
    </div>

    <main class="ad-blog-main-content">
      <?php if (!empty($data['blogs'])): ?>
        <div class="ad-blog-container">
          <?php foreach ($data['blogs'] as $blog): ?>
            <div class="ad-blog-card">
              <img src="<?php echo URLROOT; ?>/public/<?php echo htmlspecialchars($blog->image_path); ?>" alt="Blog Image" />
              
              <div class="ad-blog-action-buttons">
                <button class="ad-blog-edit-btn" onclick="window.location.href='<?php echo URLROOT; ?>/admin/editblog/<?php echo $blog->blog_id; ?>'">
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button class="ad-blog-delete-btn" onclick="if(confirm('Are you sure you want to delete this blog?')) { window.location.href='<?php echo URLROOT; ?>/admin/deleteblog/<?php echo $blog->blog_id; ?>'; }">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </div>
              
              <div class="ad-blog-content">
                <h2><?php echo htmlspecialchars($blog->title); ?></h2>
                <p><?php echo htmlspecialchars(substr($blog->content, 0, 150)); ?>...</p>
              </div>
              
              <div class="ad-blog-view">
                <a href="<?php echo URLROOT; ?>/admin/viewblog/<?php echo $blog->blog_id; ?>">
                  <i class="fas fa-eye"></i> View
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="ad-blog-no-blogs">
          <p>No blogs available. Click "Create Blog" to add a new one.</p>
        </div>
      <?php endif; ?>

      <!-- <?php if (!empty($data['blogs'])): ?>
        <div class="ad-blog-see-more">
          <button onclick="window.location.href='<?php echo URLROOT; ?>/admin/blogs?page=<?php echo $data['nextPage'] ?? 1; ?>'">
            <i class="fas fa-arrow-down"></i> See More
          </button>
        </div>
      <?php endif; ?> -->
    </main>
  </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>