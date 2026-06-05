<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../includes/db.php';

// Delete book if requested
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cover_image FROM books WHERE id = $id"));

    // Delete the image file if it exists
    if ($row && $row['cover_image']) {
        $path = __DIR__ . '/../uploads/book-covers/' . $row['cover_image'];
        if (file_exists($path)) unlink($path);
    }

    mysqli_query($conn, "DELETE FROM books WHERE id = $id");
    header('Location: books.php?msg=deleted');
    exit;
}

$books = mysqli_query($conn, "SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id ORDER BY b.id DESC");
$message = isset($_GET['msg']) ? $_GET['msg'] : '';
$page_title = 'Manage Books';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Admin Panel</title>
    <?php include 'sidebar.php'; ?>
</head>
<body>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-success">
                Book <?php echo $message; ?> successfully!
            </div>
        <?php endif; ?>

        <div class="admin-section">
            <div class="admin-section-header">
                <h2>All Books</h2>
                <a href="add-book.php" class="admin-btn admin-btn-primary"><i class="fas fa-plus"></i> Add New Book</a>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>ID</th><th>Cover</th><th>Title</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($books) > 0): ?>
                            <?php while ($book = mysqli_fetch_assoc($books)): ?>
                                <tr>
                                    <td>#<?php echo $book['id']; ?></td>
                                    <td>
                                        <img src="<?php echo $book['cover_image'] ? '../uploads/book-covers/' . $book['cover_image'] : '../assets/images/book-placeholder.jpg'; ?>"
                                             alt="<?php echo htmlspecialchars($book['title']); ?>"
                                             onerror="this.src='../assets/images/book-placeholder.jpg'">
                                    </td>
                                    <td style="font-weight: 500;"><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td><?php echo CURRENCY . ' ' . number_format($book['price'], 2); ?></td>
                                    <td><?php echo $book['stock']; ?></td>
                                    <td>
                                        <a href="edit-book.php?id=<?php echo $book['id']; ?>" class="admin-btn admin-btn-warning admin-btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="books.php?delete=<?php echo $book['id']; ?>" class="admin-btn admin-btn-danger admin-btn-sm" onclick="return confirm('Delete this book?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center;"><div class="admin-empty"><i class="fas fa-book"></i><h3>No books found</h3></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
