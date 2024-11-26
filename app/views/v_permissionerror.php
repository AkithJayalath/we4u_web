<?php
$required_styles = [
    'permissionerror',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>
<container>
    <div class="permission-error-container">
        <div class="error-content">
            <div class="error-icon">🚫</div>
            <h1>Access Denied</h1>
            <h2>Error 403: Permission Denied</h2>
            <p>You don't have permission to access this page.</p>
            <div class="error-details">
                <p>User: <?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Not logged in'; ?></p>
                <p>Requested URL: <?php echo $_SERVER['REQUEST_URI']; ?></p>
                <p>Time: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            <a href="<?php echo URLROOT; ?>" class="home-button">Return to Home</a>
        </div>
    </div>
</container>
