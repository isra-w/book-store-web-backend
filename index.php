<?php
require_once __DIR__ . '/includes/header.php';

// Get categories
$cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id");

// Get Trendy Books (up to 4)
$trendy_result = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.is_trendy = 1 LIMIT 4");

// Get Discount Books (up to 4)
$discount_result = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.is_discount = 1 LIMIT 4");

// Get Best Sellers (up to 4)
$bestseller_result = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.is_bestseller = 1 LIMIT 4");

// Helper function to show a book card
function showBookCard($book, $path_prefix = '') {
    $img = $book['cover_image'] ? $path_prefix . 'uploads/book-covers/' . $book['cover_image'] : $path_prefix . 'assets/images/book-placeholder.jpg';
    $placeholder = $path_prefix . 'assets/images/book-placeholder.jpg';
    ?>
    <div class="book-card">
        <div class="book-image">
            <?php if ($book['is_trendy']): ?><span class="book-badge badge-trendy">Trendy</span><?php endif; ?>
            <?php if ($book['is_discount']): ?><span class="book-badge badge-discount">Sale</span><?php endif; ?>
            <?php if ($book['is_bestseller']): ?><span class="book-badge badge-bestseller">Best Seller</span><?php endif; ?>
            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" onerror="this.src='<?php echo $placeholder; ?>'">
        </div>
        <div class="book-content">
            <span class="book-category"><?php echo htmlspecialchars($book['category_name'] ?? 'Book'); ?></span>
            <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
            <p class="book-description"><?php echo htmlspecialchars($book['description']); ?></p>
            <div class="book-price-row">
                <span class="book-price"><?php echo CURRENCY; ?> <?php echo number_format($book['price'], 2); ?></span>
                <?php if ($book['discount_price']): ?>
                    <span class="book-old-price"><?php echo CURRENCY; ?> <?php echo number_format($book['discount_price'], 2); ?></span>
                <?php endif; ?>
            </div>
            <div class="book-actions">
                <a href="<?php echo $path_prefix; ?>pages/product.php?id=<?php echo $book['id']; ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-eye"></i> View
                </a>
                <form method="POST" action="<?php echo $path_prefix; ?>pages/cart.php" style="flex: 1;">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">
                        <i class="fas fa-cart-plus"></i> Add
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Discover Your Next <br><span>Favorite Book</span></h1>
        <p class="hero-subtitle">Trendy &bull; Discounted &bull; Best Sellers</p>
        <div class="hero-buttons">
            <a href="pages/shop.php" class="btn btn-primary btn-lg"><i class="fas fa-shopping-bag"></i> Shop Now</a>
            <a href="#categories" class="btn btn-secondary btn-lg"><i class="fas fa-compass"></i> Explore</a>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories-section" id="categories">
    <div class="container">
        <h2 class="section-title">Browse Categories</h2>
        <p class="section-subtitle">Find your perfect book by category</p>
        <div class="categories-grid">
            <?php while ($category = mysqli_fetch_assoc($cat_result)): ?>
                <div class="category-card" data-category="<?php echo $category['id']; ?>">
                    <div class="category-icon"><?php echo $category['icon']; ?></div>
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p><?php echo htmlspecialchars($category['description']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Trendy Books -->
<section class="books-section">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-fire text-orange"></i> Trendy Books</h2>
        <p class="section-subtitle">Hot titles everyone's reading right now</p>
        <div class="books-grid">
            <?php if (mysqli_num_rows($trendy_result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($trendy_result)): ?>
                    <?php showBookCard($book); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-book"></i>
                    <h3>No trendy books yet</h3>
                    <p>Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Discount Books -->
<section class="books-section" style="background: var(--color-bg-light);">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-tags"></i> Discount Books</h2>
        <p class="section-subtitle">Great reads at amazing prices</p>
        <div class="books-grid">
            <?php if (mysqli_num_rows($discount_result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($discount_result)): ?>
                    <?php showBookCard($book); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-tags"></i>
                    <h3>No discount books yet</h3>
                    <p>Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Best Sellers -->
<section class="books-section featured-section">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-crown text-gold"></i> Best Sellers</h2>
        <p class="section-subtitle">Our most popular books loved by readers</p>
        <div class="books-grid">
            <?php if (mysqli_num_rows($bestseller_result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($bestseller_result)): ?>
                    <?php showBookCard($book); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-crown"></i>
                    <h3>No bestsellers yet</h3>
                    <p>Coming soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section style="padding: 80px 0; text-align: center; background: linear-gradient(135deg, rgba(201, 169, 98, 0.1), rgba(10, 10, 10, 0));">
    <div class="container">
        <h2 class="section-title" style="margin-bottom: 15px;">Ready to Start Reading?</h2>
        <p class="section-subtitle" style="margin-bottom: 30px;">Browse our full collection and find your next adventure</p>
        <a href="pages/shop.php" class="btn btn-primary btn-lg"><i class="fas fa-book-open"></i> Browse All Books</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
