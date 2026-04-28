<!-- PREMIUM NAVBAR -->
<nav class="premium-nav" id="mainNav">
    <div class="container">
        <div class="nav-inner">
            <!-- Brand -->
            <a href="index.php" class="nav-brand">
                <img src="../images/logo.png" alt="Logo" class="nav-logo-img">
                <div class="nav-brand-text">
                    <span class="brand-name">Medi <span class="brand-accent">Cares</span></span>
                    <span class="brand-tagline">Premium Healthcare</span>
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button class="nav-toggle" id="navToggle" onclick="document.getElementById('navMenu').classList.toggle('nav-menu-open')">
                <span></span><span></span><span></span>
            </button>

            <!-- Nav Links -->
            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="index.php#top" class="smoothScroll"><i class="fa fa-home"></i> Home</a></li>
                    <li><a href="index.php#about" class="smoothScroll"><i class="fa fa-info-circle"></i> About</a></li>
                    <li><a href="index.php#services" class="smoothScroll"><i class="fa fa-stethoscope"></i> Services</a></li>
                    <li><a href="index.php#team" class="smoothScroll"><i class="fa fa-user-md"></i> Doctors</a></li>
                    
                    <?php if ($is_logged_in): ?>
                        <li class="nav-dropdown">
                            <a href="#" class="nav-dropdown-toggle">
                                <div class="nav-avatar"><?php echo strtoupper(substr($patient['name'] ?? 'P', 0, 1)); ?></div>
                                <?php echo htmlspecialchars($patient['name'] ?? 'Patient'); ?> <i class="fa fa-caret-down ms-1"></i>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                                <li><a href="book-appointment.php"><i class="fa fa-calendar-plus-o"></i> Book Appointment</a></li>
                                <li><a href="my-appointments.php"><i class="fa fa-list-alt"></i> My Appointments</a></li>
                                <li><a href="prescriptions.php"><i class="fa fa-file-text-o"></i> Prescriptions</a></li>
                                <li><a href="payments.php"><i class="fa fa-credit-card"></i> Payments</a></li>
                                <li><a href="profile.php"><i class="fa fa-user-o"></i> Profile Settings</a></li>
                                <li class="nav-dropdown-divider"></li>
                                <li><a href="logout.php" class="nav-logout"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-dropdown">
                            <a href="#" class="nav-dropdown-toggle">
                                <i class="fa fa-sign-in"></i> Account <i class="fa fa-caret-down ms-1"></i>
                            </a>
                            <ul class="nav-dropdown-menu">
                                <li><a href="login.php"><i class="fa fa-lock"></i> Patient Login</a></li>
                                <li><a href="register.php"><i class="fa fa-user-plus"></i> Patient Register</a></li>
                                <li class="nav-dropdown-divider"></li>
                                <li><a href="../doctor/login.php"><i class="fa fa-user-md"></i> Doctor Login</a></li>
                                <li><a href="../admin/login.php"><i class="fa fa-shield"></i> Admin Portal</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                <a href="book-appointment.php" class="nav-cta">
                    <i class="fa fa-calendar-plus-o"></i> Book Appointment
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
/* ===== PREMIUM NAVBAR ===== */
.premium-nav {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    padding: 16px 0;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.08);
    transition: all 0.4s ease;
}
.premium-nav.scrolled {
    padding: 6px 0;
    background: rgba(15, 23, 42, 0.95);
    box-shadow: 0 4px 30px rgba(0,0,0,0.3);
}

.nav-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Brand */
.nav-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none !important;
    transition: transform 0.3s ease;
}
.nav-brand:hover { transform: scale(1.02); }

.nav-logo-img {
    height: 64px;
    width: auto;
    object-fit: contain;
    filter: brightness(1.1);
}
.nav-brand-text {
    display: flex;
    flex-direction: column;
}
.brand-name {
    font-size: 2.2rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: 0.5px;
    line-height: 1.2;
}
.brand-accent {
    background: linear-gradient(135deg, #4ade80, #22c55e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.brand-tagline {
    font-size: 0.65rem;
    color: #94a3b8;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 500;
}

/* Nav Menu */
.nav-menu {
    display: flex;
    align-items: center;
    gap: 10px;
}
.nav-links {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 2px;
    margin: 0;
    padding: 0;
}
.nav-links > li > a {
    color: #cbd5e1 !important;
    font-size: 1.5rem;
    font-weight: 500;
    padding: 10px 16px;
    border-radius: 10px;
    transition: all 0.3s ease;
    text-decoration: none !important;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.nav-links > li > a:hover {
    color: #ffffff !important;
    background: rgba(255,255,255,0.08);
}
.nav-links > li > a i {
    font-size: 1.5rem;
    opacity: 0.7;
    transition: all 0.3s ease;
}
.nav-links > li > a:hover i {
    opacity: 1;
    color: #4ade80;
}

/* Avatar */
.nav-avatar {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    margin-right: 6px;
}

/* Dropdown */
.nav-dropdown {
    position: relative;
}
.nav-dropdown-toggle {
    cursor: pointer;
}
.nav-dropdown-menu {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    background: #ffffff;
    border-radius: 16px;
    min-width: 240px;
    padding: 10px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2), 0 0 0 1px rgba(0,0,0,0.05);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px);
    transition: all 0.3s ease;
    list-style: none;
    z-index: 10000;
}
.nav-dropdown:hover .nav-dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.nav-dropdown-menu li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    color: #334155 !important;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 10px;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.nav-dropdown-menu li a:hover {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #ffffff !important;
    transform: translateX(4px);
}
.nav-dropdown-menu li a i {
    font-size: 1.5rem;
    width: 24px;
    text-align: center;
    color: #6366f1;
    transition: all 0.2s ease;
}
.nav-dropdown-menu li a:hover i { color: #ffffff; }

.nav-dropdown-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 6px 10px;
}
.nav-logout { color: #ef4444 !important; }
.nav-logout i { color: #ef4444 !important; }

/* CTA Button */
.nav-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #4ade80, #22c55e);
    color: #052e16 !important;
    padding: 10px 22px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.3rem;
    text-decoration: none !important;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
    white-space: nowrap;
}
.nav-cta i {
    font-size: 1.5rem;
}
.nav-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);
    color: #052e16 !important;
}

/* Mobile Toggle */
.nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px;
    padding: 10px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.nav-toggle:hover { background: rgba(255,255,255,0.15); }
.nav-toggle span {
    display: block;
    width: 22px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s ease;
}

/* Responsive */
@media (max-width: 992px) {
    .nav-toggle { display: flex; }
    .nav-menu {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 16px;
        right: 16px;
        background: rgba(15, 23, 42, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 20px;
        flex-direction: column;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .nav-menu-open { display: flex !important; }
    .nav-links { flex-direction: column; width: 100%; gap: 4px; }
    .nav-links > li { width: 100%; }
    .nav-links > li > a { width: 100%; }
    .nav-cta { width: 100%; justify-content: center; margin-top: 10px; }
    .nav-dropdown-menu {
        position: static;
        box-shadow: none;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        margin-top: 6px;
        transform: none;
        opacity: 1;
        visibility: visible;
        display: none;
    }
    .nav-dropdown.active .nav-dropdown-menu { display: block; }
    .nav-dropdown-menu li a { color: #cbd5e1 !important; }
    .nav-dropdown-menu li a:hover { color: #ffffff !important; }
    .nav-dropdown-menu li a i { color: #818cf8; }
    .brand-tagline { display: none; }
}
</style>

<script>
// Scroll effect
window.addEventListener('scroll', function() {
    const nav = document.getElementById('mainNav');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

// Mobile dropdown toggle
document.querySelectorAll('.nav-dropdown-toggle').forEach(function(el) {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        this.closest('.nav-dropdown').classList.toggle('active');
    });
});
</script>
