<?php 
    $required_styles = [
        'components/footer_styles',
        
        
    ];
    echo loadCSS($required_styles);
?>

<script src="<?php echo URLROOT; ?>/js/navbar.js"></script>
</body>
<footer>
  <div class="footer-container">
    <div class="footer-left">
    <div class="logo">
        <a href="<?php echo URLROOT ?>/home/">WE<span>4</span>U</a>
    </div>
      <p>Copyright © 2024. WE4U. All rights reserved.</p>
    </div>
    
    <div class="footer-right">
      <div class="footer-links">
        <h3>Services</h3>
        <ul>
          <li><a href="#">Email Marketing</a></li>
          <li><a href="#">Campaigns</a></li>
          <li><a href="#">Branding</a></li>
          <li><a href="#">Offline</a></li>
        </ul>
      </div>
      <div class="footer-links">
        <h3>About</h3>
        <ul>
          <li><a href="#story">Our Story</a></li>
          <li><a href="#ben">Benefits</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#car">Careers</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-social">
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>



</html>