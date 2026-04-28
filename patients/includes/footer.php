<!-- MODERN FOOTER -->
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="footer-thumb">
                    <h4 class="footer-title">MediCare</h4>
                    <p>Providing world-class healthcare since 2010. Your health is our priority, handled with precision
                        and care.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="footer-thumb">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links list-unstyled">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="book-appointment.php">Book Appointment</a></li>
                        <li><a href="dashboard.php">Patient Portal</a></li>
                        <li><a href="#team">Our Doctors</a></li>
                        <li><a href="login.php">Login / Register</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="footer-thumb">
                    <h4 class="footer-title">Support</h4>
                    <div class="contact-card">
                        <div class="c-item"><i class="fa fa-phone"></i> +880 1613-456789</div>
                        <div class="c-item"><i class="fa fa-envelope"></i> help@medicare.com</div>
                        <div class="c-item"><i class="fa fa-map-marker"></i> Banani, Dhaka, Bangladesh</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="footer-thumb">
                    <h4 class="footer-title">Newsletter</h4>
                    <p class="small">Subscribe to get health tips and center updates.</p>
                    <div class="input-group newsletter-input">
                        <input type="text" class="form-control" placeholder="Email Address">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button"><i class="fa fa-paper-plane"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom text-center">
            <hr>
            <p>&copy; <?php echo date('Y'); ?> MediCare Health Center. All rights reserved. <br>
                <small class="text-muted">Designed for Premium Healthcare</small>
            </p>
        </div>
    </div>
</footer>

<style>
    #footer {
        background: #1f2937;
        color: #d1d5db;
        padding: 80px 0 40px 0;
        font-family: 'Poppins', sans-serif;
    }

    .footer-title {
        color: white;
        font-weight: 700;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: #4ade80;
    }

    .footer-thumb p {
        color: #9ca3af;
        font-size: 14px;
        line-height: 1.6;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links li a {
        color: #9ca3af;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .footer-links li a:hover {
        color: #4ade80;
        padding-left: 10px;
    }

    .social-icons {
        margin-top: 20px;
    }

    .social-icons a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 50%;
        margin-right: 10px;
        transition: all 0.3s ease;
    }

    .social-icons a:hover {
        background: #4ade80;
        color: #1f2937;
        transform: translateY(-3px);
    }

    .contact-card .c-item {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .contact-card i {
        color: #4ade80;
        margin-right: 15px;
        font-size: 18px;
    }

    .newsletter-input {
        margin-top: 15px;
        border-radius: 50px;
        overflow: hidden;
        background: white;
        padding: 5px;
    }

    .newsletter-input .form-control {
        border: none;
        box-shadow: none;
        padding-left: 20px;
    }

    .newsletter-input .btn-primary {
        background: #4ade80;
        border: none;
        border-radius: 50px !important;
        padding: 10px 20px;
        height: auto;
    }

    .footer-bottom {
        margin-top: 60px;
    }

    .footer-bottom hr {
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>

<!-- SCRIPTS -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/wow.min.js"></script>
<script src="js/smoothscroll.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/custom.js"></script>
<script>
    $(window).scroll(function () {
        if ($(document).scrollTop() > 50) {
            $('.navbar').addClass('shrink');
        } else {
            $('.navbar').removeClass('shrink');
        }
    });
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['flash_message'])): ?>
    <script>
        $(document).ready(function () {
            Swal.fire({
                icon: '<?php echo $_SESSION['flash_type'] == 'danger' ? 'error' : ($_SESSION['flash_type'] == 'warning' ? 'warning' : 'success'); ?>',
                title: '<?php echo $_SESSION['flash_type'] == 'danger' ? 'Error!' : ($_SESSION['flash_type'] == 'warning' ? 'Warning!' : 'Success!'); ?>',
                text: '<?php echo addslashes($_SESSION['flash_message']); ?>',
                confirmButtonColor: '#4f46e5',
                timer: 4000,
                timerProgressBar: true
            });
        });
    </script>
    <?php
    unset($_SESSION['flash_type']);
    unset($_SESSION['flash_message']);
endif;
?>
</body>

</html>