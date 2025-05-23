<?php 
    $required_styles = [
        'careseeker/viewConsultantSessions',
        // 'components/sidebar',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="total-container">
        <div class="careseeker-profile-container">
            <h2>Your Consultant Sessions</h2>
            
            <!-- Sort and Filter Section -->
            <div class="filter-sort-section">
                <div class="filter-group">
                    <span class="filter-label">Date:</span>
                    <select class="filter-select" id="sort-date">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <span class="filter-label">Status:</span>
                    <select class="filter-select" id="filter-status">
                        <option value="all">All</option>
                        <option value="accepted">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <span class="filter-label">Elder Profile:</span>
                    <select class="filter-select" id="filter-elder">
                        <option value="all">All</option>
                        <?php if(isset($data['elder_profiles'])): ?>
    <?php foreach($data['elder_profiles'] as $profile): ?>
        <option value="<?= $profile->elder_id ?>">
            <?= htmlspecialchars($profile->first_name . ' ' . $profile->last_name) ?>
        </option>
    <?php endforeach; ?>
<?php endif; ?>
                    </select>
                </div>
                
                <button class="apply-filter-btn" id="apply-filters">Apply</button>
            </div>

            <!-- Sessions List -->
            <div id="sessions-container">
                <?php foreach ($data['sessions'] as $session): ?>
                    <?php
                        $consultantPic = !empty($session->consultant_pic)
                            ? URLROOT . '/public/images/profile_imgs/' . $session->consultant_pic
                            : URLROOT . '/public/images/def_profile_pic2.jpg';

                        $elderPic = !empty($session->elder_pic)
                            ? URLROOT . '/public/images/profile_imgs/' . $session->elder_pic
                            : URLROOT . '/public/images/def_profile_pic2.jpg';
                    ?>
                    <div class="profile-card" 
                         data-date="<?= strtotime($session->updated_at) ?>" 
                         data-status="<?= strtolower($session->status) ?>" 
                         data-elder="<?= $session->elder_id ?>">
                        <!-- Profile info section (images + text) -->
                        <div class="profile-info">
                            <!-- Horizontal image arrangement -->
                            <div class="image-container">
                                <div class="info-circle image1">
                                    <img src="<?= $elderPic ?>" alt="Elder Profile" />
                                </div>
                                <div class="info-circle image2">
                                    <img src="<?= $consultantPic ?>" alt="Consultant Profile" />
                                </div>
                            </div>
                            
                            <!-- Text information -->
                            <div class="profile-text">
                                <h4><?= htmlspecialchars($session->consultant_name) ?></h4>
                                <p class="session-id">Session ID: <?= $session->session_id ?></p>
                            </div>
                            <div class="profile-elder">
                                <h4><?= htmlspecialchars($session->elder_name) ?></h4>
                                <p class="session-id"><?= $session->relationship_to_careseeker ?></p>
                            </div>
                        </div>
                        
                        <!-- Status and action button -->
                        <div class="action-area">
                        <?php
    // Determine the appropriate CSS class based on status
    $statusClass = '';
    switch($session->status) {
        case 'accepted':
            $statusClass = 'accepted';
            $displayStatus = 'Ongoing';
            break;
        case 'cancelled':
            $statusClass = 'cancelled';
            $displayStatus = 'Cancelled';
            break;
        case 'completed':
            $statusClass = 'completed';
            $displayStatus = 'Completed';
            break;
    }
?>
<span class="tag <?= $statusClass ?>">
    <?= $displayStatus ?>
</span>
                            <button class="view-profile-btn" onclick="window.location.href='<?= URLROOT ?>/careseeker/viewConsultantSession/<?= $session->session_id ?>'">
                                View
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</page-body-container>

<!-- JavaScript for filtering and sorting -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyBtn = document.getElementById('apply-filters');
    
    applyBtn.addEventListener('click', function() {
        const sortDate = document.getElementById('sort-date').value;
        const filterStatus = document.getElementById('filter-status').value;
        const filterElder = document.getElementById('filter-elder').value;
        
        filterAndSortSessions(sortDate, filterStatus, filterElder);
    });
    
    // Initial sorting (newest first)
    filterAndSortSessions('newest', 'all', 'all');
    
    function filterAndSortSessions(sortDate, filterStatus, filterElder) {
        const sessionsContainer = document.getElementById('sessions-container');
        const sessions = Array.from(sessionsContainer.getElementsByClassName('profile-card'));
        
        // Filter sessions
        sessions.forEach(session => {
            const sessionStatus = session.getAttribute('data-status');
            const sessionElder = session.getAttribute('data-elder');
            let showSession = true;
            
            // Apply status filter
            if (filterStatus !== 'all' && sessionStatus !== filterStatus) {
                showSession = false;
            }
            
            // Apply elder filter
            if (filterElder !== 'all' && sessionElder !== filterElder) {
                showSession = false;
            }
            
            // Show or hide session
            session.style.display = showSession ? 'flex' : 'none';
        });
        
        // Sort visible sessions
        const visibleSessions = sessions.filter(session => session.style.display !== 'none');
        
        visibleSessions.sort((a, b) => {
            const dateA = parseInt(a.getAttribute('data-date'));
            const dateB = parseInt(b.getAttribute('data-date'));
            
            return sortDate === 'newest' ? dateB - dateA : dateA - dateB;
        });
        
        // Reorder sessions in the DOM
        visibleSessions.forEach(session => {
            sessionsContainer.appendChild(session);
        });
    }
});
</script>

<?php require APPROOT.'/views/includes/footer.php'?>