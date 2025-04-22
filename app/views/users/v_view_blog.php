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

<container>

  <main class="a-b-v-main">
      <article class="a-b-v-article">
        <header class="a-b-v-header">
          <h1>What to Look for in a Caregiver</h1>
        </header>
        <div class="a-b-v-meta">
            <span> Published: June 15, 2023</span>
            <span>By Admin</span>
        </div>

        <div class="a-b-v-featured-image">
          <img src="<?php echo URLROOT; ?>/public/images/temp-images/1.png" alt="Caregiver helping senior" />
        </div>

        <div class="a-b-v-content">
          <p>
            When hiring a caregiver, it's crucial to find someone who not only
            has the right qualifications but also fits well with your loved
            one's personality and needs.
          </p>

          <h2>Professional Qualifications</h2>
          <p>
            Look for caregivers with proper certification and training in
            elderly care. This includes CPR certification, Home Health Aide
            certification, and relevant experience with specific medical
            conditions.
          </p>

          <h2>Personal Qualities</h2>
          <p>
            The best caregivers demonstrate patience, compassion, reliability,
            and strong communication skills. They should be capable of handling
            both routine care and emergency situations effectively.
          </p>
        </div>

        <div class="a-b-v-actions">
          <button
            class="a-b-v-btn-back"
            onclick="window.location.href='blog'"
          >
          Back to Blogs
          </button>
        </div>
      </article>
    </main>
</container>