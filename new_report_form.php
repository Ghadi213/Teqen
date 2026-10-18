<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// نسحب بيانات المستخدم من الجلسة
$username = $_SESSION['username'];
$department = $_SESSION['department'];
$user_id = $_SESSION['user_id'];

// ملف الترجمة
include 'lang.php';
$lang = $translations;
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $lang['new_report'][$_SESSION['lang']]; ?> - تِقن</title>
  <link rel="stylesheet" href="Estyle.css" />
</head>
<body>
  <!-- سلايدر بار -->
  <aside class="sidebar">
    <div class="logo">
      <img src="logo.png" alt="شعار المستشفى">
    </div>
    <nav class="nav">
      <a href="new_report_form.php" class="active"><?php echo $lang['new_report'][$_SESSION['lang']]; ?></a>
      <a href="userreports.php"><?php echo $lang['my_reports'][$_SESSION['lang']]; ?></a>
      <a href="usersettings.php"><?php echo $lang['user_settings'][$_SESSION['lang']]; ?></a>
      <a href="logout.php"><?php echo $lang['logout'][$_SESSION['lang']]; ?></a>
    </nav>
    <div class="faq-link">
      <?php echo $lang['more_info_faq'][$_SESSION['lang']]; ?> <a href="usersettings.php#faq-section"><strong class="highlight-link"><?php echo $lang['here'][$_SESSION['lang']]; ?></strong></a> <?php echo $lang['faq_note'][$_SESSION['lang']]; ?>
    </div>
  </aside>

  <div class="lang-toggle-container" >
    <form method="get" onchange="this.submit()">
      <select name="lang">
        <option value="ar" <?php if ($_SESSION['lang'] === 'ar') echo 'selected'; ?>>العربية</option>
        <option value="en" <?php if ($_SESSION['lang'] === 'en') echo 'selected'; ?>>English</option>
      </select>
    </form>
  </div>


  <main class="content">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <div id="success-message" style="background-color: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 5px; text-align:center;">
        <?php echo $lang['report_success'][$_SESSION['lang']]; ?>
      </div>
    <?php endif; ?>

    <h2><?php echo $lang['welcome_user'][$_SESSION['lang']]; ?> <span id="username"><?php echo $username; ?></span> 👋</h2>
    <p class="subtext"><?php echo $lang['report_intro'][$_SESSION['lang']]; ?></p>

    <!--  يرسل الفورم ل sbmit_report.php -->
    <form action="submit_report.php" method="POST" enctype="multipart/form-data">
      <!-- هنا بيانات اليوزر مخفية لكنها ترسل -->
      <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
      <input type="hidden" name="username" value="<?php echo $username; ?>">
      <input type="hidden" name="department" value="<?php echo $department; ?>">

      <p><strong><?php echo $lang['department_label'][$_SESSION['lang']]; ?></strong> <?php echo $department; ?></p>

      <label for="title"><?php echo $lang['report_title_label'][$_SESSION['lang']]; ?></label>
      <input type="text" id="title" name="title" placeholder="<?php echo $lang['title_placeholder'][$_SESSION['lang']]; ?>" required>

      <label for="description"><?php echo $lang['description_label'][$_SESSION['lang']]; ?></label>
      <textarea id="description" name="description" rows="4" placeholder="<?php echo $lang['description_placeholder'][$_SESSION['lang']]; ?>" required></textarea>

      <label for="urgency"><?php echo $lang['urgency_label'][$_SESSION['lang']]; ?></label>
      <select id="urgency" name="urgency" required>
        <option value=""><?php echo $lang['choose_urgency'][$_SESSION['lang']]; ?></option>
        <option value="low"><?php echo $lang['urgency_low'][$_SESSION['lang']]; ?></option>
        <option value="medium"><?php echo $lang['urgency_medium'][$_SESSION['lang']]; ?></option>
        <option value="high"><?php echo $lang['urgency_high'][$_SESSION['lang']]; ?></option>
      </select>

      <label for="attachment"><?php echo $lang['attach_image'][$_SESSION['lang']]; ?></label>
      <input type="file" id="attachment" name="attachment" accept="image/*" required>

      <button type="submit"><?php echo $lang['submit_report'][$_SESSION['lang']]; ?></button>
    </form>
  </main>

<script>
  // نختار العنصر بالتحديد ونسوي له إخفاء بعد 5 ثواني
  const message = document.getElementById("success-message");
  if (message) {
    setTimeout(() => {
      message.style.display = "none";
    }, 5000); // 5000 ملي ثانية = 5 ثواني
  }
</script>

<script>
  // أول ما تفتح الصفحة نشيك هل تم تفعيل الوضع الليلي
  if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
  }
</script>
</body>
</html>