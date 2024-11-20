<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<div class="care-container">
  <div class="left-section">
    <h1>The place to get home care sorted</h1>
    <p>Elder is an award-winning marketplace connecting families and self-employed home carers.</p>
    <div class="buttons">
      <button class="find-carer-btn">Find a carer</button>
      <a href="tel:03308289720" class="phone-number">0330 828 9720</a>
    </div>
    <div class="reviews">
      <div class="family-count">
        <img src="<?php echo URLROOT;?>/images/login.png" alt="family1">
        <img src="<?php echo URLROOT;?>/images/login.png" alt="family2">
        <img src="<?php echo URLROOT;?>/images/login.png" alt="family3">
        <p>Used by 5,000+ families across Great Britain</p>
      </div>
      <p class="trustpilot">
        Excellent <span class="stars">★★★★★</span> 684 reviews on <span class="trustpilot-logo">Trustpilot</span>
      </p>
    </div>
  </div>
  <div class="right-section">
    <div class="video-container">
    <img src="<?php echo URLROOT;?>/images/login.png" alt="family1">
    </div>
  </div>

</div>

<div class="homepage-container">
   <!-- HTML -->
<div class="info-section">
  <div class="info-cards">
    <div class="info-card">
      <i class="icon">🌟</i>
      <h3>More value</h3>
      <p>On average, services facilitated by Elder are 35% cheaper than traditional alternatives.</p>
    </div>
    <div class="info-card">
      <i class="icon">🔧</i>
      <h3>More control</h3>
      <p>Whenever and however you need it, you set the scope of your service.</p>
    </div>
    <div class="info-card">
      <i class="icon">💡</i>
      <h3>More choice</h3>
      <p>Pick your favourite self-employed carer from personalised matches.</p>
    </div>
    <div class="info-card">
      <i class="icon">🤝</i>
      <h3>More support</h3>
      <p>Use our platform to plan and manage care from anywhere, and chat to 5* rated support teams.</p>
    </div>
  </div>
</div>

<div class="services-section">
  <h2>Elders care services</h2>
  <p>Our technology makes searching for a self-employed carer easier than ever before. Browse and chat to carers, and invite family members to help with the decision. Then, use your all-in-one MyElder account to control your care experience.</p>
  <p>While we provide the tools, the agreement for how care takes place will sit with you and your self-employed carer. This keeps care personal, allowing you to work together to protect a loved one's routine and way of life.</p>
  <p><strong>All carer profiles include:</strong> An introductory video 📹, background checks and references 🔍, reviews from families ⭐</p>
  
  <div class="service-cards">
    <div class="service-card">
      <img src="<?php echo URLROOT;?>/images/login.png" alt="Full-time live-in care">
      <h3>Become a CareSeeker</h3>
      <p>24hr support in your home</p>
      <p>A carer moves in to provide on-going 24hr support at home.</p>
      <button onclick="window.location.href='<?php echo URLROOT; ?>/users/register'">Register</button>
      
    </div>
    <div class="service-card">
      <img src="<?php echo URLROOT;?>/images/login.png" alt="Short-term live-in care">
      <h3>Become a CareGiver</h3>
      <p>24hr support when you need it</p>
      <p>A carer moves in to provide 24hr support, for as little as 3 days at a time.</p>
      <button onclick="window.location.href='<?php echo URLROOT; ?>/caregivers/register'">Register</button>
      
    </div>
    <div class="service-card">
      <img src="<?php echo URLROOT;?>/images/login.png" alt="Visiting care">
      <h3>Become a Consultant</h3>
      <p>Flexible home visits</p>
      <p>A carer will visit for a few hours on your chosen days, at a pre-agreed time.</p>
      <button>Register</button>
      
    </div>
  </div>
</div>


    
</div>

<?php require APPROOT.'/views/includes/footer.php';?>
