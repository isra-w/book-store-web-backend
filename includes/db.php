<?php
// Connect to the database
require_once __DIR__ . '/../config/config.php';

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Stop and show error if connection fails
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
