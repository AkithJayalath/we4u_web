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

<container>
  <div class="a-b-page-wrapper">
    <div class="a-b-header">
      <!-- No Create Blog button for users -->
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
            <div class="a-b-read-more">
              <a href="<?php echo URLROOT; ?>/users/viewblog/<?php echo $blog->blog_id; ?>">View</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-blogs-message">
          <p>No blogs available at the moment.</p>
        </div>
      <?php endif; ?>
    </div>

    <div class="a-b-see-more">
      <button onclick="window.location.href='<?php echo URLROOT; ?>/users/blogs?page=<?php echo $data['nextPage'] ?? 1; ?>'">See More</button>
    </div>
  </div>
</container>

<?php require APPROOT.'/views/includes/footer.php'; ?>