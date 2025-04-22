<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>


<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/aboutus.css"> 

<body>
<section class="hero">
        <h1 class="fade-in">Caring for Your Loved Ones</h1>
        <p class="fade-in">We’ve built a platform that makes it easier for families and self-employed carers to find each other and provide compassionate and professional care services that enhance the quality of life for seniors.</p>
        <div class="btn-container">
            <button class="btn btn-primary" onClick="navigateTocsreg()">Get Started</button>

        </div>
        <div class="wave"></div>
    </section> 

    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3>Compassionate Care</h3>
            <p>Our dedicated team provides personalized care with empathy and understanding, ensuring your loved ones feel valued and respected.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3>24/7 Support</h3>
            <p>Round-the-clock care and support ensures your loved ones receive attention whenever they need it, giving you peace of mind.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h3>Professional Excellence</h3>
            <p>Our certified caregivers undergo rigorous training and maintain the highest standards of professional care services.</p>
        </div>
    </section>

    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Families Served</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label">Client Satisfaction</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">20+</div>
                <div class="stat-label">Certified Consultants</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Certified Caregivers</div>
            </div>
        </div>
    </section>

    <section class="team">
        <h2 class="fade-in">Find Your Career From WE4U</h2>
        <div class="team-grid">
            <div class="team-member">
                <img src="\we4u\public\images\Alzheimer.gif" alt="caregiver">
                <h3>Start Your journey as a caregiver</h3>
                <button class="btn btn-primary" onClick="navigateTocgreg()">Become A Caregiver</button>
            </div>
            <div class="team-member">
                <img src="\we4u\public\images\Doctors.gif" alt="consultant">
                <h3>Start your journey as a consultant</h3>
                <button class="btn btn-primary" onClick="navigateTocreg()">Become A Consultant</button>
           
        </div>
    </section>

    <script>
        function navigateTocgreg() {
    window.location.href = '<?php echo URLROOT; ?>/caregivers/register';
    }

    function navigateTocreg() {
    window.location.href = '<?php echo URLROOT; ?>/consultant/register';
    }

    function navigateTocsreg() {
    window.location.href = '<?php echo URLROOT; ?>/users/register';
    }

        // Intersection Observer for fade-in animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        // Observe all elements that should animate
        document.querySelectorAll('.fade-in, .feature-card, .stat-item, .team-member').forEach((element) => {
            observer.observe(element);
        });

        // Trigger initial animations for elements above the fold
        document.addEventListener('DOMContentLoaded', () => {
            const heroElements = document.querySelectorAll('.hero .fade-in');
            heroElements.forEach(element => {
                element.classList.add('visible');
            });
        });
    </script>
</body>

<?php require APPROOT.'/views/includes/footer.php';?>








