<?php
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
    die("فشل الاتصال: " . mysqli_connect_error());
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$department = $_POST['department'];
$role = 'normal';

$sql = "INSERT INTO users (username, email, password, department, role)
        VALUES ('$username', '$email', '$password', '$department', '$role')";

if (mysqli_query($conn, $sql)) {
   if ($role === 'digital') {
    header("Location: Home.html");
} else {
    header("Location: new_report_form.html");
}
exit();
} else {
    echo "خطأ: " . mysqli_error($conn);
}

mysqli_close($conn);
?>