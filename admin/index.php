<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../includes/db.php';

// Get stats
$books_count  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM books"))['total'];
$users_count  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$orders_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$total_sales  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'"))['total'] ?? 0;

// Get last 5 orders
$recent_orders = mysqli_query($conn, "SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");

$page_title = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <?php include 'sidebar.php'; ?>
</head>
<body>

        <!-- Stat Cards -->
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <div class="dashboard-card-icon card-books"><i class="fas fa-book"></i></div>
                <div class="dashboard-card-info"><h3><?php echo $books_count; ?></h3><p>Total Books</p></div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card-icon card-users"><i class="fas fa-users"></i></div>
                <div class="dashboard-card-info"><h3><?php echo $users_count; ?></h3><p>Registered Users</p></div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card-icon card-orders"><i class="fas fa-shopping-bag"></i></div>
                <div class="dashboard-card-info"><h3><?php echo $orders_count; ?></h3><p>Total Orders</p></div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card-icon card-sales"><i class="fas fa-money-bill-wave"></i></div>
                <div class="dashboard-card-info"><h3><?php echo CURRENCY . ' ' . number_format($total_sales, 2); ?></h3><p>Total Sales</p></div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="admin-section">
            <div class="admin-section-header">
                <h2><i class="fas fa-clock"></i> Recent Orders</h2>
                <a href="orders.php" class="admin-btn admin-btn-primary admin-btn-sm">View All</a>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                            <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['full_name'] ?? $order['username'] ?? 'Guest'); ?></td>
                                    <td><?php echo CURRENCY . ' ' . number_format($order['total_amount'], 2); ?></td>
                                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align: center; color: var(--admin-muted);">No orders yet</td></tr>
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
