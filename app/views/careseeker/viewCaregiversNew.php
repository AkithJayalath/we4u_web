<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/careseeker/viewCaregiversNew.css">

<div class="caregivers-wrapper">
    <div class="caregivers-header">
        <h1>Available Caregivers</h1>
    </div>

    <!-- Filter and Sort Section -->
    <div class="filter-sort-bar">
        <form action="<?php echo URLROOT; ?>/careseekers/viewCaregivers" method="GET">
            <div class="filters">
                <div class="filter-group">
                    <label for="location">Location</label>
                    <select name="location" id="location">
                        <option value="">All Locations</option>
                        <?php foreach($data['locations'] as $location) : ?>
                            <option value="<?php echo $location; ?>" <?php echo isset($_GET['location']) && $_GET['location'] == $location ? 'selected' : ''; ?>>
                                <?php echo $location; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="experience">Experience</label>
                    <select name="experience" id="experience">
                        <option value="">Any Experience</option>
                        <option value="0-1" <?php echo isset($_GET['experience']) && $_GET['experience'] == '0-1' ? 'selected' : ''; ?>>0-1 years</option>
                        <option value="1-3" <?php echo isset($_GET['experience']) && $_GET['experience'] == '1-3' ? 'selected' : ''; ?>>1-3 years</option>
                        <option value="3-5" <?php echo isset($_GET['experience']) && $_GET['experience'] == '3-5' ? 'selected' : ''; ?>>3-5 years</option>
                        <option value="5+" <?php echo isset($_GET['experience']) && $_GET['experience'] == '5+' ? 'selected' : ''; ?>>5+ years</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="availability">Availability</label>
                    <select name="availability" id="availability">
                        <option value="">Any Availability</option>
                        <option value="full-time" <?php echo isset($_GET['availability']) && $_GET['availability'] == 'full-time' ? 'selected' : ''; ?>>Full Time</option>
                        <option value="part-time" <?php echo isset($_GET['availability']) && $_GET['availability'] == 'part-time' ? 'selected' : ''; ?>>Part Time</option>
                    </select>
                </div>
            </div>

            <div class="sort-group">
                <label for="sort">Sort By</label>
                <select name="sort" id="sort">
                    <option value="rating" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'rating' ? 'selected' : ''; ?>>Rating</option>
                    <option value="experience" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'experience' ? 'selected' : ''; ?>>Experience</option>
                    <option value="hourly_rate" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'hourly_rate' ? 'selected' : ''; ?>>Hourly Rate</option>
                </select>
            </div>

            <button type="submit" class="apply-button">Apply Filters</button>
        </form>
    </div>

    <?php if(empty($data['caregivers'])) : ?>
        <div class="no-results">
            <h2>No caregivers found matching your criteria</h2>
            <p>Try adjusting your filters or search terms</p>
        </div>
    <?php else : ?>
        <div class="caregivers-container">
            <?php foreach($data['caregivers'] as $caregiver) : ?>
                <div class="caregivers-post">
                    <div class="caregivers-post-header">
                        <div class="caregivers-post-header-image">
                            <img src="<?php echo $caregiver->profile_picture ? URLROOT . '/public/uploads/' . $caregiver->profile_picture : URLROOT . '/public/img/default-profile.png'; ?>" 
                                 alt="<?php echo $caregiver->first_name; ?>'s profile picture">
                        </div>
                    </div>

                    <div class="caregivers-post-content">
                        <h2><?php echo $caregiver->first_name . ' ' . $caregiver->last_name; ?></h2>

                        <div class="caregiver-personal-info-details">
                            <span class="caregiver-personal-info-tag">
                                <?php echo $caregiver->experience; ?> Years Experience
                            </span>
                            <div class="consultant-rating">
                                <div class="rating-stars">
                                    <?php
                                    $rating = round($caregiver->rating);
                                    for($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="caregiver-tags-section">
                            <div class="tags-group">
                                <span class="tag"><?php echo $caregiver->location; ?></span>
                            </div>
                            <div class="tags-group">
                                <span class="tag"><?php echo $caregiver->availability; ?></span>
                            </div>
                            <div class="tags-group">
                                <span class="tag">$<?php echo $caregiver->hourly_rate; ?>/hr</span>
                            </div>
                        </div>
                    </div>

                    <div class="caregivers-read-more">
                        <a href="<?php echo URLROOT; ?>/careseekers/viewCaregiver/<?php echo $caregiver->caregiver_id; ?>">
                            View Profile
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if($data['total_pages'] > 1) : ?>
            <div class="pagination">
                <?php if($data['current_page'] > 1) : ?>
                    <a href="?page=<?php echo $data['current_page']-1; ?><?php echo $data['query_string']; ?>" class="page-link">Previous</a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $data['total_pages']; $i++) : ?>
                    <?php if($i == 1 || $i == $data['total_pages'] || ($i >= $data['current_page'] - 2 && $i <= $data['current_page'] + 2)) : ?>
                        <a href="?page=<?php echo $i; ?><?php echo $data['query_string']; ?>" 
                           class="page-link <?php echo $i == $data['current_page'] ? 'current-page' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php elseif($i == 2 || $i == $data['total_pages'] - 1) : ?>
                        <span class="page-ellipsis">...</span>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($data['current_page'] < $data['total_pages']) : ?>
                    <a href="?page=<?php echo $data['current_page']+1; ?><?php echo $data['query_string']; ?>" class="page-link">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 