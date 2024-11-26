<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/components/sidebar.css"> 
<script src="<?php echo URLROOT; ?>/js/sidebar.js" defer></script>

<?php 
  require_once APPROOT.'/controllers/SidebarController.php';
  $sidebarController = new SidebarController();
  $links = $sidebarController->getSidebarLinks();

?>

<nav id="sidebar">
  <ul>
      <li>
          <span class="logo">CareGiver</span>
          <button id="toggle-btn" onclick="toggleSidebar()">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#e8eaed"
              >
                <path
                  d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"
                />
              </svg>
          </button>
      </li>

      <!-- <?php echo $_SESSION['user_role'] ;?>
      <?php echo $_SESSION['user_name'] ;?> -->


      <?php foreach($links as $link): ?>
          <li class="<?php echo $link['active'] ? 'active' : ''; ?>">
              <a href="<?php echo $link['url']; ?>">
                  <?php echo $link['icon']; ?>
                  <span><?php echo $link['title']; ?></span>
              </a>
          </li>
      <?php endforeach; ?>
  </ul>
</nav>
