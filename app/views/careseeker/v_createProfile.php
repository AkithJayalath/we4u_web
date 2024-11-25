<?php 
    $required_styles = [
        'careseeker/careseekerCreateProfile',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    <div class="total-container">
        <div class="careseeker-profile-container">
            <h2>Your Profiles</h2>

            <?php if (!empty($data['elders'])): ?>
    <?php foreach ($data['elders'] as $elder): ?>
        <div class="profile-card">
            <img src="<?php echo !empty($elder->profile_picture) ? URLROOT . '/' . $elder->profile_picture : URLROOT . '/images/def_profile_pic.png'; ?>" alt="Elder Image">
            <div>
                <h4><?php echo htmlspecialchars($elder->first_name . ' ' . $elder->last_name); ?></h4>
                <p><?php echo htmlspecialchars($elder->relationship_to_careseeker); ?></p>
            </div>
            <button class="view-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/viewElderProfile/<?php echo $elder->id; ?>'">View Profile</button>
            <!-- Add a form to submit the delete request -->
            <form action="<?php echo URLROOT; ?>/careseeker/deleteElderProfile/<?php echo $elder->elder_id; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this profile?');">
                <button type="submit" class="delete-profile-btn">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No elder profiles found.</p>
<?php endif; ?>


            <!-- Create Profile button -->
            <button class="create-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/createProfile'">Create Profile</button>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
