<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'lang.php';

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// تسجيل جديد
if (isset($_POST['register'])) {
    $username   = $_POST['username'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $original_password = $_POST['password'];

    $role = ($original_password === DIGITAL_SIGNUP_CODE) ? 'digital' : 'normal';
    $department = $_POST['department'];

    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $error = " اسم المستخدم أو البريد الإلكتروني مسجل مسبقًا.";
    } else {
        $sql = "INSERT INTO users (username, email, password, department, role)
                VALUES ('$username', '$email', '$password', '$department', '$role')";
        if (mysqli_query($conn, $sql)) {
            $success = "تم إنشاء الحساب بنجاح. سجّل دخولك.";
        } else {
            if (mysqli_errno($conn) == 1062) {
                $error = "هذا البريد الإلكتروني مسجّل مسبقًا. جرّبي بريدًا آخر.";
            } else {
                $error = " فشل في إنشاء الحساب.";
            }
        }
    }
}

// تسجيل الدخول
if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']    = $row['id'];
            $_SESSION['username']   = $row['username'];
            $_SESSION['department'] = $row['department'];
            $_SESSION['role']       = $row['role'];

            header("Location: " . ($row['role'] === 'digital' ? 'Home.php' : 'new_report_form.php'));
            exit;
        } else {
            $error = " كلمة المرور غير صحيحة.";
        }
    } else {
        $error = " البريد الإلكتروني غير موجود.";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang == 'ar' ? 'rtl' : 'ltr' ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
  <title><?= $lang == 'ar' ? 'نظام تِقن - تسجيل الدخول' : 'Teqen System - Login' ?></title>
</head>
<body class="login-page">

<div class="main">
 <div class="lang-toggle-container">
  <form method="get" onchange="this.submit()">
    <select name="lang">
      <option value="ar" <?php if ($_SESSION['lang'] === 'ar') echo 'selected'; ?>>العربية</option>
      <option value="en" <?php if ($_SESSION['lang'] === 'en') echo 'selected'; ?>>English</option>
    </select>
  </form>
</div>
  <input type="checkbox" id="chk" aria-hidden="true" />

  <!-- تسجيل جديد -->
  <div class="signup">
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <label for="chk" aria-hidden="true"><?= $translations['register_title'][$lang] ?></label>
      <input type="text" name="username" placeholder="<?= $translations['username'][$lang] ?>" required />
      <input type="email" name="email" placeholder="<?= $translations['email'][$lang] ?>" required />
      <input type="password" name="password" placeholder="<?= $translations['password'][$lang] ?>" required />
      <input type="text" name="department" placeholder="<?= $translations['department'][$lang] ?>" required />
      <button type="submit" name="register"><?= $translations['create_account'][$lang] ?></button>
    </form>
  </div>

  <!-- تسجيل الدخول -->
  <div class="login">
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <label for="chk" aria-hidden="true"><?= $translations['login_title'][$lang] ?></label>
      <input type="email" name="email" placeholder="<?= $translations['email'][$lang] ?>" required />
      <input type="password" name="password" placeholder="<?= $translations['password'][$lang] ?>" required />
      <button type="submit" name="login"><?= $translations['login_btn'][$lang] ?></button>

      <a href="forgot_password.php" class="forgot-password-link"><?= $translations['forgot_password'][$lang] ?></a>
    </form>
  </div>
</div>

<!-- رسائل الخطأ أو النجاح -->
<div style="color:red; text-align:center; margin-top:10px;">
  <?php if (isset($error)) echo $error; ?>
</div>
<div style="color:green; text-align:center; margin-top:10px;">
  <?php if (isset($success)) echo $success; ?>
</div>

</body>
</html>