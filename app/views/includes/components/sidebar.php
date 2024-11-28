<?php
require_once APPROOT . '/controllers/SidebarController.php';
$sidebarController = new SidebarController();
$sidebarLinks = $sidebarController->getSidebarLinks();
$logoPath = $sidebarController->getSidebarLogo();
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/sidebar.css"> 
<script src="<?php echo URLROOT; ?>/js/sidebar.js" defer></script>
<nav id="sidebar">
    <header class="sidebar-header">
      <new  class="header-logo ">
        <!-- <img src="<?php echo URLROOT; ?>/images/image1.png" alt="CareGiver Logo" /> -->
        <img src="<?php echo URLROOT . $logoPath; ?>" alt="Role-specific Logo" />
      </new>
      <button class="toggler sidebar-toggler" onclick="toggleSidebar()">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="24px"
          viewBox="0 -960 960 960"
          width="24px"
          fill="#000000"
          color="#e8eaed"
        >
          <path
            d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"
          />
        </svg>
      </button>
    </header>

    <div class="sidebar-nav">
        <ul class="nav-list primary-nav">
            <?php foreach($sidebarLinks as $link): ?>
                <li class="nav-item">
                    <a href="<?php echo $link['url']; ?>" class="nav-link">
                        <?php echo $link['icon']; ?>
                        <span class="nav-label"><?php echo $link['title']; ?></span>
                        <span class="nav-tooltip"><?php echo $link['title']; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
