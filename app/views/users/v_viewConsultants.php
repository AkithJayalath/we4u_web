<?php 
    $required_styles = [
        'careseeker/viewCaregiversNew',
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
        <form method="GET" action="<?php echo URLROOT; ?>/users/viewConsultants" id="filter-form">
            <div class="filters">
              <div class="filter-group">
                <label for="username-filter">Username</label>
                <input type="search" 
                       id="username-filter" 
                       class="live-search"
                       name="username" 
                       placeholder="Search by name" 
                       value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>" />
              </div>

              <div class="filter-group">
                <label for="region-filter">Region</label>
                <select id="region-filter" name="region">
                  <option value="">All</option>
                  <?php 
                  // Predefined Sri Lankan regions
                  $sriLankanRegions = [
                      'Colombo',
                      'Gampaha',
                      'Kalutara',
                      'Kandy',
                      'Matale',
                      'Nuwara Eliya',
                      'Galle',
                      'Matara',
                      'Hambantota',
                      'Jaffna',
                      'Kilinochchi',
                      'Mannar',
                      'Vavuniya',
                      'Mullaitivu',
                      'Batticaloa',
                      'Ampara',
                      'Trincomalee',
                      'Kurunegala',
                      'Puttalam',
                      'Anuradhapura',
                      'Polonnaruwa',
                      'Badulla',
                      'Moneragala',
                      'Ratnapura',
                      'Kegalle'
                  ];
                  
                  foreach($sriLankanRegions as $region): ?>
                      <option value="<?php echo htmlspecialchars($region); ?>" 
                              <?php echo (isset($_GET['region']) && $_GET['region'] == $region) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($region); ?>
                      </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="filter-group">
                <label for="speciality-filter">Speciality</label>
                <select id="speciality-filter" name="speciality">
                    <option value="">All</option>
                    <?php 
                    // Predefined specialization options
                    $specializationOptions = [
                        'Dementia Care',
                        'Wound Care',
                        'Wheelchair Care',
                        'Palliative Care',
                        'Post-Surgery Care',
                        'Diabetes Care',
                        'Parkinson\'s Care',
                        'Stroke Recovery Care',
                        'Elderly Care',
                        'Pediatric Care',
                        'Physical Therapy Assistance',
                        'Speech Therapy Assistance'
                    ];
                    
                    foreach ($specializationOptions as $specialization): ?>
                        <option value="<?php echo htmlspecialchars($specialization); ?>" 
                                <?php echo (isset($_GET['speciality']) && $_GET['speciality'] == $specialization) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($specialization); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
              </div>

              

              
            </div>

            <div class="sort-options">
              <div class="filter-group">
                <label for="sort-by">Sort by</label>
                <select id="sort-by" name="sort">
                  <option value="">Select</option>
                  <option value="rating" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'rating' ? 'selected' : ''; ?>>Rating</option>
                  
                </select>
              </div>

              <button type="submit" id="apply-filters" class="apply-button">Apply</button>
            </div>
        </form>
      </div>
    </div>

    <div class="caregivers-container">
    <?php
    if(empty($data['consultants'])) {
        echo '<div class="no-results">No consultants found matching your criteria.</div>';
    } else {
        foreach($data['consultants'] as $consultant) {
            $regions = explode(',', $consultant->available_regions);
            $specialties = explode(',', $consultant->specializations);
            
            $ratingValue = $consultant->rating ?? 0;
            $decimal = $ratingValue - floor($ratingValue);
            $ratingStars = '';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= floor($ratingValue)) {
                    // Full star
                    $ratingStars .= '<i class="fa-solid fa-star active"></i>';
                } elseif ($i == ceil($ratingValue) && $decimal >= 0.5) {
                    // Half star
                    $ratingStars .= '<i class="fa-solid fa-star-half-stroke active"></i>';
                } else {
                    // Empty star
                    $ratingStars .= '<i class="fa-regular fa-star"></i>';
                }
            }
            
            $imgPath = !empty($consultant->profile_picture) 
                ? 'profile_imgs/'.$consultant->profile_picture 
                : 'def_profile_pic2.jpg';
            
            $nameParts = explode(' ', $consultant->username ?? 'Unknown User', 2);
            $fullName = count($nameParts) > 1 ? $nameParts[0] . ' ' . $nameParts[1] : $nameParts[0];
    ?>
    <div class="caregivers-post" 
         data-tags="region:<?php echo htmlspecialchars($consultant->available_regions); ?>,
                   speciality:<?php echo htmlspecialchars($consultant->specializations); ?>">
        <div class="caregivers-post-header">
            <div class="caregivers-post-header-image">
                <img src="<?php echo URLROOT . '/public/images/' . $imgPath; ?>" alt="<?php echo htmlspecialchars($fullName); ?>" />
            </div>
        </div>
        <div class="caregivers-post-content">
            <h2><?php echo htmlspecialchars($fullName); ?></h2>
            <div class="caregiver-personal-info-details">
                <?php if($consultant->is_approved == 'approved'): ?>
                <span class="caregiver-personal-info-tag">Verified</span>
                <?php else: ?>
                <span class="caregiver-personal-info-tag-2"></span>
                <?php endif; ?>
                <p class="consultant-rating">
                    <span class="rating-stars"><?php echo $ratingStars; ?></span>
                    <span class="rating-value">(<?php echo number_format($ratingValue, 1); ?>)</span>
                </p>
                <p><?php echo htmlspecialchars($consultant->gender); ?></p>
                <p>Rs <?php echo htmlspecialchars($consultant->payment_details ?? 0); ?> per hour</p>
            </div>

            <div class="caregiver-tags-section">
                <div class="tags-group">
                    <?php 
                    $primaryRegion = !empty($regions[0]) ? trim($regions[0]) : 'Not specified';
                    ?>
                    <span class="tag"><?php echo htmlspecialchars($primaryRegion); ?></span>
                </div>
                <div class="tags-group">
                    <span class="tag"><?php echo htmlspecialchars($consultant->expertise ?? 'Not specified'); ?></span>
                </div>
                <div class="tags-group">
                    <?php 
                    $primarySpecialty = !empty($specialties[0]) ? trim($specialties[0]) : 'General Care';
                    ?>
                    <span class="tag"><?php echo htmlspecialchars($primarySpecialty); ?></span>
                </div>
            </div>
        </div>
        <div class="caregivers-read-more">
            <a href="<?php echo URLROOT; ?>/careseeker/viewConsultantProfile/<?php echo $consultant->consultant_id; ?>">View</a>
        </div>
    </div>
    <?php
        }
    }
    ?>
    </div>
    
    <?php if($data['totalPages'] > 0): ?>
    <div class="pagination">
        <?php 
        $currentPage = $data['currentPage'];
        $totalPages = $data['totalPages'];
        
        $queryParams = [];
        if(!empty($_GET['username'])) $queryParams['username'] = $_GET['username'];
        if(!empty($_GET['region'])) $queryParams['region'] = $_GET['region'];
        if(!empty($_GET['speciality'])) $queryParams['speciality'] = $_GET['speciality'];
        if(!empty($_GET['sort'])) $queryParams['sort'] = $_GET['sort'];
        
        if($currentPage > 1) {
            $prevPageParams = $queryParams;
            $prevPageParams['page'] = $currentPage - 1;
            $prevPageUrl = URLROOT . '/consultant/viewConsultants?' . http_build_query($prevPageParams);
            echo '<a href="' . $prevPageUrl . '" class="page-link">&laquo; Previous</a>';
        }
        
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        if($startPage > 1) {
            $firstPageParams = $queryParams;
            $firstPageParams['page'] = 1;
            $firstPageUrl = URLROOT . '/consultant/viewConsultants?' . http_build_query($firstPageParams);
            echo '<a href="' . $firstPageUrl . '" class="page-link">1</a>';
            if($startPage > 2) {
                echo '<span class="page-ellipsis">...</span>';
            }
        }
        
        for($i = $startPage; $i <= $endPage; $i++) {
            $pageParams = $queryParams;
            $pageParams['page'] = $i;
            $pageUrl = URLROOT . '/consultant/viewConsultants?' . http_build_query($pageParams);
            
            if($i == $currentPage) {
                echo '<span class="page-link current-page">' . $i . '</span>';
            } else {
                echo '<a href="' . $pageUrl . '" class="page-link">' . $i . '</a>';
            }
        }
        
        if($endPage < $totalPages) {
            if($endPage < $totalPages - 1) {
                echo '<span class="page-ellipsis">...</span>';
            }
            $lastPageParams = $queryParams;
            $lastPageParams['page'] = $totalPages;
            $lastPageUrl = URLROOT . '/consultant/viewConsultants?' . http_build_query($lastPageParams);
            echo '<a href="' . $lastPageUrl . '" class="page-link">' . $totalPages . '</a>';
        }
        
        if($currentPage < $totalPages) {
            $nextPageParams = $queryParams;
            $nextPageParams['page'] = $currentPage + 1;
            $nextPageUrl = URLROOT . '/consultant/viewConsultants?' . http_build_query($nextPageParams);
            echo '<a href="' . $nextPageUrl . '" class="page-link">Next &raquo;</a>';
        }
        ?>
    </div>
    <?php endif; ?>
    
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.live-search');
    const consultantCards = document.querySelectorAll('.caregivers-post');
    const cardsContainer = document.querySelector('.caregivers-container');

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let foundResults = false;

        consultantCards.forEach(card => {
            const name = card.querySelector('h2').textContent.toLowerCase();
            const isMatch = name.includes(searchTerm);
            card.style.display = isMatch ? 'flex' : 'none';
            if (isMatch) foundResults = true;
        });

        let noResultsMsg = cardsContainer.querySelector('.no-results');
        if (!foundResults) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'no-results';
                noResultsMsg.textContent = 'No consultants found matching your search.';
                cardsContainer.appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    searchInput.addEventListener('input', performSearch);
    searchInput.addEventListener('keyup', performSearch);
});

  if (performance.navigation.type === 1) {
    // Page was refreshed
    window.location.href = "<?php echo URLROOT; ?>/users/viewConsultants";
  }

</script>

</container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
