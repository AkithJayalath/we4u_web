<nav class="navbar">
        <div class="logo">
            <a href="<?php echo URLROOT?>/">WE<span>4</span>U</a>
        </div>
        <ul class="nav-links">
            <li><a href="#">Caregivers</a></li>
            <li><a href="#">Caregivers</a></li>
            <li><a href="#">Consultants</a></li>
            <li><a href="#">Blogs</a></li>
            <li class="separator">|</li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Help and Advice</a></li>
        </ul>
        <div class="contact-signin">
            <span class="contact-number">
                <img src="/we4u/public/images/phone_icon.png" alt="Phone" class="phone-icon"> 011 057 4115
            </span> 
            
            <?php if(isset($_SESSION['user_id'])) : ?>
                <!-- Show "Logout" button if the user is logged in -->
                <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/logout'">Logout</button>
            <?php else : ?>
                    <?php
                    // Check if we're on the login page
                    $current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); // Ensures only the path name is compared
                    if ($current_page == 'login') : ?>
                        <!-- Show "Register" button if on the login page -->
                        <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/register'">Register</button>
                    <?php else : ?>
                        <!-- Show "Sign In" button if not on the login page -->
                        <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/login'">Sign In</button>
                    <?php endif; ?>
            <?php endif; ?>

        </div>
    </nav>
    <div class="bottom space"></div>
    
    