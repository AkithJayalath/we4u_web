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
      <!-- <div class="a-b-create-blog">
        <button>Create Blog</button>
      </div> -->
    </div>

    <div class="a-b-blogs-container">
      <div class="a-b-blog-post">
        <img src="<?php echo URLROOT; ?>/public/images/temp-images/1.png" alt="Caregiver" />

        <h2>What to Look for in a Caregiver</h2>
        <p>
          When hiring a caregiver, it's important to consider their
          qualifications, experience, and personality. Look for someone who is
          compassionate, reliable, and able to provide the care your loved one
          needs...
        </p>
        <!-- <div class="a-b-action-buttons">
          <button class="a-b-edit-btn">Edit</button>
          <button class="a-b-delete-btn">Delete</button>
        </div> -->
        <div class="a-b-read-more">
          <a href="<?php echo URLROOT; ?>/users/viewblog">View</a>
        </div>
      </div>

      <div class="a-b-blog-post">
        <img src="<?php echo URLROOT; ?>/public/images/temp-images/2.png" alt="Caregiver" />

        <h2>What to Look for in a Caregiver</h2>
        <p>
          When hiring a caregiver, it's important to consider their
          qualifications, experience, and personality. Look for someone who is
          compassionate, reliable, and able to provide the care your loved one
          needs...
        </p>
        <!-- <div class="a-b-action-buttons">
          <button class="a-b-edit-btn">Edit</button>
          <button class="a-b-delete-btn">Delete</button>
        </div> -->
        <div class="a-b-read-more">
        <a href="<?php echo URLROOT; ?>/users/viewblog">View</a>
        </div>
      </div>

      <div class="a-b-blog-post">
        <img src="<?php echo URLROOT; ?>/public/images/temp-images/3.png" alt="Caregiver" />

        <h2>What to Look for in a Caregiver</h2>
        <p>
          When hiring a caregiver, it's important to consider their
          qualifications, experience, and personality. Look for someone who is
          compassionate, reliable, and able to provide the care your loved one
          needs...
        </p>
        <!-- <div class="a-b-action-buttons">
          <button class="a-b-edit-btn">Edit</button>
          <button class="a-b-delete-btn">Delete</button>
        </div> -->
        <div class="a-b-read-more">
        <a href="<?php echo URLROOT; ?>/users/viewblog">View</a>
        </div>
      </div>

      <div class="a-b-blog-post">
        <img src="<?php echo URLROOT; ?>/public/images/temp-images/4.png" alt="Caregiver" />

        <h2>What to Look for in a Caregiver</h2>
        <p>
          When hiring a caregiver, it's important to consider their
          qualifications, experience, and personality. Look for someone who is
          compassionate, reliable, and able to provide the care your loved one
          needs...
        </p>
        <!-- <div class="a-b-action-buttons">
          <button class="a-b-edit-btn">Edit</button>
          <button class="a-b-delete-btn">Delete</button>
        </div> -->
        <div class="a-b-read-more">
        <a href="<?php echo URLROOT; ?>/users/viewblog">View</a>
        </div>
      </div>
    </div>

    <div class="a-b-see-more">
      <button>See More</button>
    </div>
  </div>

</container>

<?php require APPROOT.'/views/includes/footer.php'; ?>