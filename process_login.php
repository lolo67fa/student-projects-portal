<?php
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $user = loginUser($email, $password);

    if ($user) {
        // التحقق من حالة الحساب
        if ($user['status'] !== 'active') {
            header('Location: index.php?error=' . urlencode('حسابك غير مفعل. تواصل مع المشرف.'));
            exit;
        }

        // إزالة كلمة المرور من بيانات الجلسة
        unset($user['password']);
        $_SESSION['user'] = $user;

        // تحويل المستخدم حسب نوعه
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($user['role'] === 'student') {
            header('Location: student/dashboard.php');
        } else {
            header('Location: index.php?error=' . urlencode('نوع الحساب غير معروف.'));
        }
        exit;
    } else {
        header('Location: index.php?error=' . urlencode('بيانات دخول غير صحيحة.'));
        exit;
    }
}
