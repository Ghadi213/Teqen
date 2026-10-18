<?php
// Copy this file to config.php and fill in your own values.
// config.php is listed in .gitignore, so your passwords never reach GitHub.

// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'teqen_db');

// Email (used by "Forgot password")
// For Gmail, create an App Password: Google Account > Security > App passwords
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');

// Website address, used in the password reset link (no slash at the end)
define('APP_URL', 'http://localhost/Teqen');

// Secret code that IT staff type as their password when signing up,
// so their account gets the IT ("digital") role
define('DIGITAL_SIGNUP_CODE', 'change-this-code');
