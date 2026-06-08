<?php
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Find user by username or email
        $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $success = true;
            header('Refresh: 1; URL=' . SITE_URL . 'index.php');
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<section class="auth-page">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-book-open" style="font-size: 3rem; color: var(--color-gold);"></i>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success" style="text-align: center;">
                <i class="fas fa-check-circle"></i> Login successful! Redirecting...
            </div>
        <?php else: ?>
            <h1>Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your account</p>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Username or Email</label>
                    <input type="text" name="username" required placeholder="Enter your username or email"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter your password">
                </div>
                <button type="submit" name="login" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p style="margin-top: 10px;"><a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Home</a></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
