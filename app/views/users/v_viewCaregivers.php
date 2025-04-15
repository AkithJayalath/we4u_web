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
        <form method="GET" action="<?php echo URLROOT; ?>/careseeker/viewCaregivers">
            <div class="filters">
              <label for="region-filter">Region:</label>
              <select id="region-filter" name="region">
                <option value="">All</option>
                <?php 
                // Get all unique regions from the database
                $regions = $data['regions'] ?? [];
                foreach($regions as $region): ?>
                    <option value="<?php echo $region; ?>" <?php echo isset($_GET['region']) && $_GET['region'] == $region ? 'selected' : ''; ?>>
                        <?php echo $region; ?>
                    </option>
                <?php endforeach; ?>
              </select>

              <label for="type-filter">Type:</label>
              <select id="type-filter" name="type">
                <option value="">All</option>
                <option value="Short Term" <?php echo isset($_GET['type']) && $_GET['type'] == 'Short Term' ? 'selected' : ''; ?>>Short Term</option>
                <option value="Long Term" <?php echo isset($_GET['type']) && $_GET['type'] == 'Long Term' ? 'selected' : ''; ?>>Long Term</option>
              </select>

              <label for="speciality-filter">Speciality:</label>
              <input type="text" id="speciality-filter" name="speciality" placeholder="Type speciality" value="<?php echo $_GET['speciality'] ?? ''; ?>" />
            </div>

            <div class="sort-options">
              <label for="sort-by">Sort by:</label>
              <select id="sort-by" name="sort">
                <option value="">Select</option>
                <option value="rating" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'rating' ? 'selected' : ''; ?>>Rating</option>
                <option value="price-asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price-asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price-desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price-desc' ? 'selected' : ''; ?>>Price: High to Low</option>
              </select>
            </div>

            <button type="submit" id="apply-filters" class="apply-button">Apply</button>
        </form>
      </div>
    </div>

    <div class="caregivers-container">
    <?php
    if(empty($data['caregivers'])) {
        echo '<div class="no-results">No caregivers found matching your criteria.</div>';
    } else {
        foreach($data['caregivers'] as $caregiver) {
            // Extract tags from comma-separated values
            $regions = explode(',', $caregiver->available_region);
            $specialties = explode(',', $caregiver->specialty);
            
            // Convert rating to stars (assuming rating is stored as a number 1-5)
            $ratingValue = $caregiver->rating ?? 0;
            $ratingStars = '';
            for($i = 1; $i <= 5; $i++) {
                if($i <= $ratingValue) {
                    $ratingStars .= '★'; // Filled star
                } else {
                    $ratingStars .= '☆'; // Empty star
                }
            }
            
            // Default image if none exists
            $imgPath = !empty($caregiver->profile_picture) 
                ? 'profile_imgs/'.$caregiver->profile_picture 
                : 'def_profile_pic2.jpg';
            
            // Extract first name and last name (assuming name is stored in format "First Last")
            $nameParts = explode(' ', $caregiver->username ?? 'Unknown User', 2);
            $fullName = count($nameParts) > 1 ? $nameParts[0] . ' ' . $nameParts[1] : $nameParts[0];
    ?>
    <div class="caregivers-post" 
         data-tags="region:<?php echo htmlspecialchars($caregiver->available_region); ?>,
                   type:<?php echo htmlspecialchars($caregiver->caregiver_type); ?>,
                   speciality:<?php echo htmlspecialchars($caregiver->specialty); ?>">
        <div class="caregivers-post-header">
            <div class="caregivers-post-header-image">
                <img src="<?php echo URLROOT . '/public/images/' . $imgPath; ?>" alt="<?php echo htmlspecialchars($fullName); ?>" />
            </div>
        </div>
        <div class="caregivers-post-content">
            <h2><?php echo htmlspecialchars($fullName); ?></h2>
            <div class="caregiver-personal-info-details">
                <?php if($caregiver->is_approved == 1): ?>
                <span class="caregiver-personal-info-tag">Verified</span>
                <?php endif; ?>
                <p class="consultant-rating">
                    <span class="rating-stars"><?php echo $ratingStars; ?></span>
                </p>
                <p><?php echo htmlspecialchars($caregiver->gender); ?></p>
                <p>Rs <?php echo htmlspecialchars($caregiver->payment_rate ?? 0); ?> per day</p>
            </div>

            <!-- Caregiver Tags Section -->
            <div class="caregiver-tags-section">
                <div class="tags-group">
                    <?php 
                    // Show first region (or default if none)
                    $primaryRegion = !empty($regions[0]) ? trim($regions[0]) : 'Not specified';
                    ?>
                    <span class="tag"><?php echo htmlspecialchars($primaryRegion); ?></span>
                </div>
                <div class="tags-group">
                    <span class="tag"><?php echo htmlspecialchars($caregiver->caregiver_type ?? 'Not specified'); ?></span>
                </div>
                <div class="tags-group">
                    <?php 
                    // Show first specialty (or default if none)
                    $primarySpecialty = !empty($specialties[0]) ? trim($specialties[0]) : 'General Care';
                    ?>
                    <span class="tag"><?php echo htmlspecialchars($primarySpecialty); ?></span>
                </div>
            </div>
        </div>
        <div class="caregivers-read-more">
            <a href="<?php echo URLROOT; ?>/careseeker/viewCaregiverProfile/<?php echo $caregiver->caregiver_id; ?>">View</a>
        </div>
    </div>
    <?php
        }
    }
    ?>
    </div>
    
    <!-- Pagination Controls -->
    <?php if($data['totalPages'] > 0): ?>
    <div class="pagination">
        <?php 
        $currentPage = $data['currentPage'];
        $totalPages = $data['totalPages'];
        
        // Build the query string for pagination links
        $queryParams = [];
        if(!empty($_GET['region'])) $queryParams['region'] = $_GET['region'];
        if(!empty($_GET['type'])) $queryParams['type'] = $_GET['type'];
        if(!empty($_GET['speciality'])) $queryParams['speciality'] = $_GET['speciality'];
        if(!empty($_GET['sort'])) $queryParams['sort'] = $_GET['sort'];
        
        // Previous button
        if($currentPage > 1) {
            $prevPageParams = $queryParams;
            $prevPageParams['page'] = $currentPage - 1;
            $prevPageUrl = URLROOT . '/users/viewCaregivers?' . http_build_query($prevPageParams);
            echo '<a href="' . $prevPageUrl . '" class="page-link">&laquo; Previous</a>';
        }
        
        // Page numbers
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        // Always show first page if we're not starting from it
        if($startPage > 1) {
            $firstPageParams = $queryParams;
            $firstPageParams['page'] = 1;
            $firstPageUrl = URLROOT . '/users/viewCaregivers?' . http_build_query($firstPageParams);
            echo '<a href="' . $firstPageUrl . '" class="page-link">1</a>';
            if($startPage > 2) {
                echo '<span class="page-ellipsis">...</span>';
            }
        }
        
        // Display page numbers
        for($i = $startPage; $i <= $endPage; $i++) {
            $pageParams = $queryParams;
            $pageParams['page'] = $i;
            $pageUrl = URLROOT . '/users/viewCaregivers?' . http_build_query($pageParams);
            
            if($i == $currentPage) {
                echo '<span class="page-link current-page">' . $i . '</span>';
            } else {
                echo '<a href="' . $pageUrl . '" class="page-link">' . $i . '</a>';
            }
        }
        
        // Always show last page if we're not ending on it
        if($endPage < $totalPages) {
            if($endPage < $totalPages - 1) {
                echo '<span class="page-ellipsis">...</span>';
            }
            $lastPageParams = $queryParams;
            $lastPageParams['page'] = $totalPages;
            $lastPageUrl = URLROOT . '/users/viewCaregivers?' . http_build_query($lastPageParams);
            echo '<a href="' . $lastPageUrl . '" class="page-link">' . $totalPages . '</a>';
        }
        
        // Next button
        if($currentPage < $totalPages) {
            $nextPageParams = $queryParams;
            $nextPageParams['page'] = $currentPage + 1;
            $nextPageUrl = URLROOT . '/users/viewCaregivers?' . http_build_query($nextPageParams);
            echo '<a href="' . $nextPageUrl . '" class="page-link">Next &raquo;</a>';
        }
        ?>
    </div>
    <?php endif; ?>
    
  </div>
</div>
</container>

<?php require APPROOT.'/views/includes/footer.php'; ?>