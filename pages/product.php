<?php
require_once __DIR__ . '/../includes/header.php';

// Get book ID from the URL, redirect if missing
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($book_id <= 0) { header('Location: shop.php'); exit; }

// Fetch the book
$stmt = mysqli_prepare($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.id = ?");
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$book = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Redirect if book not found
if (!$book) { header('Location: shop.php'); exit; }

// Fetch related books from the same category
$rel_stmt = mysqli_prepare($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.category_id = ? AND b.id != ? LIMIT 4");
mysqli_stmt_bind_param($rel_stmt, "ii", $book['category_id'], $book_id);
mysqli_stmt_execute($rel_stmt);
$related_result = mysqli_stmt_get_result($rel_stmt);

$img = $book['cover_image'] ? '../uploads/book-covers/' . $book['cover_image'] : '../assets/images/book-placeholder.jpg';
?>

<section class="product-detail">
    <div class="container">
        <div class="product-layout">

            <!-- Book Image -->
            <div class="product-gallery">
                <div class="product-main-image">
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" onerror="this.src='../assets/images/book-placeholder.jpg'">
                </div>
            </div>

            <!-- Book Info -->
            <div class="product-info">
                <span class="book-category" style="font-size: 1rem;"><?php echo htmlspecialchars($book['category_name'] ?? 'Book'); ?></span>
                <h1><?php echo htmlspecialchars($book['title']); ?></h1>

                <div class="product-meta">
                    <?php if ($book['author']): ?><span><i class="fas fa-user"></i> <strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></span><?php endif; ?>
                    <?php if ($book['sku']): ?><span><i class="fas fa-barcode"></i> <strong>SKU:</strong> <?php echo htmlspecialchars($book['sku']); ?></span><?php endif; ?>
                    <?php if ($book['book_type']): ?><span><i class="fas fa-bookmark"></i> <strong>Type:</strong> <?php echo htmlspecialchars($book['book_type']); ?></span><?php endif; ?>
                    <span><i class="fas fa-boxes"></i> <strong>Stock:</strong> <?php echo $book['stock']; ?> available</span>
                </div>

                <div class="product-price-detail">
                    <?php echo CURRENCY; ?> <?php echo number_format($book['price'], 2); ?>
                    <?php if ($book['discount_price']): ?>
                        <span class="old-price"><?php echo CURRENCY; ?> <?php echo number_format($book['discount_price'], 2); ?></span>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>

                <!-- Add to Cart -->
                <form method="POST" action="cart.php">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <div class="quantity-selector">
                        <label>Quantity:</label>
                        <div class="qty-input">
                            <button type="button" id="qtyMinus">-</button>
                            <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="<?php echo $book['stock']; ?>">
                            <button type="button" id="qtyPlus">+</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 10px;">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>

                <!-- Tabs -->
                <div class="product-tabs">
                    <div class="tab-buttons">
                        <button class="tab-btn active" data-tab="tab-description">Description</button>
                        <button class="tab-btn" data-tab="tab-reviews">Reviews</button>
                        <button class="tab-btn" data-tab="tab-info">Additional Info</button>
                    </div>

                    <div class="tab-content active" id="tab-description">
                        <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                    </div>

                    <div class="tab-content" id="tab-reviews">
                        <div class="empty-state" style="padding: 30px 0;">
                            <i class="fas fa-star"></i>
                            <h3>No reviews yet</h3>
                            <p>Be the first to review this book!</p>
                        </div>
                    </div>

                    <div class="tab-content" id="tab-info">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr><td style="padding: 10px; border-bottom: 1px solid var(--color-border); color: var(--color-text-muted);">SKU</td><td style="padding: 10px; border-bottom: 1px solid var(--color-border);"><?php echo htmlspecialchars($book['sku'] ?? 'N/A'); ?></td></tr>
                            <tr><td style="padding: 10px; border-bottom: 1px solid var(--color-border); color: var(--color-text-muted);">Category</td><td style="padding: 10px; border-bottom: 1px solid var(--color-border);"><?php echo htmlspecialchars($book['category_name'] ?? 'N/A'); ?></td></tr>
                            <tr><td style="padding: 10px; border-bottom: 1px solid var(--color-border); color: var(--color-text-muted);">Type</td><td style="padding: 10px; border-bottom: 1px solid var(--color-border);"><?php echo htmlspecialchars($book['book_type'] ?? 'N/A'); ?></td></tr>
                            <tr><td style="padding: 10px; border-bottom: 1px solid var(--color-border); color: var(--color-text-muted);">Stock</td><td style="padding: 10px; border-bottom: 1px solid var(--color-border);"><?php echo $book['stock']; ?> in stock</td></tr>
                            <tr><td style="padding: 10px; color: var(--color-text-muted);">Date Added</td><td style="padding: 10px;"><?php echo date('F j, Y', strtotime($book['created_at'])); ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Books -->
        <?php if (mysqli_num_rows($related_result) > 0): ?>
            <section style="margin-top: 80px;">
                <h2 class="section-title">You May Also Like</h2>
                <div class="books-grid">
                    <?php while ($related = mysqli_fetch_assoc($related_result)): ?>
                        <div class="book-card">
                            <div class="book-image">
                                <img src="<?php echo $related['cover_image'] ? '../uploads/book-covers/' . $related['cover_image'] : '../assets/images/book-placeholder.jpg'; ?>"
                                     alt="<?php echo htmlspecialchars($related['title']); ?>"
                                     onerror="this.src='../assets/images/book-placeholder.jpg'">
                            </div>
                            <div class="book-content">
                                <h3 class="book-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                                <div class="book-price-row">
                                    <span class="book-price"><?php echo CURRENCY; ?> <?php echo number_format($related['price'], 2); ?></span>
                                </div>
                                <div class="book-actions">
                                    <a href="product.php?id=<?php echo $related['id']; ?>" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> View</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
