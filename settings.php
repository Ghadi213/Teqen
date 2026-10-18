<?php
session_start();
require_once "lang.php"; // ملف الترجمة

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if ($conn->connect_error) die("فشل الاتصال: " . $conn->connect_error);

// التحقق من أن المستخدم من موظفي الصحة الرقمية
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, role, department, password FROM users WHERE id = $user_id AND role = 'digital'";
$result = $conn->query($query);
if ($result->num_rows == 0) {
  die("غير مصرح لك بالدخول إلى هذه الصفحة.");
}
$data = $result->fetch_assoc();

// تعديل البيانات
if (isset($_POST['update_profile'])) {
  $new_name = $_POST['username'];
  $new_email = $_POST['email'];
  $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
  $stmt->bind_param("ssi", $new_name, $new_email, $user_id);
  $stmt->execute();
  $_SESSION['message'] = $translations['success_update'][$lang];
  header("Location: usersettings.php");
  exit();
}

// تغيير كلمة المرور
if (isset($_POST['change_password'])) {
  $current = $_POST['current_password'];
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  if (!password_verify($current, $data['password'])) {
    $_SESSION['error'] = $translations['error_password_incorrect'][$lang];
  } elseif ($new !== $confirm) {
    $_SESSION['error'] = $translations['error_password_mismatch'][$lang];
  } else {
    $new_hash = password_hash($new, PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password = '$new_hash' WHERE id = $user_id");
    $_SESSION['message'] = $translations['password_changed'][$lang];
    header("Location: settings.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title><?= $translations['account_settings_title'][$lang]; ?> - تِقن</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
 
<header>
  <div class="branding">
    <div style="max-width: 120px;">
      <img src="logo.png" alt="شعار" style="width: 100%; height:auto;">
    </div>
    <div class="text">
      <strong><?= $translations['system_name'][$lang]; ?></strong>
      <span class="subtext"><?= $translations['digital_health_board'][$lang]; ?></span>
    </div>
  </div>

  <nav>
    <a href="Home.php"><?= $translations['nav_home'][$lang]; ?></a>
    <a href="Archive.php"><?= $translations['nav_archive'][$lang]; ?></a>
    <a href="Reports.php"><?= $translations['nav_reports'][$lang]; ?></a>
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

<div class="container">
  <h2>⚙️ <?= $translations['account_settings_title'][$lang]; ?></h2>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
  <?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <!-- قسم حسابي -->
  <div class="section">
    <button class="accordion">👤 <?= $translations['section_account'][$lang]; ?></button>
    <div class="panel">
      <form method="post">
        <p>👤 <?= $translations['label_username'][$lang]; ?>
          <input type="text" name="username" value="<?= $data['username'] ?>" disabled>
        </p>
        <p>📧 <?= $translations['label_email'][$lang]; ?>
          <input type="email" name="email" value="<?= $data['email'] ?>" disabled>
        </p>
        <p>🏥 <?= $translations['label_department'][$lang]; ?>
          <input type="text" value="<?= $data['department'] ?>" disabled>
        </p>
        <button type="button" class="btn" onclick="enableEdit()">✏️ <?= $translations['edit_button'][$lang]; ?></button>
        <button type="submit" name="update_profile" class="btn save-btn" style="display:none;">💾 <?= $translations['save_button'][$lang]; ?></button>
      </form>
    </div>
  </div>

  <!-- تغيير كلمة المرور -->
  <div class="section">
    <button class="accordion">🔑 <?= $translations['section_change_password'][$lang]; ?></button>
    <div class="panel">
      <form method="post">
        <p><input type="password" name="current_password" placeholder="🔒 <?= $translations['current_password'][$lang]; ?>" required></p>
        <p><input type="password" name="new_password" placeholder="🔑 <?= $translations['new_password'][$lang]; ?>" required></p>
        <p><input type="password" name="confirm_password" placeholder="✅ <?= $translations['confirm_password'][$lang]; ?>" required></p>
        <button type="submit" name="change_password" class="btn"><?= $translations['confirm_change_button'][$lang]; ?></button>
      </form>
    </div>
  </div>

  <!-- الوضع الليلي -->
  <div class="section">
    <button class="accordion">🌙 <?= $translations['section_dark_mode'][$lang]; ?></button>
    <div class="panel">
      <label class="switch">
        <input type="checkbox" id="darkModeToggle">
        <span class="slider round"></span>
      </label>
      <span><?= $translations['toggle_dark_mode'][$lang]; ?></span>
    </div>
  </div>

  <!-- حول النظام -->
  <div class="section">
    <button class="accordion">ℹ️ <?= $translations['about_system'][$lang]; ?></button>
    <div class="panel">
      <p><?= $translations['system_about_text'][$lang]; ?></p>    
    </div>
  </div>

  <!-- تسجيل الخروج -->
  <div class="section">
    <button class="accordion logout"><i class="fas fa-sign-out-alt"></i> <?= $translations['logout'][$lang]; ?></button>
    <div class="panel">
      <a href="logout.php" class="btn logout-btn">
        <i class="fas fa-sign-out-alt"></i> <?= $translations['logout_now'][$lang]; ?>
      </a>
    </div>
  </div>
</div>

<script>
// أكورديون
document.querySelectorAll(".accordion").forEach(button => {
  button.addEventListener("click", () => {
    button.classList.toggle("active");
    let panel = button.nextElementSibling;
    panel.style.maxHeight = panel.style.maxHeight ? null : panel.scrollHeight + "px";
  });
});

// تفعيل تعديل البيانات
function enableEdit() {
  const input = document.querySelector('input[name="username"]');
  input.disabled = false;
  document.querySelector(".save-btn").style.display = "inline-block";
}

// الوضع الليلي 
const darkModeToggle = document.getElementById("darkModeToggle");
if (localStorage.getItem("darkMode") === "enabled") {
  document.body.classList.add("dark-mode");
  if (darkModeToggle) darkModeToggle.checked = true;
}
if (darkModeToggle) {
  darkModeToggle.addEventListener("change", () => {
    if (darkModeToggle.checked) {
      document.body.classList.add("dark-mode");
      localStorage.setItem("darkMode", "enabled");
    } else {
      document.body.classList.remove("dark-mode");
      localStorage.setItem("darkMode", "disabled");
    }
  });
}
</script>

<footer class="footer">
  <p>منصة تِقن لإدارة البلاغات التقنية</p>
  <p>Copyright © 2025 — All Rights Reserved</p>
  <p>للتواصل: <a href="mailto:gg2ghadi@gmail.com">gg2ghadi@gmail.com</a></p>
</footer>
</body>
</html>