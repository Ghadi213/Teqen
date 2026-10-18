<?php
require_once __DIR__ . '/db.php';
$conn = db_connect();

if (!$conn) {
    echo json_encode(["error" => "فشل الاتصال"]);
    exit;
}

// نجيب عدد البلاغات اللي مو "تم الحل"
$sql = "SELECT COUNT(*) AS reportCount FROM reports WHERE status != 'done'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// نرجع العدد بصيغة JSON
echo json_encode(["reportCount" => $row['reportCount']]);
?>