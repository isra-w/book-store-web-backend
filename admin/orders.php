<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../includes/db.php';

// Update order status
if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $order_id  = (int)$_GET['id'];
    $new_status = mysqli_real_escape_string($conn, $_GET['update_status']);
    $allowed = ['pending', 'completed', 'cancelled'];

    if (in_array($new_status, $allowed)) {
        mysqli_query($conn, "UPDATE orders SET status = '$new_status' WHERE id = $order_id");
    }
    header('Location: orders.php');
    exit;
}

$orders = mysqli_query($conn, "SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$page_title = 'Orders';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Admin Panel</title>
    <?php include 'sidebar.php'; ?>
</head>
<body>

        <div class="admin-section">
            <div class="admin-section-header">
                <h2>All Orders (<?php echo mysqli_num_rows($orders); ?>)</h2>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>Order #</th><th>Customer</th><th>Email</th><th>Payment</th><th>Amount</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($orders) > 0): ?>
                            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td style="font-weight: 500;"><?php echo htmlspecialchars($order['full_name'] ?? $order['username'] ?? 'Guest'); ?></td>
                                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></td>
                                    <td style="font-weight: 600;"><?php echo CURRENCY . ' ' . number_format($order['total_amount'], 2); ?></td>
                                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <?php if ($order['status'] == 'pending'): ?>
                                            <a href="orders.php?update_status=completed&id=<?php echo $order['id']; ?>" class="admin-btn admin-btn-primary admin-btn-sm" onclick="return confirm('Mark as completed?')"><i class="fas fa-check"></i></a>
                                            <a href="orders.php?update_status=cancelled&id=<?php echo $order['id']; ?>" class="admin-btn admin-btn-danger admin-btn-sm" onclick="return confirm('Cancel this order?')"><i class="fas fa-times"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="text-align: center;"><div class="admin-empty"><i class="fas fa-shopping-bag"></i><h3>No orders yet</h3></div></td></tr>
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
