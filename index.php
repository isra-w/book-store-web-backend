<?php
// Homepage: loads shared header and renders the landing page content.
// Shows store hero, category cards, and featured book collections.
require_once __DIR__ . '/includes/header.php';

$cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");
$trendy_result = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.is_trendy = 1 LIMIT 4");
$discount_result = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.is_discount = 1 LIMIT 4");
$bestseller_result = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.is_bestseller = 1 LIMIT 4");
?>

<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Discover Your Next <br><span>Favorite Book</span></h1>
        <p class="hero-subtitle">Trendy &bull; Discounted &bull; Best Sellers</p>
        <div class="hero-buttons">
            <a href="<?= app_url('pages/shop.php') ?>" class="btn btn-primary btn-lg"><i class="fas fa-shopping-bag"></i> Shop Now</a>
            <a href="#categories" class="btn btn-secondary btn-lg"><i class="fas fa-compass"></i> Explore</a>
        </div>
    </div>
</section>

<section class="categories-section" id="categories">
    <div class="container">
        <h2 class="section-title">Browse Categories</h2>
        <p class="section-subtitle">Find your perfect book by category</p>
        <div class="categories-grid">
            <?php while ($category = mysqli_fetch_assoc($cat_result)): ?>
                <div class="category-card" data-category="<?= (int)$category['id'] ?>">
                    <div class="category-icon"><?= escape($category['icon'] ?? '<i class="fas fa-book"></i>') ?></div>
                    <h3><?= escape($category['name']) ?></h3>
                    <p><?= escape($category['description']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<section class="books-section">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-fire text-orange"></i> Trendy Books</h2>
        <p class="section-subtitle">Hot titles everyone is reading right now</p>
        <div class="books-grid">
            <?php if (mysqli_num_rows($trendy_result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($trendy_result)): ?>
                    <?php render_book_card($book); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-book"></i>
                    <h3>No trendy books yet</h3>
                    <p>Check back soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="books-section" style="background: var(--color-bg-light);">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-tags"></i> Discount Books</h2>
        <p class="section-subtitle">Great reads at amazing prices</p>
        <div class="books-grid">
            <?php if (mysqli_num_rows($discount_result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($discount_result)): ?>
                    <?php render_book_card($book); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-tags"></i>
                    <h3>No discount books yet</h3>
                    <p>Check back soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="books-section featured-section">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-crown text-gold"></i> Best Sellers</h2>
        <p class="section-subtitle">Our most popular books loved by readers</p>
        <div class="books-grid">
            <?php if (mysqli_num_rows($bestseller_result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($bestseller_result)): ?>
                    <?php render_book_card($book); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-crown"></i>
                    <h3>No bestsellers yet</h3>
                    <p>Coming soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section style="padding: 80px 0; text-align: center; background: linear-gradient(135deg, rgba(201, 169, 98, 0.1), rgba(10, 10, 10, 0));">
    <div class="container">
        <h2 class="section-title" style="margin-bottom: 15px;">Ready to Start Reading?</h2>
        <p class="section-subtitle" style="margin-bottom: 30px;">Browse our full collection and find your next adventure.</p>
        <a href="<?= app_url('pages/shop.php') ?>" class="btn btn-primary btn-lg"><i class="fas fa-book-open"></i> Browse All Books</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
