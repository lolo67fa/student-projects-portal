<?php
if(!isset($_SESSION)) session_start();
require_once __DIR__ . '/../functions.php'; // تأكدي من هذا السطر
$user = $_SESSION['user'] ?? null;
?>
<div class="topbar">
    <div class="logo">بوابة مشاريع الطلاب</div>
    <div class="nav">
           <a href="../home.php">صفحة الرئيسية</a>
        <?php if($user): ?>
            <span><?= e($user['name']) ?> (<?= e($user['role']) ?>)</span>
            <a href="../logout.php">تسجيل خروج</a>
        <?php else: ?>
            <a href="/index.php">دخول</a>
            <a href="/register.php">تسجيل</a>
        <?php endif; ?>
    </div>
</div>
