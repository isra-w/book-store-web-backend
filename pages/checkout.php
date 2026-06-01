<?php
require_once __DIR__ . '/../includes/header.php';

// Redirect to cart if it is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$order_success = false;
$order_error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $full_name      = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email          = mysqli_real_escape_string($conn, trim($_POST['email']));
    $address        = mysqli_real_escape_string($conn, trim($_POST['address']));
    $phone          = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    if (empty($full_name) || empty($email) || empty($address) || empty($phone) || empty($payment_method)) {
        $order_error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $order_error = 'Please enter a valid email address.';
    } else {
        // Calculate total
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $total = $subtotal + 50; // 50 ETB shipping

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Save order to database
        $order_stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, full_name, email, address, phone, payment_method, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
        mysqli_stmt_bind_param($order_stmt, "isssssd", $user_id, $full_name, $email, $address, $phone, $payment_method, $total);

        if (mysqli_stmt_execute($order_stmt)) {
            $order_id = mysqli_insert_id($conn);

            // Save each cart item
            foreach ($_SESSION['cart'] as $item) {
                $item_stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($item_stmt, "iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                mysqli_stmt_execute($item_stmt);
            }

            // Clear the cart
            $_SESSION['cart'] = [];
            $order_success = true;
            $success_order_id = $order_id;
        } else {
            $order_error = 'Something went wrong. Please try again.';
        }
    }
}

// Recalculate totals for display
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = 50;
$total = $subtotal + $shipping;
?>

<section class="checkout-page">
    <div class="container">

        <?php if ($order_success): ?>
            <div style="max-width: 600px; margin: 0 auto; text-align: center; padding: 60px 20px;">
                <div style="font-size: 5rem; color: var(--color-success); margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="section-title">Order Placed Successfully!</h1>
                <p style="color: var(--color-text-muted); margin-bottom: 10px;">Thank you for your purchase. Your order has been received.</p>
                <p style="color: var(--color-beige); font-size: 1.2rem; margin-bottom: 30px;">Order #<?php echo $success_order_id; ?></p>
                <a href="../index.php" class="btn btn-primary btn-lg"><i class="fas fa-home"></i> Back to Home</a>
                <a href="shop.php" class="btn btn-outline btn-lg" style="margin-left: 10px;"><i class="fas fa-book-open"></i> Continue Shopping</a>
            </div>

        <?php else: ?>
            <h1 class="section-title" style="text-align: left; margin-bottom: 10px;"><i class="fas fa-credit-card"></i> Checkout</h1>
            <p style="color: var(--color-text-muted); margin-bottom: 30px;">Complete your purchase</p>

            <?php if ($order_error): ?>
                <div class="alert alert-error"><?php echo $order_error; ?></div>
            <?php endif; ?>

            <div class="checkout-grid">
                <!-- Billing Form -->
                <div>
                    <form method="POST" action="" class="checkout-form">
                        <h3 style="font-family: var(--font-heading); margin-bottom: 20px; color: var(--color-beige);">
                            <i class="fas fa-user"></i> Billing Details
                        </h3>

                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="full_name" required placeholder="Enter your full name"
                                   value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Email Address *</label>
                                <input type="email" name="email" required placeholder="your@email.com"
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Phone Number *</label>
                                <input type="tel" name="phone" required placeholder="+251 911 234 567"
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Delivery Address *</label>
                            <input type="text" name="address" required placeholder="Street address, city, country"
                                   value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                        </div>

                        <h3 style="font-family: var(--font-heading); margin: 30px 0 20px; color: var(--color-beige);">
                            <i class="fas fa-wallet"></i> Payment Method
                        </h3>

                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                                <i class="fas fa-money-bill-wave"></i> <span>Cash on Delivery</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <i class="fas fa-university"></i> <span>Bank Transfer</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="mobile_money">
                                <i class="fas fa-mobile-alt"></i> <span>Mobile Money</span>
                            </label>
                        </div>

                        <button type="submit" name="place_order" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 10px;">
                            <i class="fas fa-check"></i> Place Order
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="order-review">
                        <h3>Your Order</h3>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="order-item">
                                <span><?php echo htmlspecialchars($item['title']); ?> x<?php echo $item['quantity']; ?></span>
                                <span><?php echo CURRENCY; ?> <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="summary-row" style="border-top: 1px solid var(--color-border); margin-top: 15px; padding-top: 15px;">
                            <span>Subtotal</span><span><?php echo CURRENCY; ?> <?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span><span><?php echo CURRENCY; ?> <?php echo number_format($shipping, 2); ?></span>
                        </div>
                        <div class="summary-row" style="font-size: 1.2rem; font-weight: 700; color: var(--color-beige); border-top: 1px solid var(--color-border); margin-top: 10px; padding-top: 15px;">
                            <span>Total</span><span><?php echo CURRENCY; ?> <?php echo number_format($total, 2); ?></span>
                        </div>
                        <a href="cart.php" class="btn btn-outline" style="width: 100%; margin-top: 15px;"><i class="fas fa-arrow-left"></i> Back to Cart</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
