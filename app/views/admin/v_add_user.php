<?php 
    $required_styles = [
        'admin/ad-user',
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

<main class="ad-users-main-content">
      <div class="ad-users-stat-card" style="max-width: 600px; margin: 0 auto">
        <h2 style="margin-bottom: 20px; color: #333">Add New User</h2>
        <form class="add-user-form">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" class="ad-users-search-bar" required />
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              class="ad-users-search-bar"
              required
            />
          </div>

          <div class="form-group">
            <label for="role">Role</label>
            <select id="role" class="ad-users-filter-select" required>
              <option value="">Select Role</option>
              <option value="moderator">Moderator</option>
              <option value="careseeker">Careseeker</option>
            </select>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              class="ad-users-search-bar"
              required
            />
          </div>

          <div class="form-group">
            <label for="verify-password">Verify Password</label>
            <input
              type="password"
              id="verify-password"
              class="ad-users-search-bar"
              required
            />
          </div>

          <div class="form-buttons">
            <button
              type="button"
              class="ad-users-btn-export"
              onclick="window.location.href='index.html'"
            >
              Cancel
            </button>
            <button type="submit" class="ad-users-btn-add">Add User</button>
          </div>
        </form>
      </div>
</main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.js"></script>
    <script>
      lucide.createIcons();
    </script>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>