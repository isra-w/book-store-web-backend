<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../includes/db.php';

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
$page_title = 'Registered Users';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin Panel</title>
    <?php include 'sidebar.php'; ?>
</head>
<body>

        <div class="admin-section">
            <div class="admin-section-header">
                <h2>All Users (<?php echo mysqli_num_rows($users); ?>)</h2>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>ID</th><th>Username</th><th>Email</th><th>Full Name</th><th>Phone</th><th>Registered</th></tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($users) > 0): ?>
                            <?php while ($user = mysqli_fetch_assoc($users)): ?>
                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td style="font-weight: 500;"><i class="fas fa-user" style="color: var(--admin-primary); margin-right: 8px;"></i><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center;"><div class="admin-empty"><i class="fas fa-users"></i><h3>No users yet</h3></div></td></tr>
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
