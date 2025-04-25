<?php 
    $required_styles = [
        'admin/userdetails',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</div>
  <page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
  <div class="ad-users-wrapper">
  <div class="ad-users-header">
          <h1>User Details</h1>
      </div>
      <main class="ad-users-main-content">
          <!-- Stats cards section -->
        <div class="ad-users-stats-container">
            <div class="ad-users-stat-card">
                <div class="ad-users-stat-main">
                    <div class="ad-users-stat-details">
                        <h3>All Users</h3>
                        <div class="ad-users-stat-value"><?php echo number_format($data['totalUsers']); ?></div>
                    </div>
                    <div class="ad-users-stat-icon ad-users-blue">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="ad-users-stat-card">
                <div class="ad-users-stat-main">
                    <div class="ad-users-stat-details">
                        <h3>Caregivers</h3>
                        <div class="ad-users-stat-value"><?php echo number_format($data['caregivers']); ?></div>
                    </div>
                    <div class="ad-users-stat-icon ad-users-green">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                </div>
            </div>

            <div class="ad-users-stat-card">
                <div class="ad-users-stat-main">
                    <div class="ad-users-stat-details">
                        <h3>Consultants</h3>
                        <div class="ad-users-stat-value"><?php echo number_format($data['consultants']); ?></div>
                    </div>
                    <div class="ad-users-stat-icon ad-users-red">
                        <i class="fas fa-user-md"></i>
                    </div>
                </div>
            </div>

            <div class="ad-users-stat-card">
                <div class="ad-users-stat-main">
                    <div class="ad-users-stat-details">
                        <h3>Careseekers</h3>
                        <div class="ad-users-stat-value"><?php echo number_format($data['careseekers']); ?></div>
                    </div>
                    <div class="ad-users-stat-icon ad-users-green">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>

            <div class="ad-users-stat-card">
                <div class="ad-users-stat-main">
                    <div class="ad-users-stat-details">
                        <h3>Pending Users</h3>
                        <div class="ad-users-stat-value"><?php echo number_format($data['pendingUsers']); ?></div>
                    </div>
                    <div class="ad-users-stat-icon ad-users-yellow">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>

            <div class="ad-users-stat-card">
                <div class="ad-users-stat-main">
                    <div class="ad-users-stat-details">
                        <h3>Rejected Users</h3>
                        <div class="ad-users-stat-value"><?php echo number_format($data['rejectedUsers']); ?></div>
                    </div>
                    <div class="ad-users-stat-icon ad-users-red">
                        <i class="fas fa-user-times"></i>
                    </div>
                </div>
            </div>

            <a href="<?php echo URLROOT; ?>/admin/user_detailes<?php echo $data['showFlagged'] ? '' : '?flagged=true'; ?>" class="ad-users-btn-flagged <?php echo $data['showFlagged'] ? 'active' : ''; ?>">
                <div class="ad-users-stat-card">
                    <div class="ad-users-stat-main">
                        <div class="ad-users-stat-details">
                            <h3>Flaged Users</h3>
                            <p>
                            <div class="ad-users-stat-value"><?php echo number_format($data['flaggedUsers']); ?> </div>
                            </p>

                        </div>
                        <div class="ad-users-stat-icon ad-users-red">
                            <i class="fas fa-flag"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>


          <!-- Filters section -->
          <div class="ad-users-filters">
              <div class="ad-users-table-controls">
                  <select id="role-filter" class="ad-users-filter-select">
                      <option value="">All Roles</option>
                      <option value="Admin">Admin</option>
                      <option value="Moderator">Moderator</option>
                      <option value="Careseeker">Careseeker</option>
                      <option value="Caregiver">Caregiver</option>
                      <option value="Consultant">Consultant</option>
                  </select>
                    <!-- Update the search wrapper to include the flagged button -->
                    <div class="ad-users-search-wrapper">
                        <i data-lucide="search" class="ad-users-search-icon"></i>
                        <input type="search" id="user-search" class="ad-users-user-search" placeholder="Search by name or email" />
                    </div>
              </div>
              <div class="ad-users-action-group">
                  <button class="ad-users-btn-export" id="export-users">
                      <i data-lucide="download" class="ad-users-btn-icon"></i>
                      Export
                  </button>
                  <a href="<?php echo URLROOT; ?>/admin/adduser">
                    <button class="ad-users-btn-add">
                        <i data-lucide="plus" class="ad-users-btn-icon"></i>
                        Add New User
                    </button>
                  </a>
              </div>
          </div>

          <!-- Table section with horizontal scroll -->
          <div class="ad-users-table-container">
              <table class="ad-users-users-table">
                  <thead>
                      <tr>
                          <th class="sortable" data-sort="username">USER <i class="sort-icon" data-lucide="chevron-down"></i></th>
                          <th class="sortable" data-sort="role">ROLE <i class="sort-icon" data-lucide="chevron-down"></i></th>
                          <th class="sortable" data-sort="user_id">USERID <i class="sort-icon" data-lucide="chevron-down"></i></th>
                          <th class="sortable" data-sort="email">EMAIL <i class="sort-icon" data-lucide="chevron-down"></i></th>
                          <th class="sortable" data-sort="status">STATUS <i class="sort-icon" data-lucide="chevron-down"></i></th>
                      </tr>
                  </thead>
                  <tbody id="users-table-body">
                    <?php foreach($data['users'] as $user):?>
                      <tr class="user-row" 
                          data-username="<?php echo strtolower($user->username); ?>" 
                          data-email="<?php echo strtolower($user->email); ?>"
                          data-role="<?php echo $user->role; ?>"
                          data-userid="<?php echo $user->user_id; ?>">
                          <td class="ad-users-user-cell">
                                <a href="<?php echo URLROOT; ?>/operator/viewmoderator">
                              
                            <img 
                                src="<?php echo URLROOT; ?>/public/images/<?php echo strtolower($user->role); ?>-logo.png"
                                alt="User"
                                class="ad-users-user-avatar"
                              />
                              </a>
                              <a href="<?php echo URLROOT; ?>/operator/viewmoderator">
                              <div class="ad-users-user-info">
                                  <div class="ad-users-user-name"><?php echo $user->username; ?></div>
                              </div>
                            </a>
                          </td>
                          <td><?php echo $user->role;?></td>
                          <td><?php 
                                if($user->role =='Caregiver'){
                                    echo '#CG'.$user->user_id;
                                }elseif($user->role == 'Careseeker'){
                                    echo '#CS'.$user->user_id;
                                }elseif($user->role == 'Consultant'){
                                    echo '#CO'.$user->user_id;
                                }elseif($user->role == 'Moderator'){
                                    echo '#MO'.$user->user_id;
                                }else{
                                    echo '#AD'.$user->user_id;
                                }
                          ?></td>
                          <td><?php echo $user->email; ?></td>
                          <td>
                              <span class="ad-users-status ad-users-active">Active</span>
                          </td>
                      </tr>
                      <?php endforeach;?>
                  </tbody>
              </table>

              <!-- No results message -->
              <div id="no-results-message" style="display: none; text-align: center; padding: 20px; color: #666;">
                  No users found matching your search criteria.
              </div>

          </div>
      </main>
  </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const userSearch = document.getElementById('user-search');
    const roleFilter = document.getElementById('role-filter');
    const tableBody = document.getElementById('users-table-body');
    const noResultsMessage = document.getElementById('no-results-message');
    const sortableHeaders = document.querySelectorAll('th.sortable');
    
    // Current sort state
    let currentSort = {
        column: null,
        direction: 'asc'
    };
    
    // Add event listener for search input
    userSearch.addEventListener('input', filterUsers);
    
    // Add event listener for role filter
    roleFilter.addEventListener('change', filterUsers);
    
    // Add event listeners for sortable headers
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.getAttribute('data-sort');
            sortUsers(column);
        });
    });
    
    // Function to filter users based on search input and role filter
    function filterUsers() {
        const searchTerm = userSearch.value.toLowerCase().trim();
        const selectedRole = roleFilter.value;
        const rows = tableBody.querySelectorAll('tr.user-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const username = row.getAttribute('data-username').toLowerCase();
            const email = row.getAttribute('data-email').toLowerCase();
            const role = row.getAttribute('data-role');
            
            // Check if row matches search term and role filter
            const matchesSearch = searchTerm === '' || 
                                 username.includes(searchTerm) || 
                                 email.includes(searchTerm);
            
            const matchesRole = selectedRole === '' || role === selectedRole;
            
            // Show or hide row based on filters
            if (matchesSearch && matchesRole) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show or hide no results message
        if (visibleCount === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }
    
    // Function to sort users
    function sortUsers(column) {
        const rows = Array.from(tableBody.querySelectorAll('tr.user-row'));
        
        // Determine sort direction
        let direction = 'asc';
        if (currentSort.column === column) {
            direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        }
        
        // Update sort icons
        updateSortIcons(column, direction);
        
        // Sort the rows
        rows.sort((a, b) => {
            let valueA = a.getAttribute('data-' + column).toLowerCase();
            let valueB = b.getAttribute('data-' + column).toLowerCase();
            
            // Special handling for user_id (numeric sorting)
            if (column === 'user_id') {
                valueA = parseInt(a.getAttribute('data-userid'));
                valueB = parseInt(b.getAttribute('data-userid'));
                return direction === 'asc' ? valueA - valueB : valueB - valueA;
            }
            
            // String comparison for other columns
            if (direction === 'asc') {
                return valueA.localeCompare(valueB);
            } else {
                return valueB.localeCompare(valueA);
            }
        });
        
        // Reorder the rows in the table
        rows.forEach(row => {
            tableBody.appendChild(row);
        });
        
        // Update current sort state
        currentSort.column = column;
        currentSort.direction = direction;
    }
    
    // Function to update sort icons

});

</script>
