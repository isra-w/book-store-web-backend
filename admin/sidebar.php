<?php
// Shared admin sidebar - included by all admin pages
$cur = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="admin.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="admin-wrapper">
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-brand"><i class="fas fa-book-open"></i><span>JJ Admin</span></div>
    <ul class="admin-nav">
        <li><a href="index.php" class="<?php echo ($cur == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="books.php" class="<?php echo in_array($cur, ['books.php','add-book.php','edit-book.php']) ? 'active' : ''; ?>"><i class="fas fa-book"></i> Books</a></li>
        <li><a href="users.php" class="<?php echo ($cur == 'users.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="orders.php" class="<?php echo ($cur == 'orders.php') ? 'active' : ''; ?>"><i class="fas fa-shopping-bag"></i> Orders</a></li>
        <li><a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a></li>
    </ul>
    <div class="admin-sidebar-footer">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</aside>

<main class="admin-main">
    <div class="admin-header">
        <button class="admin-mobile-toggle" id="adminMobileToggle"><i class="fas fa-bars"></i></button>
        <h1><?php echo $page_title ?? 'Dashboard'; ?></h1>
        <div class="admin-user">
            <i class="fas fa-user-shield"></i>
            <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
        </div>
    </div>
