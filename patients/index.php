<?php
$page_title = 'Health Center - Premium Medical Care';
include 'includes/header.php';
include 'includes/navbar.php';

// Fetch Active Doctors (Dynamic)
$stmt = $pdo->query("SELECT d.*, dept.name as dept_name FROM doctors d LEFT JOIN departments dept ON d.department_id = dept.id WHERE d.status = 'active' LIMIT 6");
$active_doctors = $stmt->fetchAll();

// Fetch Departments (Dynamic Services)
$depts = $pdo->query("SELECT * FROM departments LIMIT 4")->fetchAll();
?>

<!-- MODERN HERO SECTION -->
<section id="top" class="modern-hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row hero-content">
            <div class="col-md-8 col-sm-12">
                <h1 class="hero-title wow fadeInUp" data-wow-delay="0.2s">The Future of <br>Personalized Care.</h1>
                <p class="hero-text wow fadeInUp" data-wow-delay="0.4s">Experience a new era of medical excellence. We
                    combine world-class expertise with cutting-edge technology to prioritize your health above all else.
                </p>
                <div class="hero-btns wow fadeInUp" data-wow-delay="0.6s">
                    <a href="book-appointment.php" class="premium-btn btn-primary smoothScroll">Book Your Appointment <i
                            class="fa fa-arrow-right ms-2"></i></a>
                    <a href="#about" class="premium-btn btn-outline smoothScroll">Learn More</a>
                </div>
            </div>
            <div class="col-md-4 hidden-sm hidden-xs">
                <div class="floating-box wow zoomIn" data-wow-delay="0.8s">
                    <i class="fa fa-heartbeat text-danger floating-icon"></i>
                    <h5>24/7 Monitoring</h5>
                    <p class="small">We are always here for your emergencies.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS COUNTER -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 text-center stat-item">
                <h2 class="counter">10</h2><span class="plus">+</span>
                <p>Years Experience</p>
            </div>
            <div class="col-md-3 col-sm-6 text-center stat-item">
                <h2 class="counter">50</h2><span class="plus">+</span>
                <p>Expert Doctors</p>
            </div>
            <div class="col-md-3 col-sm-6 text-center stat-item">
                <h2 class="counter">12</h2><span class="plus">K+</span>
                <p>Happy Patients</p>
            </div>
            <div class="col-md-3 col-sm-6 text-center stat-item">
                <h2 class="counter">100</h2><span class="plus">%</span>
                <p>Care & Quality</p>
            </div>
        </div>
    </div>
</section>

<!-- ELEGANT MINIMALIST ABOUT SECTION V6 (Standard Premium) -->
<section id="about" class="about-v6-minimal">
    <div class="container v6-narrow-container">
        <div class="row align-items-center">
            <!-- Left Side: Clean Content -->
            <div class="col-lg-6 v6-content-col wow fadeInLeft" data-wow-delay="0.2s">
                <div class="v6-content-wrap">
                    <span class="v6-badge">About Health Center</span>
                    <h2 class="v6-title">We are Providing <br><span>Top Quality</span> Care.</h2>
                    <p class="v6-description">
                        Health Center has been a leader in medical excellence for over a decade. 
                        We believe that every patient deserves personalized care and attention 
                        from the world's best specialists.
                    </p>
                    
                    <div class="v6-features-list">
                        <div class="v6-feature-item">
                            <div class="v6-feature-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="v6-feature-text">
                                <h5>Modern Technology</h5>
                                <p>Using latest diagnostic and surgical tools.</p>
                            </div>
                        </div>
                        <div class="v6-feature-item">
                            <div class="v6-feature-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="v6-feature-text">
                                <h5>Expert Doctors</h5>
                                <p>Team of highly qualified and experienced specialists.</p>
                            </div>
                        </div>
                        <div class="v6-feature-item">
                            <div class="v6-feature-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="v6-feature-text">
                                <h5>24/7 Support</h5>
                                <p>Round the clock emergency and patient care.</p>
                            </div>
                        </div>
                    </div>

                    <div class="v6-action">
                        <a href="register.php" class="btn-v6-primary">Get Started Now</a>
                        <div class="v6-experience-short">
                            <strong>10+</strong>
                            <span>Years Experience</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Standard Visual -->
            <div class="col-lg-6 v6-visual-col wow fadeInRight" data-wow-delay="0.4s">
                <div class="v6-image-wrapper">
                    <img src="images/about-bg.jpg" class="v6-main-img" alt="Medical Center">
                    <div class="v6-experience-box">
                        <div class="v6-exp-icon"><i class="fa fa-heart"></i></div>
                        <h4>12K+</h4>
                        <p>Happy Patients</p>
                    </div>
                    <div class="v6-image-decor"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS SECTION -->
<!-- CINEMATIC TESTIMONIALS V2 -->
<section id="testimonials" class="testi-v2-premium">
    <div class="testi-v2-bg">
        <div class="testi-glow glow-1"></div>
        <div class="testi-glow glow-2"></div>
        <div class="testi-particles"></div>
    </div>
    
    <div class="container">
        <div class="text-center mb-5 v2-header-box wow fadeIn">
            <div class="testi-v2-badge">
                <span class="pulse-ring"></span>
                <i class="fa fa-quote-left me-2"></i> Patient Voices
            </div>
            <h2 class="testi-v2-title">Healing Stories from <br><span class="text-gradient-purple">Our Community</span></h2>
        </div>

        <div class="row g-4">
            <!-- Testimonial 1 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                <div class="testi-v2-card">
                    <div class="v2-card-glass"></div>
                    <div class="v2-card-content">
                        <div class="v2-rating">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                        </div>
                        <p class="v2-quote-text">
                            "The best medical experience I've ever had. Dr. Sadiya was incredibly patient and thorough with my heart checkup. The facility is world-class."
                        </p>
                        <div class="v2-author-info">
                            <div class="v2-avatar-wrap">
                                <img src="https://ui-avatars.com/api/?name=Rahat+Uddin&background=6366f1&color=fff&bold=true" alt="Rahat Uddin">
                                <div class="v2-check"><i class="fa fa-check"></i></div>
                            </div>
                            <div class="v2-meta">
                                <h6>Rahat Uddin</h6>
                                <span>Cardiology Patient</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                <div class="testi-v2-card featured-v2">
                    <div class="v2-card-glass"></div>
                    <div class="v2-card-content">
                        <div class="v2-rating">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                        </div>
                        <p class="v2-quote-text">
                            "Booking was so easy! I didn't have to wait in line, and the portal for prescriptions is a lifesaver. Highly recommend for busy professionals."
                        </p>
                        <div class="v2-author-info">
                            <div class="v2-avatar-wrap">
                                <img src="https://ui-avatars.com/api/?name=Mousumi+Khan&background=10b981&color=fff&bold=true" alt="Mousumi Khan">
                                <div class="v2-check"><i class="fa fa-check"></i></div>
                            </div>
                            <div class="v2-meta">
                                <h6>Mousumi Khan</h6>
                                <span>General Patient</span>
                            </div>
                        </div>
                    </div>
                    <div class="v2-featured-tag">Highly Recommended</div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                <div class="testi-v2-card">
                    <div class="v2-card-glass"></div>
                    <div class="v2-card-content">
                        <div class="v2-rating">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i>
                        </div>
                        <p class="v2-quote-text">
                            "The doctors here truly care. My knee surgery recovery was seamless thanks to Dr. Nasrin's expert guidance. Five stars aren't enough!"
                        </p>
                        <div class="v2-author-info">
                            <div class="v2-avatar-wrap">
                                <img src="https://ui-avatars.com/api/?name=Arif+Hossain&background=f59e0b&color=fff&bold=true" alt="Arif Hossain">
                                <div class="v2-check"><i class="fa fa-check"></i></div>
                            </div>
                            <div class="v2-meta">
                                <h6>Arif Hossain</h6>
                                <span>Orthopedics Patient</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* CUSTOM CONTAINER OVERRIDES */
    .container, .container-fluid {
        width: 96% !important;
        max-width: 56% !important;
        min-width: 89% !important;
    }

    /* PREMIUM DESIGN SYSTEM */
    :root {
        --primary: #6366f1;
        --secondary: #a855f7;
        --accent: #4ade80;
        --dark: #0f172a;
        --light: #f8fafc;
        --glass: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    /* === ELEGANT MINIMALIST ABOUT V6 (Standard Premium) === */
    .about-v6-minimal {
        padding: 120px 0;
        background: #ffffff;
        position: relative;
    }

    .v6-narrow-container {
        max-width: 1100px;
    }

    .v6-content-wrap { padding-right: 40px; }

    .v6-badge {
        display: inline-block;
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .v6-title {
        font-size: 3rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 25px;
    }

    .v6-title span { color: #6366f1; }

    .v6-description {
        font-size: 1.1rem;
        color: #64748b;
        line-height: 1.8;
        margin-bottom: 40px;
    }

    .v6-features-list {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-bottom: 45px;
    }

    .v6-feature-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .v6-feature-icon {
        width: 45px;
        height: 45px;
        background: #f1f5f9;
        color: #6366f1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .v6-feature-item:hover .v6-feature-icon {
        background: #6366f1;
        color: white;
    }

    .v6-feature-text h5 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 5px;
    }

    .v6-feature-text p {
        font-size: 0.95rem;
        color: #64748b;
        margin: 0;
    }

    .v6-action {
        display: flex;
        align-items: center;
        gap: 40px;
    }

    .btn-v6-primary {
        background: #6366f1;
        color: white !important;
        padding: 18px 35px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }

    .btn-v6-primary:hover {
        background: #4f46e5;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
    }

    .v6-experience-short {
        display: flex;
        flex-direction: column;
    }

    .v6-experience-short strong {
        font-size: 1.8rem;
        color: #0f172a;
        font-weight: 800;
        line-height: 1;
    }

    .v6-experience-short span {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Visual Side Styling */
    .v6-visual-col { position: relative; }

    .v6-image-wrapper {
        position: relative;
        padding: 20px;
    }

    .v6-main-img {
        width: 100%;
        height: 550px;
        object-fit: cover;
        border-radius: 30px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        position: relative;
        z-index: 2;
    }

    .v6-experience-box {
        position: absolute;
        bottom: 50px;
        left: -30px;
        background: #ffffff;
        padding: 30px;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        z-index: 3;
        text-align: center;
        min-width: 160px;
        animation: v6-float 5s ease-in-out infinite;
    }

    @keyframes v6-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    .v6-exp-icon {
        width: 50px;
        height: 50px;
        background: rgba(236, 72, 153, 0.1);
        color: #ec4899;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 15px;
    }

    .v6-experience-box h4 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .v6-experience-box p {
        font-size: 0.9rem;
        color: #64748b;
        margin: 0;
        font-weight: 600;
    }

    .v6-image-decor {
        position: absolute;
        top: 0;
        right: 0;
        width: 80%;
        height: 80%;
        border: 2px solid #f1f5f9;
        border-radius: 30px;
        z-index: 1;
    }

    @media (max-width: 991px) {
        .about-v6-minimal { padding: 80px 0; }
        .v6-content-wrap { padding-right: 0; margin-bottom: 50px; }
        .v6-main-img { height: 400px; }
        .v6-experience-box { left: 20px; bottom: 20px; }
        .v6-action { flex-direction: column; align-items: flex-start; gap: 20px; }
    }

    .text-gradient {
        background: linear-gradient(45deg, #4ade80, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .badge-premium {
        background: rgba(74, 222, 128, 0.2);
        color: var(--accent);
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
        display: inline-block;
        margin-bottom: 20px;
    }

    .floating-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        padding: 30px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        margin-top: 50px;
        width: 100%;
    }

    .floating-box p {
        color: #ffffff !important;
        opacity: 0.9;
    }

    .floating-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        animation: float 3s ease-in-out infinite;
        display: block;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .stats-section {
        background: white;
        padding: 50px 0;
        margin-top: -50px;
        position: relative;
        z-index: 5;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);
        border-radius: 20px;
    }

    .stat-item h2 {
        display: inline;
        font-size: 3rem;
        font-weight: 800;
        color: var(--dark);
    }

    .stat-item .plus {
        font-size: 1.5rem;
        color: var(--primary);
        font-weight: 700;
    }

    /* === CINEMATIC TESTIMONIALS V2 (WHITE THEME) === */
    .testi-v2-premium {
        background: #f8fafc;
        padding: 120px 0;
        position: relative;
        overflow: hidden;
    }

    .testi-v2-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    .testi-glow {
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        filter: blur(150px);
        opacity: 0.08;
    }

    .glow-1 {
        background: var(--primary);
        top: -100px;
        left: -100px;
        animation: v2-pulse-glow 10s infinite alternate;
    }

    .glow-2 {
        background: var(--secondary);
        bottom: -100px;
        right: -100px;
        animation: v2-pulse-glow 12s infinite alternate-reverse;
    }

    .v2-header-box { position: relative; z-index: 2; }

    .testi-v2-badge {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        color: var(--primary);
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 25px;
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 1px solid var(--primary);
        border-radius: 50px;
        left: 0;
        top: 0;
        animation: v2-pulse-ring 2s infinite;
    }

    .testi-v2-title {
        font-size: 3.2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 20px;
    }

    .text-gradient-purple {
        background: linear-gradient(135deg, var(--secondary), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .testi-v2-card {
        position: relative;
        height: 100%;
        border-radius: 30px;
        padding: 40px;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        z-index: 2;
    }

    .v2-card-glass {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 30px;
        z-index: -1;
        transition: all 0.5s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }

    .testi-v2-card:hover { transform: translateY(-15px); }
    .testi-v2-card:hover .v2-card-glass {
        border-color: var(--primary);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
    }

    .featured-v2 .v2-card-glass {
        background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(243, 244, 246, 1));
    }

    .v2-rating { color: #fbbf24; font-size: 0.9rem; margin-bottom: 25px; display: flex; gap: 4px; }

    .v2-quote-text {
        color: #475569;
        font-size: 1.1rem;
        line-height: 1.8;
        font-style: italic;
        margin-bottom: 30px;
    }

    .v2-author-info {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-top: 25px;
        border-top: 1px solid #f1f5f9;
    }

    .v2-avatar-wrap img { width: 55px; height: 55px; border-radius: 50%; border: 2px solid #f1f5f9; }
    
    .v2-check {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 18px;
        height: 18px;
        background: #22c55e;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        border: 2px solid #ffffff;
    }

    .v2-meta h6 { color: #0f172a; font-weight: 700; font-size: 1rem; margin: 0; }
    .v2-meta span { color: var(--primary); font-size: 0.85rem; font-weight: 600; }

    .v2-featured-tag {
        position: absolute;
        top: 20px;
        right: -35px;
        background: var(--primary);
        color: white;
        padding: 5px 40px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        transform: rotate(45deg);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    @media (max-width: 991px) {
        .testi-v2-title { font-size: 2.5rem; }
        .testi-v2-card { padding: 30px; }
    }

    @media (max-width: 768px) {
        .testi-v2-title { font-size: 2.2rem; }
    }
</style>

<!-- HYPER-SERVICE GRID V4 -->
<section id="services" class="svc-v4-hyper">
    <div class="v4-aura aura-1"></div>
    <div class="v4-aura aura-2"></div>
    
    <div class="container">
        <div class="text-center mb-5 v4-header wow fadeIn">
            <div class="v4-badge">
                <span class="v4-badge-dot"></span>
                <i class="fa fa-heartbeat me-2"></i> Our Specialties
            </div>
            <h2 class="v4-title">Medical <span class="v4-title-alt">Excellence</span></h2>
        </div>

        <div class="row g-5">
            <?php
            $v4_schemes = [
                ['color' => '#6366f1', 'bg' => 'linear-gradient(135deg, #6366f1, #818cf8)', 'shadow' => 'rgba(99, 102, 241, 0.2)'],
                ['color' => '#10b981', 'bg' => 'linear-gradient(135deg, #10b981, #34d399)', 'shadow' => 'rgba(16, 185, 129, 0.2)'],
                ['color' => '#f59e0b', 'bg' => 'linear-gradient(135deg, #f59e0b, #fbbf24)', 'shadow' => 'rgba(245, 158, 11, 0.2)'],
                ['color' => '#ec4899', 'bg' => 'linear-gradient(135deg, #ec4899, #f472b6)', 'shadow' => 'rgba(236, 72, 153, 0.2)'],
            ];
            $svc_icons = [
                'Cardiology' => 'fa-heartbeat',
                'Neurology' => 'fa-sitemap',
                'Orthopedics' => 'fa-wheelchair',
                'Pediatrics' => 'fa-child',
                'Dermatology' => 'fa-leaf',
                'ENT' => 'fa-assistive-listening-systems',
            ];
            foreach ($depts as $i => $dept):
                $sc = $v4_schemes[$i % count($v4_schemes)];
                $icon = $svc_icons[$dept['name']] ?? 'fa-medkit';
                ?>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="<?php echo 0.15 * ($i + 1); ?>s">
                    <div class="v4-card" style="--accent: <?php echo $sc['color']; ?>; --accent-bg: <?php echo $sc['bg']; ?>; --accent-shadow: <?php echo $sc['shadow']; ?>;">
                        <div class="v4-card-inner">
                            <div class="v4-icon-wrap">
                                <div class="v4-icon-shape">
                                    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M44.7,-76.4C58.1,-69.2,69.2,-58.1,76.4,-44.7C83.7,-31.4,87,-15.7,87,0C87,15.7,83.7,31.4,76.4,44.7C69.2,58.1,58.1,69.2,44.7,76.4C31.4,83.7,15.7,87,0,87C-15.7,87,-31.4,83.7,-44.7,76.4C-58.1,69.2,-69.2,58.1,-76.4,44.7C-83.7,31.4,-87,15.7,-87,0C-87,-15.7,-83.7,-31.4,-76.4,-44.7C-69.2,-58.1,-58.1,-69.2,-44.7,-76.4C-31.4,-83.7,-15.7,-87,0,-87C15.7,-87,31.4,-83.7,44.7,-76.4Z" transform="translate(100 100)" />
                                    </svg>
                                </div>
                                <i class="fa <?php echo $icon; ?>"></i>
                            </div>
                            
                            <h4 class="v4-card-title"><?php echo htmlspecialchars($dept['name']); ?></h4>
                            <p class="v4-card-text">
                                <?php echo htmlspecialchars($dept['description'] ?: 'Specialized medical solutions and personalized care in ' . $dept['name']); ?>
                            </p>
                            
                            <div class="v4-card-action">
                                <a href="book-appointment.php" class="v4-btn-link">
                                    Learn More <span class="v4-btn-arrow"><i class="fa fa-long-arrow-right"></i></span>
                                </a>
                            </div>
                        </div>
                        <div class="v4-card-number"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                        <div class="v4-card-glow"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    /* === HYPER-SERVICE GRID V4 (WHITE THEME) === */
    .svc-v4-hyper {
        background: #ffffff;
        padding: 140px 0;
        position: relative;
        overflow: hidden;
    }

    .v4-aura {
        position: absolute;
        width: 700px;
        height: 700px;
        border-radius: 50%;
        filter: blur(150px);
        opacity: 0.05;
        pointer-events: none;
    }

    .aura-1 { background: var(--primary); top: -200px; right: -100px; }
    .aura-2 { background: var(--accent); bottom: -200px; left: -100px; }

    .v4-header { position: relative; z-index: 5; }

    .v4-badge {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        color: #64748b;
        padding: 10px 25px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 25px;
        border: 1px solid #e2e8f0;
    }

    .v4-badge-dot {
        width: 8px;
        height: 8px;
        background: var(--accent);
        border-radius: 50%;
        margin-right: 12px;
        box-shadow: 0 0 15px var(--accent);
        animation: v4-pulse-dot 2s infinite;
    }

    .v4-title {
        font-size: 4rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -1px;
    }

    .v4-title-alt {
        background: linear-gradient(135deg, var(--accent), #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .v4-card {
        position: relative;
        height: 100%;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 1;
    }

    .v4-card-inner {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 40px;
        padding: 50px 40px;
        height: 100%;
        position: relative;
        overflow: hidden;
        z-index: 2;
        transition: all 0.6s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }

    .v4-card:hover .v4-card-inner {
        transform: translateY(-15px);
        border-color: var(--accent);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
    }

    .v4-icon-wrap {
        position: relative;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 35px;
    }

    .v4-icon-shape {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        fill: var(--accent);
        opacity: 0.1;
        transition: all 0.6s ease;
        animation: v4-morph 15s linear infinite;
    }

    .v4-icon-wrap i {
        font-size: 2.2rem;
        color: var(--accent);
        z-index: 3;
        transition: all 0.6s ease;
    }

    .v4-card:hover .v4-icon-shape {
        opacity: 0.9;
        fill: var(--accent);
        transform: scale(1.1);
    }

    .v4-card:hover .v4-icon-wrap i {
        color: white;
        transform: scale(1.1) rotate(-10deg);
    }

    .v4-card-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .v4-card-text {
        font-size: 1rem;
        color: #64748b;
        line-height: 1.8;
        margin-bottom: 40px;
    }

    .v4-btn-link {
        color: #0f172a;
        font-weight: 700;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .v4-btn-arrow {
        width: 35px;
        height: 35px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .v4-card:hover .v4-btn-link { color: var(--accent); }
    .v4-card:hover .v4-btn-arrow { background: var(--accent); color: white; transform: translateX(5px); }

    .v4-card-number {
        position: absolute;
        bottom: 40px;
        right: 40px;
        font-size: 4rem;
        font-weight: 900;
        color: #f8fafc;
        line-height: 1;
        transition: all 0.6s ease;
        z-index: -1;
    }

    .v4-card:hover .v4-card-number {
        color: #f1f5f9;
    }

    .v4-card-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% 100%, var(--accent-shadow), transparent 70%);
        opacity: 0;
        transition: all 0.6s ease;
        z-index: 1;
        pointer-events: none;
    }

    .v4-card:hover .v4-card-glow { opacity: 0.3; }

    @media (max-width: 991px) {
        .v4-title { font-size: 3rem; }
        .v4-card-inner { padding: 40px 30px; }
    }

    @media (max-width: 768px) {
        .v4-title { font-size: 2.5rem; }
    }

    @media (max-width: 991px) {
        .v4-title { font-size: 3rem; }
        .v4-card-inner { padding: 40px 30px; }
    }

    @media (max-width: 768px) {
        .v4-title { font-size: 2.5rem; }
    }
</style>

<!-- MODERN TEAM SECTION -->
<section id="team" class="section-padding bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="fw-bold">Expert Doctors</h2>
            <p class="text-muted">Our team of experienced specialists is here to help you.</p>
        </div>
        <div class="row">
            <?php foreach ($active_doctors as $dr): ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="doctor-card-modern wow fadeInUp">
                            <div class="doctor-header-bg"></div>
                            <div class="doctor-avatar">
                                <?php
                                $img_src = 'https://ui-avatars.com/api/?name=' . urlencode($dr['name']) . '&background=667eea&color=fff&size=200';
                                if ($dr['profile_image'] && $dr['profile_image'] !== 'default_doctor.png') {
                                    $img_src = '../doctor/assets/images/' . $dr['profile_image'];
                                }
                                ?>
                                <img src="<?php echo $img_src; ?>" class="img-responsive">
                            </div>
                            <div class="doctor-info text-center">
                                <span
                                    class="badge-dept"><?php echo htmlspecialchars($dr['dept_name'] ?: 'Specialist'); ?></span>
                                <h3 class="dr-name">Dr. <?php echo htmlspecialchars($dr['name']); ?></h3>

                                <div class="dr-contact">
                                    <div class="contact-pill"><i class="fa fa-phone"></i>
                                        <?php echo htmlspecialchars($dr['phone']); ?></div>
                                </div>

                                <a href="book-appointment.php?doctor_id=<?php echo $dr['id']; ?>"
                                    class="btn-book-modern mt-4">Book Appointment <i class="fa fa-arrow-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CALL TO ACTION -->
<section id="cta" class="cta-section text-center">
    <div class="container">
        <div class="cta-box wow pulse">
            <h2>Ready to prioritize your health?</h2>
            <p style="color: white !important;">Join thousands of happy patients today. Quick booking, expert advice.
            </p>
            <a href="register.php" class="premium-btn btn-white">Get Started Now</a>
        </div>
    </div>
</section>

<style>
    /* MODERN OVERRIDES */
    .modern-hero {
        background: url('images/slider1.jpg') no-repeat center center;
        background-size: cover;
        min-height: 85vh;
        padding: 15% 0;
        position: relative;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(31, 41, 55, 0.8) 0%, rgba(102, 126, 234, 0.4) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
        width: 100%;
    }

    .hero-title {
        font-size: 5rem;
        font-weight: 800;
        margin-bottom: 25px;
        line-height: 1.1;
        display: block;
        background: linear-gradient(45deg, #4ade80, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        text-transform: uppercase;
        letter-spacing: 4px;
        font-size: 1.2rem;
        color: #4ade80;
        margin-bottom: 15px;
        display: block;
    }

    .hero-text {
        font-size: 1.4rem;
        color: #ffffff;
        opacity: 1;
        margin-bottom: 45px;
        max-width: 700px;
        line-height: 1.6;
    }

    .text-highlight {
        color: #4ade80;
    }

    .premium-btn {
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        display: inline-block;
        border: 2px solid transparent;
    }

    .btn-primary {
        background: #4ade80;
        color: #1f2937;
        margin-right: 15px;
    }

    .btn-primary:hover {
        background: #22c55e;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
    }

    .btn-outline {
        border-color: white;
        color: white;
    }

    .btn-outline:hover {
        background: white;
        color: #1f2937;
    }

    .btn-white {
        background: white;
        color: #667eea;
        margin-top: 20px;
    }

    .section-padding {
        padding: 100px 0;
    }

    .doctor-card-modern {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border: 1px solid rgba(0, 0, 0, 0.02);
        height: 100%;
    }

    .doctor-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.15);
    }

    .doctor-header-bg {
        height: 100px;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        position: relative;
    }

    .doctor-avatar {
        margin-top: -50px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .doctor-avatar img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        object-fit: cover;
        background: white;
        display: inline-block;
    }

    .doctor-info {
        padding: 20px 30px 30px;
    }

    .badge-dept {
        display: inline-block;
        background: #eff6ff;
        color: #4f46e5;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    .dr-name {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1f2937;
        margin: 0 0 10px 0;
    }

    .dr-stats {
        font-size: 0.95rem;
        color: #4b5563;
        margin-bottom: 15px;
    }

    .contact-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f3f4f6;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        color: #4b5563;
        font-weight: 600;
    }

    .contact-pill i {
        color: #667eea;
    }

    .btn-book-modern {
        display: block;
        width: 100%;
        text-align: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-book-modern:hover {
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        transform: translateY(-2px);
    }

    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 120px 0;
        color: white;
    }

    .cta-box h2 {
        font-size: 3rem;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .modern-hero {
            height: auto;
            padding: 120px 0 80px 0;
        }

        .hero-title {
            font-size: 3.5rem;
        }

        .hero-text {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 2.8rem;
        }

        .hero-text {
            font-size: 1.1rem;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>