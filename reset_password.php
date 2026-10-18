<?php
// نبدأ الجلسة ونجيب الترجمة
session_start();
require_once "lang.php"; // ملف الترجمة

// نضبط توقيت UTC عشان تنتهي صلاحية الرابط بشكل دقيق
date_default_timezone_set('UTC');

// نتصل بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
    die("فشل الاتصال: " . mysqli_connect_error());
}

// لو ما فيه توكن بالرابط نطلع له رسالة
if (!isset($_GET['token'])) {
    die($translations['reset_invalid'][$_SESSION['lang']]);
}

// ناخذ التوكن من الرابط
$token = $_GET['token'];

// نتحقق إذا التوكن موجود وصالح (ما انتهت مدته)
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > UTC_TIMESTAMP()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// إذا ما حصلنا المستخدم نوقف ونقول الرابط مو صالح
if (!$user = $result->fetch_assoc()) {
    die($translations['reset_expired'][$_SESSION['lang']]);
}

// لو ضغط المستخدم زر "تحديث كلمة المرور"
$successMsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // نأخذ كلمة المرور الجديدة ونشفرها
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // نحدث كلمة المرور ونحذف التوكن والتاريخ
    $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
    $update->bind_param("si", $newPassword, $user['id']);
    $update->execute();

    // نحط له رسالة نجاح
    $successMsg = $translations['reset_success'][$_SESSION['lang']];
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo $_SESSION['lang'] === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
  <meta charset="UTF-8">
  <title><?php echo $translations['reset_title'][$_SESSION['lang']]; ?></title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="reset-global-body">

  <div class="reset-global-container">
    <div class="reset-card">
      <div class="icon-circle">🔒</div>
      
      <!-- عنوان الصفحة -->
      <h2><?php echo $translations['reset_title'][$_SESSION['lang']]; ?></h2>
      
      <?php if ($successMsg): ?>
        <!-- نعرض رسالة نجاح إذا خلص -->
        <p class="success-msg"><?php echo $successMsg; ?></p>
      <?php else: ?>
        <!-- الفورم يدخل فيه كلمة المرور الجديدة -->
        <form method="POST">
          <input type="password" name="new_password" placeholder="<?php echo $translations['new_password'][$_SESSION['lang']]; ?>" required>
          <button type="submit" class="reset-main-btn"><?php echo $translations['update_password'][$_SESSION['lang']]; ?></button>
        </form>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>