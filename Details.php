<?php
// نبدأ الجلسة علشان نستخدم معلومات المستخدم لو احتجنا
session_start();

// نجيب ملف الترجمة
require_once 'lang.php';

// نتأكد إذا فيه رقم بلاغ بالعنوان (GET)
if (!isset($_GET['id'])) {
  die($translations['report_not_found'][$lang]);
}

// نحفظ رقم البلاغ في متغير
$reportId = $_GET['id'];

// نسوي اتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();

// إذا الاتصال فشل نوقف ونطبع رسالة
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

// إذا المستخدم ضغط على زر "استلام البلاغ"
if (isset($_POST['receive'])) {
  // نجيب اسم الموظف من الجلسة
  $employee = $_SESSION['username'];

  // نحدث حالة البلاغ ونخزن اسم المستلم
  $updateQuery = "UPDATE reports SET status='processing', handled_by='$employee' WHERE id=$reportId";
  mysqli_query($conn, $updateQuery);
}

// إذا المستخدم ضغط على زر "تم الحل"
if (isset($_POST['resolve'])) {
  // نغير حالة البلاغ إلى "تم الحل"
  $updateQuery = "UPDATE reports SET status='done' WHERE id=$reportId";
  mysqli_query($conn, $updateQuery);
}

// نجيب معلومات البلاغ مع معلومات القسم (من جدول المستخدمين)
$query = "SELECT reports.*,users.username  ,users.department 
          FROM reports 
          JOIN users ON reports.user_id = users.id 
          WHERE reports.id = $reportId";

$result = mysqli_query($conn, $query);

// إذا ما حصلنا بلاغ نوقف
if (mysqli_num_rows($result) == 0) {
  die($translations['report_not_found'][$lang]);
}

// نحفظ البيانات داخل متغير عشان نستخدمها في الصفحة
$report = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title><?= $translations['report_details'][$lang] ?> - تِقن</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- رأس الصفحة -->
<header>
  <div class="branding">
    <div style="max-width: 120px;">
      <img src="logo.png" alt="شعار" style="width: 100%; height: auto;">
    </div>
    <div class="text">
      <strong><?= $translations['system_name'][$lang] ?></strong>
      <span class="subtext"><?= $translations['digital_health_board'][$lang] ?></span>
    </div>
  </div>

  <!-- قائمة التنقل -->
  <nav>
    <a href="Home.php"><?= $translations['nav_home'][$lang] ?></a>
    <a href="Archive.php"><?= $translations['nav_archive'][$lang] ?></a>
    <a href="Reports.php"><?= $translations['nav_reports'][$lang] ?></a>
  </nav>

  <div class="icons">
    <span class="icon"><a href="settings.php">⚙️</a> </span>
  </div>
</header>


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

<!-- محتوى الصفحة الرئيسي -->
<main>
  <h2><?= $translations['report_details'][$lang] ?></h2>

  <!--  كل تفاصيل البلاغ -->
  <div class="info-box">
    <p><span><?= $translations['report_id'][$lang] ?>:</span> #<?= $report['id'] ?></p>
    <p><span><?= $translations['department'][$lang] ?>:</span> <?= htmlspecialchars($report['department']) ?></p>
    <p><span><?= $translations['reporter_name'][$lang] ?>:</span> <?= htmlspecialchars($report['username']) ?></p>
    <p><span><?= $translations['date'][$lang] ?>:</span> <?= date("d M Y", strtotime($report['created_at'])) ?></p>
    <p><span><?= $translations['title'][$lang] ?>:</span> <?= htmlspecialchars($report['title']) ?></p>
    <p><span><?= $translations['status'][$lang] ?>:</span>
      <?= ($report['status'] == 'new') ? $translations['status_new'][$lang] : (($report['status'] == 'processing') ? $translations['status_processing'][$lang] : $translations['status_done'][$lang]) ?>
    </p>

    <?php if (!empty($report['handled_by'])): ?>
      <p><span><?= $translations['handled_by'][$lang] ?>:</span> <?= htmlspecialchars($report['handled_by']) ?></p>
    <?php endif; ?>

    <!-- إذا فيه مرفق نعرضه -->
    <?php if (!empty($report['image_path'])): ?>
      <div class="attachments">
        <strong><?= $translations['attachments'][$lang] ?>:</strong><br>
        <a target="_blank" href="<?= htmlspecialchars($report['image_path']) ?>">
          <img src="<?= htmlspecialchars($report['image_path']) ?>" alt="المرفق" width="250">
        </a>
      </div>
    <?php endif; ?>

    <!-- إذا البلاغ ما هو "تم الحل"، نعرض الأزرار -->
    <?php if ($report['status'] != 'done'): ?>
      <form method="post" class="actions">
        <!-- إذا الحالة جديد نعرض زر استلام -->
        <?php if ($report['status'] == 'new'): ?>
          <button class="btn" type="submit" name="receive"><?= $translations['btn_receive'][$lang] ?></button>
        <?php endif; ?>
        <!-- نعرض دائمًا زر تم حلها -->
        <button class="btn success" type="submit" name="resolve"><?= $translations['btn_resolved'][$lang] ?></button>
      </form>
    <?php endif; ?>

    <?php if ($report['status'] == 'processing'): ?>
      <!-- إذا الحالة "قيد المعالجة" نعرض المحادثة -->
      <div class="chat-box">
        <h3 style="text-align:center;"><?= $translations['chat'][$lang] ?></h3>

        <?php
        // هنا نجيب كل الرسايل الخاصة بالبلاغ هذا و نرتبها حسب الوقت
        $stmt = $conn->prepare("SELECT * FROM messages WHERE report_id = ? ORDER BY timestamp ASC");
        $stmt->bind_param("i", $reportId);
        $stmt->execute();
        $messages = $stmt->get_result();
        ?>

        <!-- نبدأ نعرض كل رسالة -->
        <?php while ($msg = $messages->fetch_assoc()): ?>
          <div class="chat-msg <?= ($msg['sender_id'] == $_SESSION['user_id']) ? 'chat-me' : 'chat-other' ?>">
            <p><?= htmlspecialchars($msg['message']) ?></p>
            <span class="chat-time"><?= $msg['timestamp'] ?></span>
          </div>
        <?php endwhile; ?>

        <!-- نموذج إرسال رسالة جديدة -->
        <form action="send_message.php" method="POST" class="chat-form" style="margin-top: 20px;">
          <!-- نحط رقم البلاغ علشان نربط الرسالة به -->
          <input type="hidden" name="report_id" value="<?= $reportId ?>">
          <!-- مكان كتابة الرسالة -->
          <textarea name="message" placeholder="<?= $translations['type_here'][$lang] ?>" required></textarea>
          <!-- زر الإرسال -->
          <button type="submit"><?= $translations['btn_send'][$lang] ?></button>
        </form>
      </div>
    <?php endif; ?>

  </div>
</main>

<footer class="footer">
  <p>منصة تِقن لإدارة البلاغات التقنية</p>
  <p>Copyright © 2025 — All Rights Reserved</p>
  <p>للتواصل: <a href="mailto:gg2ghadi@gmail.com">gg2ghadi@gmail.com</a></p>
</footer>

<script>
  // تفعيل الوضع الليلي إذا كان محفوظ
  if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
  }
</script>
</body>
</html>