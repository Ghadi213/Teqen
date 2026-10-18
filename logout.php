<?php
// نبدأ الجلسة
session_start();

// نحذف كل المتغيرات من الجلسة
session_unset();

// نحذف الجلسة بالكامل
session_destroy();

// نرجع المستخدم لصفحة تسجيل الدخول
header("Location: index.php");
exit;
?>