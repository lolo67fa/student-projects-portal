<?php
$dsn = "mysql:host=localhost;dbname=student_projects_db;charset=utf8mb4";
$user = "root";  // اسم المستخدم في XAMPP أو PHPMyAdmin
$pass = "";       // كلمة المرور (عادة فارغة في XAMPP)

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
