<?php 
    $required_styles = [
        'careseeker/viewCaregivers',
        // 'components/sidebar',
        
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>


<container>

<div class="cargivers-container">
  <div class="caregivers-wrapper">
    <div class="caregivers-header">
       <div class="filter-sort-bar">
    <div class="filters">
      <label for="region-filter">Region:</label>
      <select id="region-filter" name="region">
        <option value="">All</option>
        <option value="Colombo">Colombo</option>
        <option value="Matara">Matara</option>
        <option value="Galle">Galle</option>
        <!-- Add more regions as needed -->
      </select>

      <label for="type-filter">Type:</label>
      <select id="type-filter" name="type">
        <option value="">All</option>
        <option value="Short Term">Short Term</option>
        <option value="Long Term">Long Term</option>
      </select>

      <label for="speciality-filter">Speciality:</label>
      <input type="text" id="speciality-filter" name="speciality" placeholder="Type speciality" />
    </div>

    <div class="sort-options">
      <label for="sort-by">Sort by:</label>
      <select id="sort-by" name="sort">
        <option value="">Select</option>
        <option value="rating">Rating</option>
        <option value="price-asc">Price: Low to High</option>
        <option value="price-desc">Price: High to Low</option>
      </select>
    </div>

    <button id="apply-filters" class="apply-button">Apply</button>
  </div>
    </div>
    <?php
// Sample data - You would typically fetch this from the database
$caregivers = [
    // Example caregivers data here...
];

$initialDisplay = 8;  // Show 8 posts initially
$nextBatch = 4;       // Load 4 more posts with each "See More"

$caregiversToShow = array_slice($caregivers, 0, $initialDisplay);
?>

    <div class="caregivers-container">
    <?php
// Sample data - You would typically fetch this from the database
$caregivers = [
    [
        'name' => 'Pawan Silva',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],

    [
        'name' => 'Amal Peiris',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],

    [
        'name' => 'Amal Peiris',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],

    [
        'name' => 'Amal Peiris',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],

    [
        'name' => 'Amal Peiris',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],

    [
        'name' => 'Amal Peiris',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],
    [
        'name' => 'Amal Peiris',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],
    [
        'name' => 'Amal Peiris',
        'region' => 'Colombo',
        'type' => 'Short Term',
        'speciality' => 'Dementia Care',
        'price' => 200,
        'rating' => '★★★★☆',
        'image' => 'temp-images/1.png',
        'gender' => 'Male',
    ],
    // Add more caregivers as needed
    
];

foreach ($caregivers as $caregiver) {
    ?>
    <div class="caregivers-post" 
         data-tags="region:<?php echo $caregiver['region']; ?>,type:<?php echo $caregiver['type']; ?>,speciality:<?php echo $caregiver['speciality']; ?>,price:<?php echo $caregiver['price']; ?>">
        <div class="caregivers-post-header">
            <div class="caregivers-post-header-image">
                <img src="<?php echo URLROOT . '/public/images/' . $caregiver['image']; ?>" alt="Caregiver" />
            </div>
        </div>
        <div class="caregivers-post-content">
            <h2><?php echo $caregiver['name']; ?></h2>
            <div class="caregiver-personal-info-details">
                <span class="caregiver-personal-info-tag">Verified</span>
                <p class="consultant-rating">
                    <span class="rating-stars"><?php echo $caregiver['rating']; ?></span>
                </p>
                <p><?php echo $caregiver['gender']; ?></p>
                <p>Rs <?php echo $caregiver['price']; ?> per day</p>
            </div>

            <!-- Caregiver Tags Section -->
            <div class="caregiver-tags-section">
                <div class="tags-group">
                    <span class="tag"><?php echo $caregiver['region']; ?></span>
                </div>
                <div class="tags-group">
                    <span class="tag"><?php echo $caregiver['type']; ?></span>
                </div>
                <div class="tags-group">
                    <span class="tag"><?php echo $caregiver['speciality']; ?></span>
                </div>
            </div>
        </div>
        <div class="caregivers-read-more">
            <a href="<?php echo URLROOT; ?>/careseeker/viewCaregiverProfile">View</a>
        </div>
    </div>
    <?php
}


?>

    
  </div>
  <div class="caregivers-see-more">
    <button id="see-more-btn">See More</button>
</div>
    </div>
</container>

<?php require APPROOT.'/views/includes/footer.php'; ?>