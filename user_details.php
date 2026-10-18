<?php
session_start();
include 'lang.php'; // ملف الترجمة

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$lang = $_SESSION['lang']; // تحديد اللغة الحالية

if (!isset($_GET['id'])) {
  die("رقم البلاغ غير موجود.");
}

$reportId = $_GET['id'];
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
  die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// تحديث الرسائل إلى "مقروءة" لهذا المستخدم
$my_id = $_SESSION['user_id'];
$report_id = $_GET['id'];

$updateSeenQuery = "UPDATE messages 
                    SET seen_by = CONCAT_WS(',', seen_by, ?) 
                    WHERE report_id = ? AND NOT FIND_IN_SET(?, seen_by)";
$stmt = $conn->prepare($updateSeenQuery);
$stmt->bind_param("iii", $my_id, $report_id, $my_id);
$stmt->execute();

$query = "SELECT reports.*, users.username, users.department 
          FROM reports 
          JOIN users ON reports.user_id = users.id 
          WHERE reports.id = $reportId";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
  die($translations['report_not_found'][$lang]);
}

$report = mysqli_fetch_assoc($result);

// دالة الترجمة للحالة
function getStatusLabel($status, $lang, $translations) {
  switch ($status) {
    case 'new': return $translations['status_new'][$lang];
    case 'processing': return $translations['status_processing'][$lang];
    case 'done': return $translations['status_done'][$lang];
    default: return $status;
  }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title><?= $translations['report_details'][$lang] ?></title>
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
  <div class="lang-toggle-container" style="left: 20px; right: auto;">
  <form method="get" onchange="this.submit()">
    <!-- نحفظ رقم البلاغ عشان نرجع له -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
    <!-- قائمة اللغات -->
    <select name="lang">
      <option value="ar" <?php if ($_SESSION['lang'] === 'ar') echo 'selected'; ?>>العربية</option>
      <option value="en" <?php if ($_SESSION['lang'] === 'en') echo 'selected'; ?>>English</option>
    </select>
  </form>
</div>

  <div class="content">
    <h2><?= $translations['report_details'][$lang] ?></h2>

    <div class="info-box">
      <p><strong><?= $translations['report_id'][$lang] ?>:</strong> <?= $report['id'] ?></p>
      <p><strong><?= $translations['department'][$lang] ?>:</strong> <?= htmlspecialchars($report['department']) ?></p>
      <p><strong><?= $translations['status'][$lang] ?>:</strong> <?= getStatusLabel($report['status'], $lang, $translations) ?></p>
      <p><strong><?= $translations['title'][$lang] ?>:</strong> <?= htmlspecialchars($report['title']) ?></p>
      <p><strong><?= $translations['description_label'][$lang] ?>:</strong> <?= htmlspecialchars($report['description']) ?></p>
      <p><strong><?= $translations['date'][$lang] ?>:</strong> <?= htmlspecialchars($report['created_at']) ?></p>

      <?php if (!empty($report['handled_by'])): ?>
        <p><strong><?= $translations['handled_by'][$lang] ?>:</strong> <?= htmlspecialchars($report['handled_by']) ?></p>
      <?php endif; ?>

      <?php if (!empty($report['image_path'])): ?>
        <div class="attachments">
          <strong><?= $translations['attachments'][$lang] ?>:</strong><br>
          <a href="<?= htmlspecialchars($report['image_path']) ?>" target="_blank">
            <img src="<?= htmlspecialchars($report['image_path']) ?>" width="250">
          </a>
        </div>
      <?php endif; ?>

      <?php if ($report['status'] == 'processing' || $report['status'] == 'done'): ?>
        <div class="chat-box">
          <h3 style="text-align:center;"><?= $translations['chat'][$lang] ?></h3>

          <?php
          $stmt = $conn->prepare("SELECT * FROM messages WHERE report_id = ? ORDER BY timestamp ASC");
          $stmt->bind_param("i", $reportId);
          $stmt->execute();
          $messages = $stmt->get_result();
          ?>

          <?php while ($msg = $messages->fetch_assoc()): ?>
            <div class="chat-msg <?= ($msg['sender_id'] == $_SESSION['user_id']) ? 'chat-me' : 'chat-other' ?>">
              <p><?= htmlspecialchars($msg['message']) ?></p>
              <span class="chat-time"><?= $msg['timestamp'] ?></span>
            </div>
          <?php endwhile; ?>

          <?php if ($report['status'] == 'processing'): ?>
            <form action="send_message.php" method="POST" class="chat-form">
              <input type="hidden" name="report_id" value="<?= $reportId ?>">
              <textarea name="message" placeholder="<?= $translations['type_here'][$lang] ?>" required></textarea>
              <button type="submit"><?= $translations['btn_send'][$lang] ?></button>
            </form>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    if (localStorage.getItem("darkMode") === "enabled") {
      document.body.classList.add("dark-mode");
    }
  </script>
</body>
</html>