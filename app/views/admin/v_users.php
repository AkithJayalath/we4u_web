<?php 
    $required_styles = [
        'admin/userdetails',
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
  <div class="ad-users-wrapper">
      <main class="ad-users-main-content">
          <!-- Stats cards section -->
          <div class="ad-users-stats-container">
              <div class="ad-users-stat-card">
                  <div class="ad-users-stat-main">
                      <div class="ad-users-stat-details">
                          <h3>Session</h3>
                          <div class="ad-users-stat-value">21,459</div>
                          <div class="ad-users-stat-label">Total Users</div>
                      </div>
                      <div class="ad-users-stat-icon ad-users-blue">
                          
                      </div>
                  </div>
                  <div class="ad-users-stat-growth ad-users-positive">(+29%)</div>
              </div>

              <div class="ad-users-stat-card">
                  <div class="ad-users-stat-main">
                      <div class="ad-users-stat-details">
                          <h3>All Users</h3>
                          <div class="ad-users-stat-value">4,567</div>
                          <div class="ad-users-stat-label">Last week analytics</div>
                      </div>
                      <div class="ad-users-stat-icon ad-users-red">
                          <i data-lucide="user-plus"></i>
                      </div>
                  </div>
                  <div class="ad-users-stat-growth ad-users-positive">(+18%)</div>
              </div>

              <div class="ad-users-stat-card">
                  <div class="ad-users-stat-main">
                      <div class="ad-users-stat-details">
                          <h3>Active Users</h3>
                          <div class="ad-users-stat-value">19,860</div>
                          <div class="ad-users-stat-label">Last week analytics</div>
                      </div>
                      <div class="ad-users-stat-icon ad-users-green">
                          <i data-lucide="user-check"></i>
                      </div>
                  </div>
                  <div class="ad-users-stat-growth ad-users-negative">(-14%)</div>
              </div>

              <div class="ad-users-stat-card">
                  <div class="ad-users-stat-main">
                      <div class="ad-users-stat-details">
                          <h3>Pending Users</h3>
                          <div class="ad-users-stat-value">237</div>
                          <div class="ad-users-stat-label">Last week analytics</div>
                      </div>
                      <div class="ad-users-stat-icon ad-users-yellow">
                          <i data-lucide="user-cog"></i>
                      </div>
                  </div>
                  <div class="ad-users-stat-growth ad-users-positive">(+42%)</div>
              </div>
          </div>

          <!-- Filters section -->
          <div class="ad-users-filters">
              <div class="ad-users-table-controls">
                  <select class="ad-users-filter-select">
                      <option value="">Select Role</option>
                      <option value="admin">Admin</option>
                      <option value="moderator">Moderator</option>
                      <option value="user">Careseeker</option>
                      <option value="caregiver">Caregiver</option>
                      <option value="consultant">Consultant</option>
                  </select>
                  <select class="ad-users-entries-select">
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="50">50</option>
                  </select>
                  <div class="ad-users-search-wrapper">
                      <i data-lucide="search" class="ad-users-search-icon"></i>
                      <input type="search" class="ad-users-user-search" placeholder="Search User" />
                  </div>
              </div>
              <div class="ad-users-action-group">
                  <button class="ad-users-btn-export">
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
                          <th>USER</th>
                          <th>ROLE</th>
                          <th>USERID</th>
                          <th>EMAIL</th>
                          <th>STATUS</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php foreach($data['users'] as $user):?>
                        <!-- <span><?php echo $user->username; ?></span> -->
                      <tr>
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

                      
                      <!-- More table rows... -->
                      <?php endforeach;?>

                  </tbody>
              </table>

                <!--- pagination--->
              <div class="ad-users-pagination">
                  <button class="ad-users-page-btn">
                      <i data-lucide="chevrons-left"></i>
                  </button>
                  <button class="ad-users-page-btn">
                  <i data-lucide="chevron-left" style="color: #FF0000;"></i>


                  </button>
                  <button class="ad-users-page-btn">1</button>
                  <button class="ad-users-page-btn">2</button>
                  <button class="ad-users-page-btn ad-users-active">3</button>
                  <button class="ad-users-page-btn">4</button>
                  <button class="ad-users-page-btn">5</button>
                  <button class="ad-users-page-btn">
                      <i data-lucide="chevron-right"></i>
                  </button>
                  <button class="ad-users-page-btn" >
                      <i data-lucide="chevrons-right"></i>
                  </button>
              </div>
          </div>
      </main>
  </div>
</page-body-container>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.js"></script>
    <script>
      lucide.createIcons();
    </script>

<?php require APPROOT.'/views/includes/footer.php'; ?>
