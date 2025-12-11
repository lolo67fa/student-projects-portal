<?php
session_start();
require_once 'config.php'; // الاتصال بقاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = (int)$_POST['project_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $comment = trim($_POST['comment']);

    // التحقق من البيانات
    if($name && $email && $comment){
        $stmt = $conn->prepare("INSERT INTO comments (project_id, name, email, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$project_id, $name, $email, $comment]);
        
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        echo "جميع الحقول مطلوبة.";
    }
} else {
    die("طريقة غير مسموحة.");
}
?>
