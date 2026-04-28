<!-- Top Navbar -->
<div id="content">
    <nav class="top-navbar">
        <button type="button" id="sidebarCollapse" class="btn text-primary fs-4 d-lg-none">
            <i class="bi bi-list"></i>
        </button>
        
        <div class="nav-search d-none d-md-block">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control" placeholder="Search patients, appointments...">
        </div>

        <div class="nav-actions">
            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <button class="nav-icon-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width: 300px;">
                    <li><h6 class="dropdown-header fw-bold border-bottom pb-2">Notifications</h6></li>
                    <li><a class="dropdown-item py-2" href="#">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-light text-primary rounded-circle p-2 me-3"><i class="bi bi-calendar-plus"></i></div>
                            <div>
                                <p class="mb-0 fw-semibold text-wrap text-truncate" style="max-width: 200px;">New appointment booked</p>
                                <small class="text-muted">Just now</small>
                            </div>
                        </div>
                    </a></li>
                </ul>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown profile-dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $doctor['profile_image'] ? 'assets/images/'.$doctor['profile_image'] : 'https://ui-avatars.com/api/?name='.urlencode($doctor['name']).'&background=0b5ed7&color=fff'; ?>" alt="Profile">
                    <span class="d-none d-md-inline ms-2"><?php echo htmlspecialchars($doctor['name']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i> My Profile</a></li>
                    <li><a class="dropdown-item" href="schedule.php"><i class="bi bi-calendar-check me-2"></i> Availability</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
