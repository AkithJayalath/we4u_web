<?php
// Dummy data for the consultant list (could be fetched from a database)
$consultants = [
    ['name' => 'Dr. John Smith', 'role' => 'Doctor', 'profile_pic' => 'profile.jpg', 'rating' => 4],
    ['name' => 'Therapist Emily Davis', 'role' => 'Therapist', 'profile_pic' => 'profile.jpg', 'rating' => 3],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    ['name' => 'Psychiatrist Sarah Lee', 'role' => 'Psychiatrist', 'profile_pic' => 'profile.jpg', 'rating' => 5],
    // Add more consultants here as needed
];

// Function to display star ratings
function displayStars($rating) {
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            echo '<span class="star">&#9733;</span>'; // Filled star
        } else {
            echo '<span class="star">&#9734;</span>'; // Empty star
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultants List</title>
    <link rel="stylesheet" href="list.css">
</head>
<body>
    <header class="header">
        <h1>Consultants List</h1>
    </header>

    <!-- Consultant List Grid Container -->
    <div class="consultants-grid">
        <?php foreach ($consultants as $consultant): ?>
            <div class="consultant-item">
                <div class="consultant-profile">
                    <img src="<?php echo $consultant['profile_pic']; ?>" alt="Profile Picture" class="consultant-photo">
                    <div class="consultant-info">
                        <h3><?php echo $consultant['name']; ?></h3>
                        <p><?php echo $consultant['role']; ?></p>
                    </div>
                    
                    <!-- Dynamic Star Rating -->
                    <div class="rating">
                        <?php displayStars($consultant['rating']); ?>
                    </div>

                    <a href="profile.php" class="view-btn">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
