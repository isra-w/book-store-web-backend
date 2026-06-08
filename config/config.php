<?php
// Database settings - change these to match your setup

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // default XAMPP username
define('DB_PASS', '');           // default XAMPP password (empty)
define('DB_NAME', 'jj_bookshop');

// Website settings
define('SITE_NAME', 'JJ Book Shopping');
define('SITE_URL', 'http://localhost:8080/JJ-book-shopping-simplified/');
define('CURRENCY', 'ETB');

// Show errors (useful while learning)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set timezone
date_default_timezone_set('Africa/Addis_Ababa');
?>
