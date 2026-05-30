<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/db.php';

// Count total items in cart
function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Online Bookstore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<nav class="navbar" id="navbar">
    <div class="container nav-container">
        <a href="<?php echo SITE_URL; ?>index.php" class="logo">
            <i class="fas fa-book-open"></i>
            <span>JJ BOOK SHOPPING</span>
        </a>

        <button class="mobile-toggle" id="mobileToggle" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>

        <ul class="nav-menu" id="navMenu">
            <li><a href="<?php echo SITE_URL; ?>index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="<?php echo SITE_URL; ?>pages/shop.php" class="nav-link <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a></li>
            <li><a href="<?php echo SITE_URL; ?>pages/about.php" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a></li>
            <li><a href="<?php echo SITE_URL; ?>pages/contact.php" class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>

            <li>
                <a href="<?php echo SITE_URL; ?>pages/cart.php" class="nav-link cart-link">
                    <i class="fas fa-shopping-cart"></i> Cart
                    <span class="cart-count" id="cartCount"><?php echo getCartCount(); ?></span>
                </a>
            </li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="user-dropdown">
                    <a href="#" class="nav-link user-link">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo SITE_URL; ?>pages/cart.php"><i class="fas fa-shopping-cart"></i> My Cart</a></li>
                        <li><a href="<?php echo SITE_URL; ?>pages/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="<?php echo SITE_URL; ?>pages/login.php" class="nav-link btn-login">Login</a></li>
                <li><a href="<?php echo SITE_URL; ?>pages/register.php" class="nav-link btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<main class="main-content">
