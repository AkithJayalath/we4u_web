<?php require APPROOT.'/views/includes/header.php';?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/components/home.css"> 

<body>
    

    <section class="hero" id="home">
        <div class="hero-left">
            <h1>The place to get home care sorted</h1>
            <p>Experience peace of mind with our professional and compassionate elder care services, tailored to meet individual needs with dignity and respect.</p>
            <a href="#contact" class="cta-button" onClick="navigateTocsreg()">Find a carer</a>

        </div>
        <div class="hero-right">
            <img src="<?php echo URLROOT; ?>/images/homepageimg.jpeg" alt="Elderly Care">
        </div>
    </section>

    <section class="features" id="features">
        <h2 class="section-title">Why Choose Us</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-heart feature-icon"></i>
                <h3>Compassionate Care</h3>
                <p>Our caregivers are selected for their empathy and dedication to providing the highest quality of care.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-clock feature-icon"></i>
                <h3>24/7 Support</h3>
                <p>Round-the-clock care and support available to ensure your loved ones are always in good hands.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-user-md feature-icon"></i>
                <h3>Professional Staff</h3>
                <p>Trained and certified caregivers with extensive experience in elder care.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-star feature-icon"></i>
                <h3>More value</h3>
                 <p>On average, services facilitated by Elder are 35% cheaper than traditional alternatives.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-list feature-icon"></i>
                <h3>More choice</h3>
                <p>Pick your favourite self-employed carer from personalised matches.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-comment feature-icon"></i>
                <h3>More support</h3>
                <p>Use our platform to plan and manage care from anywhere, and chat to rated support teams.</p>
            </div>
        </div>
    </section>

    

    <section class="contact" id="contact">
        <div class="reg">
            <div class="reg-card">
            <img src="\we4u\public\images\Alzheimer.gif" alt="caregiver">
                <h3>Start Your journey as a caregiver</h3>
                <p>24hr support when you need it
                A carer moves in to provide 24hr support, for as little as 3 days at a time.</p>
                <button class="cta-button" onClick="navigateTocgreg()">Register</button>

            </div>
            <div class="reg-card">
            <img src="\we4u\public\images\Doctors.gif" alt="consultant">
            <h3>Start your journey as a consultant</h3>
                <p>Flexible home visits
                A carer will visit for a few hours on your chosen days, at a pre-agreed time.</p>
                <button class="cta-button" onClick="navigateTocreg()">Register </button>


            </div>
        </div>
        
    </section>

    <section class="contact-us">
    <div class="container">
        <div class="form-section">
            <h1>Contact Us</h1>
            <p class="subtitle">Our experienced and knowledgeable team is dedicated to providing exceptional customer service.</p>
            
            <form>
                <div class="form-group">
                    <input type="text" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <textarea placeholder="Your Message" required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <div class="contact-info">
            <img src="\we4u\public\images\Contact us.gif" alt="Contact illustration" class="illustration">
            
            <div class="contact-details">
                
                <div>📧 we4u@gmail.com</div>
                <div>📞 0330 828 9720</div>
            </div>

            <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    </section>

    <script>
         function navigateTocgreg() {
    window.location.href = '<?php echo URLROOT; ?>/caregivers/register';
    }

    function navigateTocreg() {
    window.location.href = '<?php echo URLROOT; ?>/consultants/register';
    }

    function navigateTocsreg() {
    window.location.href = '<?php echo URLROOT; ?>/users/register';
    }

</script>
</body>


<?php require APPROOT.'/views/includes/footer.php';?>


        


