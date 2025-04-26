<?php 
    $required_styles = [
        'careseeker/blogs',
        // 'components/sidebar',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
</div>

<page-body-container>
  <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

  <div class="a-b-page-wrapper">
    <div class="a-b-header">
      <div class="a-b-create-blog">
        <button onclick="window.location.href='<?php echo URLROOT; ?>/admin/addblog'">Create Blog</button>
      </div>
    </div>

    <div class="a-b-blogs-container">
      <?php if (!empty($data['blogs'])): ?>
        <?php foreach ($data['blogs'] as $blog): ?>
          <div class="a-b-blog-post">
            <img src="<?php echo URLROOT; ?>/public/<?php echo htmlspecialchars($blog->image_path); ?>" alt="Blog Image" />

            <h2><?php echo htmlspecialchars($blog->title); ?></h2>
            <p>
              <?php echo htmlspecialchars(substr($blog->content, 0, 150)); ?>...
            </p>
            <div class="a-b-action-buttons">
              <button class="a-b-edit-btn" onclick="window.location.href='<?php echo URLROOT; ?>/admin/editblog/<?php echo $blog->blog_id; ?>'">Edit</button>
              <button class="a-b-delete-btn" onclick="if(confirm('Are you sure you want to delete this blog?')) { window.location.href='<?php echo URLROOT; ?>/admin/deleteblog/<?php echo $blog->blog_id; ?>'; }">Delete</button>
            </div>
            <div class="a-b-read-more">
              <a href="<?php echo URLROOT; ?>/admin/viewblog/<?php echo $blog->blog_id; ?>">View</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-blogs-message">
          <p>No blogs available. Click "Create Blog" to add a new one.</p>
        </div>
      <?php endif; ?>
    </div>

    <div class="a-b-see-more">
      <button onclick="window.location.href='<?php echo URLROOT; ?>/admin/blogs?page=<?php echo $data['nextPage'] ?? 1; ?>'">See More</button>
    </div>
  </div>

</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>