<?php
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username         = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email            = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username/email already exists
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($check, "ss", $username, $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = 'Username or email already taken.';
        } else {
            // Save new user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
            mysqli_stmt_bind_param($insert, "sss", $username, $email, $hashed);

            if (mysqli_stmt_execute($insert)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = $username;
                $success = true;
                header('Refresh: 2; URL=' . SITE_URL . 'index.php');
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<section class="auth-page">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-user-plus" style="font-size: 3rem; color: var(--color-gold);"></i>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success" style="text-align: center;">
                <i class="fas fa-check-circle"></i> Welcome, <?php echo htmlspecialchars($username); ?>! Redirecting...
            </div>
        <?php else: ?>
            <h1>Create Account</h1>
            <p class="auth-subtitle">Join our book community</p>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Choose a username (min 3 characters)"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="your@email.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="Min 6 characters">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required placeholder="Repeat password">
                    </div>
                </div>
                <button type="submit" name="register" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Sign in here</a></p>
                <p style="margin-top: 10px;"><a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Home</a></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
