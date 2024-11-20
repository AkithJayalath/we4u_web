<?php require APPROOT.'/views/includes/header.php';?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>


<link rel ="stylesheet" href="<?php echo URLROOT; ?>/css/aboutus.css"> 

<div class="sections">
    <section id="story" class="story">
        <div class="content">
        <h2>Our Story</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo fuga distinctio cum maiores rem ut accusantium cupiditate id modi illo obcaecati ducimus, non nisi natus necessitatibus vel repellendus iusto voluptatibus.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo fuga distinctio cum maiores rem ut accusantium cupiditate id modi illo obcaecati ducimus, non nisi natus necessitatibus vel repellendus iusto voluptatibus.</p>

        </div>
        <div class="strimg">
            <img src="/we4u/public/images/hands.jpeg" alt="OurStory">
        </div>
    </section>

    <section id="ben" class="benifits">
        <div class="benimg">
            <img src="/we4u/public/images/whyus.png" alt="WhyWE4U">
        </div>
        <div class="bcontent">
        <h2>Why WE4U?</h2>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Accusantium ipsa beatae blanditiis nihil nulla doloremque, unde tenetur numquam officia incidunt debitis quam deleniti atque veniam, consequuntur quis iusto ipsam neque!</p>

        <button class="reg-btn" onclick="window.location.href='<?php echo URLROOT?>/users/register'">Register Now</button>

        </div>


    </div>
        
    </section>
    
    <section id="team" class="team">
        <div class="tcontent">
        <h2>Meet Our Team</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo similique velit atque accusantium. Omnis porro at, laborum aut velit, iure placeat impedit odio, deleniti laboriosam iste nesciunt dignissimos alias officiis.</p>
        </div>
    </section>
    <section id="car" class="career">
        <div class="c-section">
        <div class="ccontent">
            <h2>Join Our Team</h2>
            <p>Make a Difference in Someone’s Life</p>
            <br>
<p>Are you passionate about providing care, support, and companionship to those in need? At WE4U, we’re committed to creating a community where the elderly feel valued and cared for. Join us in our mission to improve lives and make a lasting impact.</p>

        </div>
        <div class="role">
            <div class="cg">
                <h2>Start Your Journey as a Caregiver</h2>
                <p>Provide compassionate care to seniors, assist with daily tasks, and become a trusted companion. We offer training for those new to caregiving, so all you need is a kind heart and a desire to help.</p>
                <button class="reg-cg" onclick="window.location.href=''">Apply as a Caregiver</button>

            </div>
            <div class="con">
                <h2>Start Your Journey as a Consultant</h2>
                <p>Use your expertise to support seniors with advice, medical guidance, or therapy. Whether you're a healthcare professional, therapist, or counselor, your skills can make a significant difference.</p>
                <button class="reg-con" onclick="window.location.href=''">Apply as a Consultant</button>

            </div>
        </div>
        </div>
    </section>
</div>

<?php require APPROOT.'/views/includes/footer.php';?>








