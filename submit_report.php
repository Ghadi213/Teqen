<?php
session_start();

// التأكد أن المستخدم مسجل دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// استلام البيانات من النموذج
$title       = $_POST['title'];
$description = $_POST['description'];
$urgency     = $_POST['urgency'];
$user_id     = $_SESSION['user_id'];
$image_path  = null;

// رفع الصورة (إذا وُجدت)
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES['attachment']['name']);
    $target_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_path)) {
        $image_path = $target_path;
    }
}

// إدخال البلاغ في قاعدة البيانات
$sql = "INSERT INTO reports (user_id, title, description, urgency, image_path)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "issss", $user_id, $title, $description, $urgency, $image_path);

if (mysqli_stmt_execute($stmt)) {
    header("Location: new_report_form.php?success=1");
    exit;
} else {
    echo "فشل في حفظ البلاغ: " . mysqli_error($conn);
}
?>