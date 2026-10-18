<?php
// Database connection used by every page.
// The real values live in config.php, which is NOT uploaded to GitHub.
require_once __DIR__ . '/config.php';

function db_connect() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn) {
        mysqli_set_charset($conn, 'utf8mb4');
    }
    return $conn;
}
