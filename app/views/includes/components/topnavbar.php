<nav class="navbar">
    <div class="logo">
        <a href="<?php echo URLROOT ?>/">WE<span>4</span>U</a>
    </div>
    <div class="menu-toggle" id="mobile-menu">
        &#9776; <!-- Hamburger Icon -->
    </div>
    <ul class="nav-links">
        <li><a href="#">Caregivers</a></li>
        <li><a href="<?php echo URLROOT; ?>/pages/consultantv">Consultants</a></li>
        <li><a href="#">Blogs</a></li>
        <li class="separator">|</li>
        <li><a href="<?php echo URLROOT; ?>/pages/about">About Us</a></li>
        <li><a href="#">Help and Advice</a></li>
    </ul>
    <div class="contact-signin">
        <span class="contact-number">
            <img src="/we4u/public/images/phone_icon.png" alt="Phone" class="phone-icon"> 011 057 4115
        </span>
        <?php if (isset($_SESSION['user_id'])): ?>
            <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/logout'">Logout</button>
        <?php else: ?>
            <button class="signin-btn" onclick="window.location.href='<?php echo URLROOT; ?>/users/login'">Sign In</button>
        <?php endif; ?>
    </div>
</nav>