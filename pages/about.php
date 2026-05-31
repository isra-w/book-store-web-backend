<?php require_once __DIR__ . '/../includes/header.php'; ?>

<section class="about-page">
    <div class="container">

        <div class="about-hero">
            <h1>About JJ Book Shopping</h1>
            <p>We are passionate about connecting readers with their perfect books. Our digital bookstore offers a curated collection of trendy, discounted, and bestselling titles across various genres.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-bottom: 80px;">
            <div>
                <h2 style="font-family: var(--font-heading); font-size: 2rem; margin-bottom: 20px; color: var(--color-beige);">Our Story</h2>
                <p style="color: var(--color-text-muted); line-height: 1.8; margin-bottom: 15px;">JJ Book Shopping started as a simple idea: make great books accessible to everyone. What began as a small collection has grown into a beloved online destination for book lovers.</p>
                <p style="color: var(--color-text-muted); line-height: 1.8; margin-bottom: 15px;">We carefully select each title in our collection, ensuring that every book we offer is worth your time. From gripping novels to educational resources, we have something for every reader.</p>
                <p style="color: var(--color-text-muted); line-height: 1.8;">Our mission is simple: inspire reading, one book at a time.</p>
            </div>
            <div style="background: linear-gradient(135deg, rgba(201, 169, 98, 0.2), rgba(212, 168, 67, 0.1)); border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 40px; text-align: center;">
                <i class="fas fa-book-open" style="font-size: 5rem; color: var(--color-gold); margin-bottom: 20px;"></i>
                <h3 style="font-family: var(--font-heading); font-size: 1.5rem; margin-bottom: 10px;">JJ Book Shopping</h3>
                <p style="color: var(--color-text-muted);">Est. 2026</p>
            </div>
        </div>

        <h2 class="section-title">Why Choose Us</h2>
        <p class="section-subtitle">What makes JJ Book Shopping special</p>

        <div class="features-grid">
            <div class="feature-box"><i class="fas fa-shipping-fast"></i><h3>Fast Delivery</h3><p>Quick and reliable delivery to your doorstep.</p></div>
            <div class="feature-box"><i class="fas fa-tags"></i><h3>Great Prices</h3><p>Competitive prices and regular discounts.</p></div>
            <div class="feature-box"><i class="fas fa-shield-alt"></i><h3>Secure Shopping</h3><p>Your data and payments are protected.</p></div>
            <div class="feature-box"><i class="fas fa-headset"></i><h3>Customer Support</h3><p>Friendly support team ready to help.</p></div>
            <div class="feature-box"><i class="fas fa-star"></i><h3>Curated Collection</h3><p>Handpicked titles from various genres.</p></div>
            <div class="feature-box"><i class="fas fa-undo"></i><h3>Easy Returns</h3><p>Simple return process if you're not satisfied.</p></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-top: 80px; text-align: center;">
            <div style="background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 40px;">
                <i class="fas fa-book" style="font-size: 2.5rem; color: var(--color-gold); margin-bottom: 15px;"></i>
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-beige); font-family: var(--font-heading);">1000+</div>
                <p style="color: var(--color-text-muted); margin-top: 5px;">Books Available</p>
            </div>
            <div style="background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 40px;">
                <i class="fas fa-users" style="font-size: 2.5rem; color: var(--color-gold); margin-bottom: 15px;"></i>
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-beige); font-family: var(--font-heading);">500+</div>
                <p style="color: var(--color-text-muted); margin-top: 5px;">Happy Readers</p>
            </div>
            <div style="background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 40px;">
                <i class="fas fa-truck" style="font-size: 2.5rem; color: var(--color-gold); margin-bottom: 15px;"></i>
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-beige); font-family: var(--font-heading);">2000+</div>
                <p style="color: var(--color-text-muted); margin-top: 5px;">Orders Delivered</p>
            </div>
            <div style="background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 40px;">
                <i class="fas fa-heart" style="font-size: 2.5rem; color: var(--color-gold); margin-bottom: 15px;"></i>
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-beige); font-family: var(--font-heading);">99%</div>
                <p style="color: var(--color-text-muted); margin-top: 5px;">Satisfaction Rate</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
