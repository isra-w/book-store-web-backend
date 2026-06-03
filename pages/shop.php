<?php
require_once __DIR__ . '/../includes/header.php';

// How many books to show per page
$per_page = 8;

// Get current page number from URL
$page_num = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page_num - 1) * $per_page;

// Get search and category filter from URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Build the WHERE part of the SQL query
$where = "WHERE 1=1";
if ($search) $where .= " AND (b.title LIKE '%$search%' OR b.author LIKE '%$search%')";
if ($category_filter > 0) $where .= " AND b.category_id = $category_filter";

// Count total books (for pagination)
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM books b $where"))['total'];
$total_pages = ceil($total / $per_page);

// Get books for current page
$result = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id $where ORDER BY b.id DESC LIMIT $per_page OFFSET $offset");

// Get categories for the dropdown filter
$cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

// Build the URL query string for pagination links
$query_str = '';
if ($search) $query_str .= '&search=' . urlencode($search);
if ($category_filter) $query_str .= '&category=' . $category_filter;
?>

<section class="shop-header">
    <div class="container">
        <h1>Our Bookshop</h1>
        <p>Browse our collection of <?php echo $total; ?> books</p>
    </div>
</section>

<!-- Search and Filter -->
<section style="padding: 20px 0; background: var(--color-bg);">
    <div class="container">
        <div class="shop-controls">
            <form method="GET" action="" class="search-box">
                <input type="text" name="search" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>

            <form method="GET" action="" class="filter-dropdown">
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>
                <select name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($category_filter == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
        </div>
    </div>
</section>

<!-- Books Grid -->
<section class="books-section">
    <div class="container">
        <div class="books-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($result)): ?>
                    <div class="book-card">
                        <div class="book-image">
                            <?php if ($book['is_trendy']): ?><span class="book-badge badge-trendy">Trendy</span><?php endif; ?>
                            <?php if ($book['is_discount']): ?><span class="book-badge badge-discount">Sale</span><?php endif; ?>
                            <?php if ($book['is_bestseller']): ?><span class="book-badge badge-bestseller">Best</span><?php endif; ?>
                            <img src="<?php echo $book['cover_image'] ? '../uploads/book-covers/' . $book['cover_image'] : '../assets/images/book-placeholder.jpg'; ?>"
                                 alt="<?php echo htmlspecialchars($book['title']); ?>"
                                 onerror="this.src='../assets/images/book-placeholder.jpg'">
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
                                <a href="product.php?id=<?php echo $book['id']; ?>" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form method="POST" action="cart.php" style="flex: 1;">
                                    <input type="hidden" name="add_to_cart" value="1">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">
                                        <i class="fas fa-cart-plus"></i> Add
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-search"></i>
                    <h3>No books found</h3>
                    <p>Try different search or filter.</p>
                    <a href="shop.php" class="btn btn-primary mt-2">View All Books</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page_num > 1): ?>
                    <a href="?page=<?php echo $page_num - 1 . $query_str; ?>"><i class="fas fa-chevron-left"></i></a>
                <?php else: ?>
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page_num): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i . $query_str; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page_num < $total_pages): ?>
                    <a href="?page=<?php echo $page_num + 1 . $query_str; ?>"><i class="fas fa-chevron-right"></i></a>
                <?php else: ?>
                    <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
