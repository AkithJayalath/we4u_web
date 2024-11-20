<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/consultantv.css">

<div class="sections">
    <!-- Story Section -->
    <section class="story">
        <div class="content">
            <h2>Our Story</h2>
            <p>
                Founded with a vision to provide world-class caregiving services, our team has been guiding families and individuals towards well-being and independence. We combine compassion, expertise, and a commitment to excellence in everything we do.
            </p>
        </div>
        <div class="strimg">
            <img src="/we4u/public/images/hands.jpeg" alt="Our Story">
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benifits">
        <div class="benimg">
            <img src="/we4u/public/images/hands.jpeg" alt="Why Choose Us">
        </div>
        <div class="bcontent">
            <h2>Why Choose Us</h2>
            <p>
                Our caregivers are experienced professionals dedicated to improving the quality of life for our clients. We provide personalized care, ensuring every individual receives the attention and support they need to thrive.
            </p>
            <button class="reg-btn">Learn More</button>
        </div>
    </section>

    <!-- Meet Our Team Section -->
    <section class="team">
        <h2>Meet Our Team</h2>
        <div class="team-carousel">
            <div class="team-member">
                <img src="/we4u/public/images/hands.jpeg" alt="Team Member 1">
                <h3>John Doe</h3>
            </div>
            <div class="team-member">
                <img src="/we4u/public/images/hands.jpeg" alt="Team Member 2">
                <h3>Jane Smith</h3>
            </div>
            <div class="team-member">
                <img src="/we4u/public/images/hands.jpeg" alt="Team Member 3">
                <h3>Emily Johnson</h3>
            </div>
            <div class="team-member">
                <img src="/we4u/public/images/hands.jpeg" alt="Team Member 4">
                <h3>Michael Brown</h3>
            </div>
        </div>
        <div class="team-navigation">
            <span class="active"></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </section>

    <!-- Career Section -->
    <section class="career">
        <div class="ccontent">
            <h2>Join Our Team</h2>
            <p>
                We’re always looking for compassionate and dedicated caregivers to join our growing team. Explore the available roles below and take the first step toward a rewarding career with us.
            </p>
        </div>
        <div class="role">
            <div class="cg">
                <h2>Caregiver Role</h2>
                <p>
                    As a caregiver, you'll work directly with clients to provide personalized care and support. Your compassion and dedication will help individuals maintain their independence and well-being.
                </p>
                <button class="reg-cg">Apply Now</button>
            </div>
            <div class="con">
                <h2>Support Specialist</h2>
                <p>
                    Our support specialists play a vital role in ensuring smooth operations. Join our team to provide essential assistance to caregivers and clients alike.
                </p>
                <button class="reg-con">Apply Now</button>
            </div>
        </div>
    </section>
</div>

<?php require APPROOT.'/views/includes/footer.php'; ?>
