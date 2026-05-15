<?php
define('DB_HOST', 'localhost:3306');
define('DB_NAME', 'real_estate_portal_db');
define('DB_USER', 'root');
define('DB_PASS', '');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
