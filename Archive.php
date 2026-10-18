<?php
session_start();
require_once "lang.php";

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
  die("فشل الاتصال: " . mysqli_connect_error());
}

// نجيب كل الأقسام من جدول المستخدمين (بدون تكرار)
$departments_result = mysqli_query($conn, "SELECT DISTINCT department FROM users ORDER BY department");

// نبدأ نص الاستعلام الأساسي
$query = "SELECT reports.id, reports.title, reports.created_at, users.department 
          FROM reports 
          JOIN users ON reports.user_id = users.id 
          WHERE reports.status = 'done'";

// فلترة بالبحث
if (!empty($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $query .= " AND (reports.id LIKE '%$search%' OR reports.title LIKE '%$search%')";
}

// فلترة بالقسم
if (!empty($_GET['department'])) {
  $department = mysqli_real_escape_string($conn, $_GET['department']);
  $query .= " AND users.department = '$department'";
}

// فلترة بالتاريخ
if (!empty($_GET['date'])) {
  $date = mysqli_real_escape_string($conn, $_GET['date']);
  $query .= " AND DATE(reports.created_at) = '$date'";
}

// نرسل الاستعلام ونجيب النتائج
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<!-- نخلي الاتجاه دايمًا من اليمين -->
<html lang="<?php echo $_SESSION['lang']; ?>" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title><?php echo $translations['nav_archive'][$_SESSION['lang']]; ?> - Teqen</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="branding">
    <div style="max-width: 120px;">
      <img src="logo.png" alt="شعار" style="width: 100%; height: auto;">
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
    <span class="icon"><a href="settings.php">⚙️</a> </span>
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
  <h2><?php echo $translations['nav_archive'][$_SESSION['lang']]; ?></h2>

  <form method="GET" class="filters">
    <input type="text" name="search" placeholder="<?php echo $translations['search_placeholder'][$_SESSION['lang']]; ?>" class="search-input" value="<?= $_GET['search'] ?? '' ?>" />

    <select name="department" class="filter-select">
      <option value=""><?php echo $translations['all_departments'][$_SESSION['lang']]; ?></option>
      <?php while ($dep = mysqli_fetch_assoc($departments_result)): 
        $depName = $dep['department'];
      ?>
        <option value="<?= $depName ?>" <?= (($_GET['department'] ?? '') == $depName) ? 'selected' : '' ?>>
          <?= $depName ?>
        </option>
      <?php endwhile; ?>
    </select>

    <input type="date" name="date" class="filter-date" value="<?= $_GET['date'] ?? '' ?>" />
    <button type="submit"><?php echo $translations['btn_filter'][$_SESSION['lang']]; ?></button>
  </form>

  <table class="report-table">
    <thead>
      <tr>
        <th><?php echo $translations['report_id'][$_SESSION['lang']]; ?></th>
        <th><?php echo $translations['department'][$_SESSION['lang']]; ?></th>
        <th><?php echo $translations['date'][$_SESSION['lang']]; ?></th>
        <th><?php echo $translations['title'][$_SESSION['lang']]; ?></th>
        <th><?php echo $translations['btn_details'][$_SESSION['lang']]; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td>#<?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['department']) ?></td>
          <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td>
            <a href="Details.php?id=<?= $row['id'] ?>&source=archive" class="details-btn">
              <?php echo $translations['btn_details'][$_SESSION['lang']]; ?>
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
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