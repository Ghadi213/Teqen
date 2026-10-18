<?php
session_start();

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
  die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // تحقق من وجود البيانات المطلوبة
  if (!isset($_POST['report_id'], $_POST['message'], $_SESSION['user_id'], $_SESSION['role'])) {
    die("بيانات ناقصة: تأكد من إرسال report_id والرسالة وأن المستخدم مسجل الدخول.");
  }

  $report_id = intval($_POST['report_id']);
  $sender_id = intval($_SESSION['user_id']);
  $sender_type = $_SESSION['role'];
  $message = trim($_POST['message']);

  if ($message === '') {
    die("لا يمكن إرسال رسالة فارغة.");
  }

  // تنفيذ الإدخال في قاعدة البيانات
  $stmt = $conn->prepare("INSERT INTO messages (report_id, sender_id, sender_type, message) VALUES (?, ?, ?, ?)");
  if (!$stmt) {
    die("فشل التحضير: " . $conn->error);
  }

  $stmt->bind_param("iiss", $report_id, $sender_id, $sender_type, $message);
  if (!$stmt->execute()) {
    die("فشل التنفيذ: " . $stmt->error);
  }
  $stmt->close();

  // التوجيه حسب نوع المستخدم
  if ($sender_type === 'digital') {
    header("Location: Details.php?id=$report_id");
  } else {
    header("Location: user_details.php?id=$report_id");
  }

  exit();
}
?>