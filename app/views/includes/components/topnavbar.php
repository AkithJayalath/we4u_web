<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<nav class="navbar">

    <!-- Logo -->
    <div class="logo">
        <a href="<?php echo URLROOT ?>/home/">WE<span>4</span>U</a>
    </div>

    <!-- Toogle menue -->
    <div class="menu-toggle" id="mobile-menu">
        &#9776; <!-- Hamburger Icon -->
    </div>

    <!-- Content that will be in the toggle menue when screen is small -->
    <div class="menu-content">
        <!-- Navlinks -->
        <ul class="nav-links">
            <?php if (isset($_SESSION['user_id'])) : ?>
                <?php

                $homeLink = '';
                switch ($_SESSION['user_role']) {
                    case 'Admin':
                        $homeLink = URLROOT . '/admin/';
                        break;
                    case 'Caregiver':
                        $homeLink = URLROOT . '/caregivers';
                        break;
                    case 'Moderator':
                        $homeLink = URLROOT . '/moderator/';
                        break;
                    case 'Consultant':
                        $homeLink = URLROOT . '/consultants/dashboard';
                        break;
                    case 'Careseeker':
                        $homeLink = URLROOT . '/careseeker/';
                        break;
                    default:
                        $homeLink = URLROOT . '/users/'; // Default fallback
                        break;
                }
                ?>
                <li><a href="<?php echo $homeLink; ?>">Home</a></li>
            <?php endif; ?>
            <li><a href="<?php echo URLROOT; ?>/users/viewCaregivers">Caregivers</a></li>
            <li><a href="<?php echo URLROOT; ?>/users/viewConsultants">Consultants</a></li>
            <li class="separator">|</li>
            <li><a href="<?php echo URLROOT; ?>/users/blog">Blogs</a></li>
            
            <li><a href="<?php echo URLROOT; ?>/pages/about">About Us</a></li>
            
        </ul>
        <!-- Profile section -->
        <?php if (isset($_SESSION['user_id'])) : ?>
            <div class="profile">
                <div class="user-name">
                    <?php echo $_SESSION['user_name']; ?>
                </div>
                <div class="pic">
                    <img src="<?= isset($_SESSION['user_profile_picture']) && $_SESSION['user_profile_picture']
                                    ? URLROOT . '/images/profile_imgs/' . $_SESSION['user_profile_picture']
                                    : URLROOT . '/images/def_profile_pic.jpg'; ?>"
                        alt="">
                </div>
            </div>
        <?php endif; ?>
        <!-- SignIn/logout section -->
        <div class="contact-signin">
            <?php if (isset($_SESSION['user_id'])): ?>
                <i class="fa-solid fa-bell notification" onclick="window.location.href='<?php echo URLROOT; ?>/notifications'"></i>
                <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/logout'">Logout</button>
                
            <?php else: ?>
                <span class="contact-number">
                
                    <img src="/we4u/public/images/phone_icon.png" alt="Phone" class="phone-icon"> 011 057 4115
                </span>
                <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/login'">Sign In</button>
            <?php endif; ?>
        </div>
    </div>
</nav>