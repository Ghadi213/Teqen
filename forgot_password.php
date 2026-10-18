<?php
session_start();
require_once 'lang.php';
date_default_timezone_set('UTC');

// الاتصال بقاعدة البيانات
require_once __DIR__ . '/db.php';
$conn = db_connect();
if (!$conn) {
    die("فشل الاتصال: " . mysqli_connect_error());
}

// استدعاء مكتبة PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // نشيك إذا الإيميل موجود بقاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // نولد توكن عشوائي ونحدد وقت انتهاءه
        $token = bin2hex(random_bytes(32));
        $expiry = gmdate("Y-m-d H:i:s", time() + 1800); // 30 دقيقة

        // نحفظ التوكن ووقته في قاعدة البيانات
        $update = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        // هذا هو رابط إعادة التعيين
        $resetLink = APP_URL . "/reset_password.php?token=" . $token;

        // نص الإيميل
        $subject = "رابط إعادة تعيين كلمة المرور";
        $message = "مرحبًا،\n\nلقد طلبتِ إعادة تعيين كلمة المرور.\nاضغطي على الرابط التالي:\n$resetLink\n\nهذا الرابط صالح لمدة 30 دقيقة فقط.\n\nفريق تقن";

        // نبدأ إعداد الإيميل باستخدام PHPMailer
        $mail = new PHPMailer(true);

        try {
            // إعدادات SMTP الخاصة بـ Gmail
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;

            $mail->Username = SMTP_USER;

            $mail->Password = SMTP_PASS;

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom(SMTP_USER, 'Teqen Team');

            $mail->addAddress($email); // المستلم هو المستخدم اللي نسي كلمة المرور
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->CharSet = 'UTF-8';

            $mail->send();
            $success = $translations['forgot_success'][$lang];
        } catch (Exception $e) {
            $error = "خطأ في الإرسال: {$mail->ErrorInfo}";
        }
    } else {
        $error = $translations['forgot_email_not_found'][$lang];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $translations['forgot_title'][$lang] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="forgot-password-page">
  <div class="forgot-password-container">
    <h2><?= $translations['forgot_title'][$lang] ?></h2>
    <p><?= $translations['forgot_instruction'][$lang] ?></p>

    <form action="" method="POST">
      <input type="email" name="email" placeholder="<?= $translations['forgot_email_placeholder'][$lang] ?>" required>
      <button type="submit"><?= $translations['forgot_send_button'][$lang] ?></button>
    </form>

    <?php if (isset($success)) echo "<p class='form-success'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='form-error'>$error</p>"; ?>
  </div>
</body>
</html>