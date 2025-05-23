<?php 
    $required_styles = [
        'view_blog',
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
  <main class="a-b-v-main">
      <article class="a-b-v-article">
        <header class="a-b-v-header">
          <h1><?php echo htmlspecialchars($data['blog']->title); ?></h1>
        </header>
        <div class="a-b-v-meta">
            <span> Published: <?php echo date('F j, Y', strtotime($data['blog']->created_at)); ?></span>
            <span>By <?php echo htmlspecialchars($data['blog']->author_name ?? 'Admin'); ?></span>
        </div>

        <div class="a-b-v-featured-image">
          <img src="<?php echo URLROOT; ?>/public/<?php echo htmlspecialchars($data['blog']->image_path); ?>" alt="Blog Image" />
        </div>

        <div class="a-b-v-content">
          <p>
            <?php echo nl2br(htmlspecialchars($data['blog']->content)); ?>
          </p>
        </div>

        <div class="a-b-v-actions">
          <button
            class="a-b-v-btn-back"
            onclick="window.location.href='<?php echo URLROOT; ?>/admin/blog'"
          >
          Back to Blogs
          </button>
        </div>
      </article>
    </main>
</page-body-container>

