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
            </span> <?php 
        // Check if we're on the sign-in page
       
$current_page = basename($_SERVER['REQUEST_URI']);

        
        
        if ($current_page == 'login') : ?>
            <!-- Show "Register" button if on sign-in page -->
            <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT?>/users/register'">Register</button>
        <?php else : ?>
            <!-- Show "Sign In" button if not on sign-in page -->
            <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT?>/users/login'">Sign In</button>
        <?php endif; ?>
            

        </div>
    </nav>
    <div class="bottom space"></div>
    
    