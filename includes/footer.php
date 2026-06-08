<?php // This closes the <main> opened in header.php ?>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?php echo SITE_URL; ?>index.php" class="footer-logo">
                    <i class="fas fa-book-open"></i>
                    <span>JJ BOOK SHOPPING</span>
                </a>
                <p class="footer-tagline">Discover your next favorite book. Trendy, discounted, and best-selling titles await you.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>index.php">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>pages/shop.php">Shop</a></li>
                    <li><a href="<?php echo SITE_URL; ?>pages/about.php">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>pages/contact.php">Contact</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Categories</h4>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>pages/shop.php?category=1">Programming</a></li>
                    <li><a href="<?php echo SITE_URL; ?>pages/shop.php?category=2">Novels</a></li>
                    <li><a href="<?php echo SITE_URL; ?>pages/shop.php?category=3">Education</a></li>
                    <li><a href="<?php echo SITE_URL; ?>pages/shop.php?category=4">Technology</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4>Contact Us</h4>
                <p><i class="fas fa-envelope"></i> support@jjbookshopping.com</p>
                <p><i class="fas fa-phone"></i> +251 911 234 567</p>
                <p><i class="fas fa-map-marker-alt"></i> Addis Ababa, Ethiopia</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            <p>Made with <i class="fas fa-heart"></i> for book lovers</p>
        </div>
    </div>
</footer>

<script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>
</body>
</html>
