<?php 
    $required_styles = [
        'careseeker/careseekerCreateProfile',
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
            <h2>Your Patients</h2>

            <!-- Profile card 1 -->
            <div class="profile-card">
            <img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Profile Picture" />   
                <div>
                    <h4>Jerome Bell</h4>
                   <span class="tag accepted">Patient</span>
                </div>
                <button class="view-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/editelderprofile'">View Profile</button>
                
            </div>


            <!-- Profile card 2 -->
            <div class="profile-card">
                <img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Profile Picture" />
                <div>
                    <h4>Jerome Bell</h4>
                    <span class="tag completed">Patient</span>
                </div>
                <button class="view-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/editelderprofile'">View Profile</button>
            </div>

            <!-- Profile card 3 -->
            <div class="profile-card">
                <img src="https://t3.ftcdn.net/jpg/02/00/90/24/360_F_200902415_G4eZ9Ok3Ypd4SZZKjc8nqJyFVp1eOD6V.jpg" alt="Profile Picture" />
                <div>
                    <h4>Sun Wukong</h4>
                    <span class="tag completed">Patient</span>
                </div>
                <button class="view-profile-btn" onclick="window.location.href='<?php echo URLROOT; ?>/consultant/editelderprofile'">View Profile</button>
            </div>
        </div>
    </div>
</page-body-container>


<?php require APPROOT.'/views/includes/footer.php'?>