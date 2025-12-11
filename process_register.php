<?php
require_once "functions.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // اجبار كل المستخدمين على أن يكون دورهم طالب
    $role = 'student';

    // التحقق من وجود البريد مسبقًا
    if(getUserByEmail($email)){
        header('Location: register.php?error=' . urlencode('هذا البريد مسجل بالفعل.'));
        exit;
    }

    $ok = registerUser($name, $email, $password, $role);

    if($ok) 
        header('Location: register.php?msg=' . urlencode('تم التسجيل بنجاح.'));
    else 
        header('Location: register.php?error=' . urlencode('حدث خطأ أثناء التسجيل.'));
}
?>
