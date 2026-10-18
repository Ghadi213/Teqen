<?php
session_start();
require_once "lang.php"; // ملف الترجمة

require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات.");
}

// فلترة البيانات
$selected_year = $_GET['year'] ?? '';
$selected_month = $_GET['month'] ?? '';
$selected_department = $_GET['department'] ?? '';

// الأقسام
$departments = [];
$dep_query = mysqli_query($conn, "SELECT DISTINCT department FROM users WHERE department IS NOT NULL AND department != ''");
while ($row = mysqli_fetch_assoc($dep_query)) {
    $departments[] = $row['department'];
}

// استعلام البلاغات
$where = "WHERE status IN ('done', 'processing')";

if ($selected_year) $where .= " AND YEAR(reports.created_at) = '$selected_year'";
if ($selected_month) $where .= " AND MONTH(reports.created_at) = '$selected_month'";
if ($selected_department) $where .= " AND users.department = '$selected_department'";

$reports_query = "
    SELECT reports.*, users.department 
    FROM reports 
    JOIN users ON reports.user_id = users.id 
    $where
";
$reports_result = mysqli_query($conn, $reports_query);

// بيانات الرسوم البيانية
$chart_data = [];
$issue_counts = [];

while ($row = mysqli_fetch_assoc($reports_result)) {
    $dept = $row['department'];
    $chart_data[$dept] = ($chart_data[$dept] ?? 0) + 1;

    $issue = $row['title'];
    $issue_counts[$issue] = ($issue_counts[$issue] ?? 0) + 1;
}

// نرجّع المؤشر لأول سطر
mysqli_data_seek($reports_result, 0);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title><?php echo $translations['reports_page_title'][$_SESSION['lang']]; ?></title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<main class="reports-page">

  <!-- الفلاتر -->
  <section class="filters">
    <form method="get">
      <!-- السنة -->
      <select name="year">
        <option value=""><?php echo $translations['filter_by_year'][$_SESSION['lang']]; ?></option>
        <?php for ($y = 2025; $y <= 2030; $y++): ?>
          <option value="<?= $y ?>" <?= ($selected_year == $y ? 'selected' : '') ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>

      <!-- الشهر -->
      <select name="month">
        <option value=""><?php echo $translations['filter_by_month'][$_SESSION['lang']]; ?></option>
        <?php foreach ($translations['months'][$_SESSION['lang']] as $i => $monthName): ?>
          <option value="<?= $i+1 ?>" <?= ($selected_month == $i+1 ? 'selected' : '') ?>><?= $monthName ?></option>
        <?php endforeach; ?>
      </select>

      <!-- القسم -->
      <select name="department">
        <option value=""><?php echo $translations['filter_by_department'][$_SESSION['lang']]; ?></option>
        <?php foreach ($departments as $dep): ?>
          <option value="<?= $dep ?>" <?= ($selected_department == $dep ? 'selected' : '') ?>><?= $dep ?></option>
        <?php endforeach; ?>
      </select>

      <!-- زر الفلترة -->
      <button class="filter-btn" type="submit"><?php echo $translations['filter_button'][$_SESSION['lang']]; ?></button>
    </form>
  </section>

  <!-- إحصائيات البلاغات -->
  <section class="summary-cards">
    <?php
    mysqli_data_seek($reports_result, 0);
    $total = $done = $processing = $urgent = 0;
    while ($row = mysqli_fetch_assoc($reports_result)) {
        $total++;
        if ($row['status'] === 'done') $done++;
        if ($row['status'] === 'processing') $processing++;
        if ($row['urgency'] === 'high') $urgent++;
    }
    ?>
    <div class="card blue"><h3><?php echo $translations['total_reports'][$_SESSION['lang']]; ?></h3><p><?= $total ?></p></div>
    <div class="card green"><h3><?php echo $translations['resolved_reports'][$_SESSION['lang']]; ?></h3><p><?= $done ?></p></div>
    <div class="card yellow"><h3><?php echo $translations['in_progress_reports'][$_SESSION['lang']]; ?></h3><p><?= $processing ?></p></div>
    <div class="card red"><h3><?php echo $translations['urgent_reports'][$_SESSION['lang']]; ?></h3><p><?= $urgent ?></p></div>
  </section>

  <!-- الرسوم البيانية -->
  <section class="charts" style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center;">
    <div style="width: 400px;">
      <canvas id="deptChart"></canvas>
    </div>
    <div style="width: 400px;">
      <canvas id="issueChart"></canvas>
    </div>
  </section>

  <!-- الفترة -->
  <?php if ($selected_year && $selected_month): ?>
    <p style="text-align:center; font-size: 18px; margin-bottom: 20px;" class="print-period">
      <?= $translations['report_period'][$_SESSION['lang']] ?>: <?= "1/$selected_month/$selected_year" ?> - <?= date("t/$selected_month/$selected_year") ?>
    </p>
  <?php endif; ?>

  <!-- زر PDF -->
  <div style="text-align: center; margin-top: 20px;">
    <button class="no-print" onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background-color: #8b9fb4ff; color: white; border: none; border-radius: 5px;">
      <?php echo $translations['download_pdf'][$_SESSION['lang']]; ?>
    </button>
  </div>

</main>


<footer class="footer">
  <p>منصة تِقن لإدارة البلاغات التقنية</p>
  <p>Copyright © 2025 — All Rights Reserved</p>
  <p>للتواصل: <a href="mailto:gg2ghadi@gmail.com">gg2ghadi@gmail.com</a></p>
</footer>


<script>
new Chart(document.getElementById('deptChart'), {
  type: 'pie',
  data: {
    labels: <?= json_encode(array_keys($chart_data), JSON_UNESCAPED_UNICODE) ?>,
    datasets: [{
      data: <?= json_encode(array_values($chart_data)) ?>,
      backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      title: { display: true, text: '<?php echo $translations['chart_by_departments'][$_SESSION['lang']]; ?>' }
    }
  }
});

new Chart(document.getElementById('issueChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_keys($issue_counts), JSON_UNESCAPED_UNICODE) ?>,
    datasets: [{
      label: '<?php echo $translations['chart_by_issues'][$_SESSION['lang']]; ?>',
      data: <?= json_encode(array_values($issue_counts)) ?>,
backgroundColor: [
  '#007bff',
  '#28a745',
  '#ffc107',
  '#dc3545',
  '#17a2b8',
  '#6f42c1',
  '#fd7e14',
  '#20c997',
  '#6610f2',
  '#e83e8c'
]    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: { display: true, text: '<?php echo $translations['chart_by_issues'][$_SESSION['lang']]; ?>' }
    },
    scales: {
      y: { beginAtZero: true }
    }
  }
});
</script>

</body>
</html>