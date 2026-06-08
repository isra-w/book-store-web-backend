<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../includes/db.php';

$error = '';
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $title        = mysqli_real_escape_string($conn, trim($_POST['title']));
    $author       = mysqli_real_escape_string($conn, trim($_POST['author']));
    $description  = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price        = (float)$_POST['price'];
    $discount_price = !empty($_POST['discount_price']) ? (float)$_POST['discount_price'] : null;
    $category_id  = (int)$_POST['category_id'];
    $sku          = mysqli_real_escape_string($conn, trim($_POST['sku']));
    $book_type    = mysqli_real_escape_string($conn, trim($_POST['book_type']));
    $stock        = (int)$_POST['stock'];
    $is_trendy    = isset($_POST['is_trendy']) ? 1 : 0;
    $is_discount  = isset($_POST['is_discount']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;

    // Handle image upload
    $cover_image = '';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/../uploads/book-covers/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $filename = 'book_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_dir . $filename)) {
                $cover_image = $filename;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Only JPG, PNG, GIF, WEBP images are allowed.';
        }
    }

    if (empty($error)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO books (title, author, description, price, discount_price, category_id, sku, book_type, cover_image, stock, is_trendy, is_discount, is_bestseller, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "sssddissiiiii", $title, $author, $description, $price, $discount_price, $category_id, $sku, $book_type, $cover_image, $stock, $is_trendy, $is_discount, $is_bestseller);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: books.php?msg=added');
            exit;
        } else {
            $error = 'Failed to add book. Please try again.';
        }
    }
}

$page_title = 'Add New Book';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book - Admin Panel</title>
    <?php include 'sidebar.php'; ?>
</head>
<body>

        <?php if ($error): ?><div class="admin-alert admin-alert-error"><?php echo $error; ?></div><?php endif; ?>

        <div class="admin-section">
            <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Book Title *</label>
                        <input type="text" name="title" required placeholder="Enter book title">
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="author" placeholder="Enter author name">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="Enter book description"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Price (<?php echo CURRENCY; ?>) *</label>
                        <input type="number" name="price" step="0.01" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Discount Price (optional)</label>
                        <input type="number" name="discount_price" step="0.01" placeholder="0.00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id">
                            <option value="">Select Category</option>
                            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" placeholder="e.g., BK-001">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Book Type</label>
                        <input type="text" name="book_type" placeholder="e.g., Fiction, Novel">
                    </div>
                    <div class="form-group">
                        <label>Stock *</label>
                        <input type="number" name="stock" required value="100" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label>Cover Image</label>
                    <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                    <small style="color: var(--admin-muted);">Accepted: JPG, PNG, GIF, WEBP</small>
                </div>

                <div class="form-group" style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_trendy" value="1"> Trendy
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_discount" value="1"> On Sale
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_bestseller" value="1"> Best Seller
                    </label>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="add_book" class="admin-btn admin-btn-primary"><i class="fas fa-plus"></i> Add Book</button>
                    <a href="books.php" class="admin-btn" style="background: var(--admin-border); color: var(--admin-text);">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.getElementById('adminMobileToggle').addEventListener('click', function() {
    document.getElementById('adminSidebar').classList.toggle('active');
});
</script>
</body>
</html>
