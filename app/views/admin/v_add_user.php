<?php 
    $required_styles = [
        'admin/add_user',
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
    <div class="ad-users-header">
        <h1>Add New User</h1>
    </div>
    
    <main class="ad-users-main-content">
        <div class="ad-users-stat-card">
            <form class="add-user-form" action="<?php echo URLROOT; ?>/admin/adduser" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="ad-users-search-bar" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" required />
                    <span class="error"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="ad-users-search-bar"
                        value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>"
                        required
                    />
                    <span class="error"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="ad-users-filter-select" required>
                        <option value="">Select Role</option>
                        <option value="moderator">Moderator</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="ad-users-search-bar"
                        required
                    />
                    <span class="error"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
                </div>

                <div class="form-group">
                    <label for="verify_password">Verify Password</label>
                    <input
                        type="password"
                        id="verify_password"
                        name="verify_password"
                        class="ad-users-search-bar"
                        required
                    />
                    <span class="error"><?php echo isset($data['verify_password_err']) ? $data['verify_password_err'] : ''; ?></span>
                </div>

                <div class="form-buttons">
                    <button
                        type="button"
                        class="ad-users-btn-export"
                        onclick="window.location.href='<?php echo URLROOT; ?>/admin/user_detailes'"
                    >
                        Cancel
                    </button>
                    <button type="submit" class="ad-users-btn-add">Add User</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
