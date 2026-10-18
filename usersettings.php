<?php
session_start();
require_once "lang.php"; // ملف الترجمة

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if ($conn->connect_error) die("فشل الاتصال: " . $conn->connect_error);

// التحقق من أن المستخدم من الموظفين العاديين
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, role, department, password FROM users WHERE id = $user_id AND role = 'normal'";
$result = $conn->query($query);
if ($result->num_rows == 0) {
  die("غير مصرح لك بالدخول إلى هذه الصفحة.");
}
$data = $result->fetch_assoc();

// تعديل البيانات
if (isset($_POST['update_profile'])) {
  $new_name = $_POST['username'];
  $new_department = $_POST['department'];
  $stmt = $conn->prepare("UPDATE users SET username = ?, department = ? WHERE id = ?");
  $stmt->bind_param("ssi", $new_name, $new_department, $user_id);
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
    header("Location: usersettings.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title><?= $translations['account_settings_title'][$lang]; ?></title>
  <link rel="stylesheet" href="Estyle.css" />
</head>
<body>

<!-- القائمة الجانبية -->
<aside class="sidebar">

  <div class="logo"><img src="logo.png" alt="شعار المستشفى" /></div>
  <nav class="nav">
    <a href="new_report_form.php"><?= $translations['new_report'][$lang]; ?></a>
    <a href="userreports.php"><?= $translations['my_reports'][$lang]; ?></a>
    <a href="usersettings.php" class="active"><?= $translations['user_settings'][$lang]; ?></a>
    <a href="logout.php"><?= $translations['logout'][$lang]; ?></a>
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
          <input type="email" value="<?= $data['email'] ?>" disabled>
        </p>
        <p>🏥 <?= $translations['label_department'][$lang]; ?>
          <input type="text" name="department" value="<?= $data['department'] ?>" disabled>
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

  <div class="section">
  <button class="accordion">❓ <?= $translations['faq_section_title'][$lang]; ?></button>
  <div class="panel faq-list">
    
    <p><strong><?= $translations['faq_q1'][$lang]; ?></strong><br>
    <?= $translations['faq_a1'][$lang]; ?></p>
    <hr>

    <p><strong><?= $translations['faq_q2'][$lang]; ?></strong><br>
    <?= $translations['faq_a2'][$lang]; ?></p>
    <hr>

    <p><strong><?= $translations['faq_q3'][$lang]; ?></strong><br>
    <?= $translations['faq_a3'][$lang]; ?></p>
    <hr>

    <p><strong><?= $translations['faq_q4'][$lang]; ?></strong><br>
    <?= $translations['faq_a4'][$lang]; ?></p>
    <hr>

    <p><strong><?= $translations['faq_q5'][$lang]; ?></strong><br>
    <?= $translations['faq_a5'][$lang]; ?></p>
    <hr>

    <p><strong><?= $translations['faq_q6'][$lang]; ?></strong><br>
    <?= $translations['faq_a6'][$lang]; ?></p>
    <hr>

    <p><strong><?= $translations['faq_q7'][$lang]; ?></strong><br>
    <?= $translations['faq_a7'][$lang]; ?></p>
    <hr>

    <p><strong><?= $translations['faq_q8'][$lang]; ?></strong><br>
    <?= $translations['faq_a8'][$lang]; ?></p>

  </div>
</div>

</div>

<script>
// سكربت الأكورديون
document.querySelectorAll(".accordion").forEach(button => {
  button.addEventListener("click", () => {
    button.classList.toggle("active");
    let panel = button.nextElementSibling;
    panel.style.maxHeight = panel.style.maxHeight ? null : panel.scrollHeight + "px";
  });
});

// تفعيل تعديل البيانات
function enableEdit() {
  const inputs = document.querySelectorAll('input[name="username"], input[name="department"]');
  inputs.forEach(i => i.disabled = false);
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

</body>
</html>