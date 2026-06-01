<?php
require_once __DIR__ . '/../includes/header.php';

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $sent = true; // In a real app you would send an email here
    }
}
?>

<section class="contact-page">
    <div class="container">
        <h1 class="section-title" style="text-align: left; margin-bottom: 10px;"><i class="fas fa-envelope"></i> Contact Us</h1>
        <p style="color: var(--color-text-muted); margin-bottom: 40px;">We'd love to hear from you. Send us a message!</p>

        <div class="contact-grid">
            <!-- Contact Form -->
            <div>
                <?php if ($sent): ?>
                    <div style="background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 50px; text-align: center;">
                        <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--color-success); margin-bottom: 20px;"></i>
                        <h2 style="font-family: var(--font-heading); margin-bottom: 15px;">Message Sent!</h2>
                        <p style="color: var(--color-text-muted);">Thank you for reaching out. We'll get back to you soon.</p>
                        <a href="contact.php" class="btn btn-primary mt-2">Send Another Message</a>
                    </div>
                <?php else: ?>
                    <form method="POST" action="" style="background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--card-radius); padding: 30px;">
                        <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Your Name *</label>
                                <input type="text" name="name" required placeholder="Enter your name">
                            </div>
                            <div class="form-group">
                                <label>Email Address *</label>
                                <input type="email" name="email" required placeholder="your@email.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Subject *</label>
                            <input type="text" name="subject" required placeholder="What is this about?">
                        </div>
                        <div class="form-group">
                            <label>Your Message *</label>
                            <textarea name="message" rows="5" required placeholder="Write your message here..."></textarea>
                        </div>
                        <button type="submit" name="send_message" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Contact Info -->
            <div class="contact-info-card">
                <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <div><h4>Email Us</h4><p>support@jjbookshopping.com</p><p>info@jjbookshopping.com</p></div>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-phone"></i>
                    <div><h4>Call Us</h4><p>+251 911 234 567</p><p>+251 922 345 678</p></div>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div><h4>Visit Us</h4><p>Addis Ababa, Ethiopia</p><p>Bole Sub-City, Africa Avenue</p></div>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-clock"></i>
                    <div><h4>Working Hours</h4><p>Monday - Friday: 9:00 AM - 6:00 PM</p><p>Saturday: 10:00 AM - 4:00 PM</p><p>Sunday: Closed</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
