<?php
require_once __DIR__ . '/../functions.php';
requireRole(['admin']);

if(!isset($_GET['id'])) {
    header("Location: manage_comments.php");
    exit;
}

$id = (int)$_GET['id'];

// حذف التعليق
$stmt = $conn->prepare("DELETE FROM comments WHERE id=?");
$stmt->execute([$id]);

// إعادة التوجيه للصفحة الرئيسية للتعليقات
header("Location: manage_comments.php");
exit;
