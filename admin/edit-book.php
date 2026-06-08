<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../includes/db.php';

// Get book ID, redirect if missing
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$book_id) { header('Location: books.php'); exit; }

// Fetch the book
$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$book = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$book) { header('Location: books.php'); exit; }

$error = '';
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
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

    $cover_image = $book['cover_image']; // keep old image by default

    // Handle new image upload
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/../uploads/book-covers/';
        $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $filename = 'book_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_dir . $filename)) {
                // Delete old image
                if ($book['cover_image'] && file_exists($upload_dir . $book['cover_image'])) {
                    unlink($upload_dir . $book['cover_image']);
                }
                $cover_image = $filename;
            }
        }
    }

    if (empty($error)) {
        $stmt = mysqli_prepare($conn, "UPDATE books SET title=?, author=?, description=?, price=?, discount_price=?, category_id=?, sku=?, book_type=?, cover_image=?, stock=?, is_trendy=?, is_discount=?, is_bestseller=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssddissiiiiii", $title, $author, $description, $price, $discount_price, $category_id, $sku, $book_type, $cover_image, $stock, $is_trendy, $is_discount, $is_bestseller, $book_id);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: books.php?msg=updated');
            exit;
        } else {
            $error = 'Failed to update book.';
        }
    }
}

$page_title = 'Edit Book';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Admin Panel</title>
    <?php include 'sidebar.php'; ?>
</head>
<body>

        <?php if ($error): ?><div class="admin-alert admin-alert-error"><?php echo $error; ?></div><?php endif; ?>

        <div class="admin-section">
            <form method="POST" action="" enctype="multipart/form-data" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Book Title *</label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($book['title']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo htmlspecialchars($book['description']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Price (<?php echo CURRENCY; ?>) *</label>
                        <input type="number" name="price" step="0.01" required value="<?php echo $book['price']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Discount Price (optional)</label>
                        <input type="number" name="discount_price" step="0.01" value="<?php echo $book['discount_price']; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id">
                            <option value="">Select Category</option>
                            <?php mysqli_data_seek($categories, 0); while ($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($book['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" value="<?php echo htmlspecialchars($book['sku']); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Book Type</label>
                        <input type="text" name="book_type" value="<?php echo htmlspecialchars($book['book_type']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Stock *</label>
                        <input type="number" name="stock" required value="<?php echo $book['stock']; ?>" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label>Current Cover</label><br>
                    <img src="<?php echo $book['cover_image'] ? '../uploads/book-covers/' . $book['cover_image'] : '../assets/images/book-placeholder.jpg'; ?>"
                         style="width: 100px; height: 130px; object-fit: cover; border-radius: 6px; margin-bottom: 10px;"
                         onerror="this.src='../assets/images/book-placeholder.jpg'"><br>
                    <label>Upload New Cover (optional)</label>
                    <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>

                <div class="form-group" style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_trendy" value="1" <?php echo $book['is_trendy'] ? 'checked' : ''; ?>> Trendy
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_discount" value="1" <?php echo $book['is_discount'] ? 'checked' : ''; ?>> On Sale
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_bestseller" value="1" <?php echo $book['is_bestseller'] ? 'checked' : ''; ?>> Best Seller
                    </label>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="update_book" class="admin-btn admin-btn-primary"><i class="fas fa-save"></i> Update Book</button>
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
