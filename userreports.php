<?php
session_start();
include 'lang.php'; // ملف الترجمة

// دالة التحقق من وجود رسائل غير مقروءة
function hasUnreadMessage($conn, $report_id, $my_id) {
  $stmt = $conn->prepare("SELECT COUNT(*) AS total 
                          FROM messages 
                          WHERE report_id = ? 
                          AND NOT FIND_IN_SET(?, seen_by)");
  $stmt->bind_param("ii", $report_id, $my_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  return ($data['total'] > 0);
}

// تأكدي أن المستخدم مسجّل دخول
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
  die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// استرجاع بلاغات المستخدم الحالي
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM reports WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// نستخدم ملف الترجمة عشان نظهر الحالة حسب اللغة
function getStatusLabel($status, $lang, $translations) {
  switch ($status) {
    case 'new': return $translations['status_new'][$lang];
    case 'processing': return $translations['status_processing'][$lang];
    case 'done': return $translations['status_done'][$lang];
    default: return 'غير معروف';
  }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title><?= $translations['my_reports'][$lang] ?> - تِقن</title>
  <link rel="stylesheet" href="Estyle.css">
</head>
<body>
  <!-- القائمة الجانبية -->
  <aside class="sidebar">
    <div class="logo">
      <img src="logo.png" alt="شعار المستشفى">
    </div>
    <nav class="nav">
      <a href="new_report_form.php"><?= $translations['new_report'][$lang] ?></a>
      <a href="userreports.php" class="active"><?= $translations['my_reports'][$lang] ?></a>
      <a href="usersettings.php"><?= $translations['user_settings'][$lang] ?></a>
      <a href="logout.php"><?= $translations['logout'][$lang] ?></a>
    </nav>
  </aside>

   <!-- ✅ اختيار اللغة -->
  <div class="lang-toggle-container" >
    <form method="get" onchange="this.submit()">
      <select name="lang">
        <option value="ar" <?php if ($_SESSION['lang'] === 'ar') echo 'selected'; ?>>العربية</option>
        <option value="en" <?php if ($_SESSION['lang'] === 'en') echo 'selected'; ?>>English</option>
      </select>
    </form>
  </div>
  <!-- المحتوى الرئيسي -->
  <main class="content">
    <div class="welcome">
      <h1><?= $translations['my_reports'][$lang] ?></h1>
      <p><?= $translations['faq_a3'][$lang] ?></p>
    </div>

    <!-- عرض البلاغات -->
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="report-item">
        <div class="report-header">
          <div>
            <strong><?= htmlspecialchars($row['title']) ?></strong> –
            <?= $translations['status'][$lang] ?>:
            <span class="status <?= $row['status'] ?>">
              <?= getStatusLabel($row['status'], $lang, $translations) ?>
            </span>
            <?php if (hasUnreadMessage($conn, $row['id'], $user_id)) : ?>
              <span style="color: red; font-weight: bold; margin-right: 10px;">
                💬 <?= $translations['you_have_message'][$lang] ?>
              </span>
            <?php endif; ?>
          </div>
          <a href="user_details.php?id=<?= $row['id'] ?>" class="btn">
            <?= $translations['view_details'][$lang] ?>
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </main>

  

  <script>
    // أول ما تفتح الصفحة نشيك هل تم تفعيل الوضع الليلي
    if (localStorage.getItem("darkMode") === "enabled") {
      document.body.classList.add("dark-mode");
    }
  </script>
</body>
</html>