<?php
// نبدأ الجلسة
session_start();

// نستدعي ملف الترجمة
require_once "lang.php";

// نتصل بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// نجيب البلاغات اللي حالتها مو 'تم الحل'
$sql = "SELECT reports.*, users.department 
        FROM reports 
        JOIN users ON reports.user_id = users.id 
        WHERE status != 'done'
        ORDER BY reports.created_at DESC";
$result = mysqli_query($conn, $sql);
$reportCount = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<!--  اللي يغير الاتجاه بعد الترجمه-->
<html lang="<?php echo $_SESSION['lang']; ?>" dir="rtl">
  <head>
  <meta charset="UTF-8">
  <title><?php echo $translations['home_title'][$_SESSION['lang']]; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

  <script>
    // نحفظ عدد البلاغات السابق عشان نقارن إذا جا بلاغ جديد
    let previousReportCount = null;

    // دالة تجيب عدد البلاغات من السيرفر
    function checkForNewReports() {
      fetch('fetch_reports.php')
        .then(response => response.json())
        .then(data => {
          let currentReportCount = data.reportCount;

          // أول مرة نحفظ الرقم فقط
          if (previousReportCount === null) {
            previousReportCount = currentReportCount;
            return;
          }

          // إذا جا بلاغ جديد، نشغل صوت ونحدث الصفحة بعد 10 ثواني
          if (currentReportCount > previousReportCount) {
            let alertSound = new Audio('alert.mp3');
            alertSound.volume = 1.0;
            alertSound.play();
            setTimeout(() => location.reload(), 10000);
          }

          // نحدّث العدد الحالي
          previousReportCount = currentReportCount;
        })
        .catch(error => console.error("خطأ أثناء التحقق من البلاغات:", error));
    }

    // نكرر التحقق كل 10 ثواني
    setInterval(checkForNewReports, 10000);
  </script>
</head>

<body>

<header>
  <div class="branding">
    <div style="max-width: 120px;">
      <img src="logo.png" alt="شعار" style="width: 100%; height:auto;">
    </div>
    <div class="text">
      <strong><?php echo $translations['system_name'][$_SESSION['lang']]; ?></strong>
      <span class="subtext"><?php echo $translations['digital_health_board'][$_SESSION['lang']]; ?></span>
    </div>
  </div>

  <nav>
    <a href="Home.php"><?php echo $translations['nav_home'][$_SESSION['lang']]; ?></a>
    <a href="Archive.php"><?php echo $translations['nav_archive'][$_SESSION['lang']]; ?></a>
    <a href="Reports.php"><?php echo $translations['nav_reports'][$_SESSION['lang']]; ?></a>
  </nav>

  <div class="icons">
    <span class="icon"><a href="settings.php">⚙️</a></span>
  </div>

  <div class="lang-toggle-container" style="left: 20px; right: auto;">
    <form method="get" onchange="this.submit()">
      <select name="lang">
        <option value="ar" <?php if ($_SESSION['lang'] === 'ar') echo 'selected'; ?>>العربية</option>
        <option value="en" <?php if ($_SESSION['lang'] === 'en') echo 'selected'; ?>>English</option>
      </select>
    </form>
  </div>
</header>

<main>
  <h2><?php echo $translations['active_reports'][$_SESSION['lang']]; ?></h2>

  <div class="grid">
    <?php
    if ($reportCount > 0) {
      // نجيب الرسائل اللي ما شافها المستخدم
      $myId = $_SESSION['user_id'];
      $unseenQuery = "SELECT report_id FROM messages WHERE NOT FIND_IN_SET('$myId', seen_by)";
      $unseenResult = mysqli_query($conn, $unseenQuery);
      $reportsWithUnseenMessages = [];
      while ($rowUnseen = mysqli_fetch_assoc($unseenResult)) {
        $reportsWithUnseenMessages[] = $rowUnseen['report_id'];
      }

      // نعرض كل بلاغ داخل كرت
      while ($row = mysqli_fetch_assoc($result)) {
        $status = $row['status'];
        $badge = ($row['urgency'] == 'high') ? '<span class="badge">' . $translations['urgent'][$_SESSION['lang']] . '</span>' : '';
        $statusLabel = ($status == 'new') ? $translations['status_new'][$_SESSION['lang']] : $translations['status_processing'][$_SESSION['lang']];
        $statusClass = ($status == 'new') ? 'new' : 'processing';
        $hasUnseenMessage = in_array($row['id'], $reportsWithUnseenMessages);

        echo '
        <div class="card">
          ' . $badge . '
          <h3>#' . $row['id'] . '</h3>
          <p>' . $translations['department'][$_SESSION['lang']] . ': ' . htmlspecialchars($row['department']) . '</p>
          <p>' . $translations['date'][$_SESSION['lang']] . ': ' . date("d M Y", strtotime($row['created_at'])) . '</p>
          <p>' . htmlspecialchars($row['title']) . '</p>
          <span class="status ' . $statusClass . '">' . $statusLabel . '</span><br>';

        // لو فيه رسالة جديدة غير مقروءة
        if ($hasUnseenMessage) {
          echo '<div style="color:red; font-weight:bold;">📩 ' . $translations['you_have_message'][$_SESSION['lang']] . '</div>';
        }

        echo '<a class="details-btn" href="Details.php?id=' . $row['id'] . '">' . $translations['view_details'][$_SESSION['lang']] . '</a>
        </div>';
      }
    } else {
      // لو ما فيه ولا بلاغ
      echo '<p style="text-align:center;">' . $translations['no_reports'][$_SESSION['lang']] . '</p>';
    }
    ?>
  </div>
</main>

<footer class="footer">
  <p>منصة تِقن لإدارة البلاغات التقنية</p>
  <p>Copyright © 2025 — All Rights Reserved</p>
  <p>للتواصل: <a href="mailto:gg2ghadi@gmail.com">gg2ghadi@gmail.com</a></p>
</footer>

<script>
  if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
  }
</script>
</body>
</html>