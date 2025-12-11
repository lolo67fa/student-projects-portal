<?php
require_once "functions.php";

// إذا المستخدم مسجل دخول — نعيد توجيهه مباشرة
if(isset($_SESSION['user'])){
    if($_SESSION['user']['role'] === 'admin'){
        header("Location: /admin/dashboard.php");
        exit;
    }
    elseif($_SESSION['user']['role'] === 'student'){
        header("Location: student/dashboard.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تسجيل الدخول</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
html, body{height:100%;font-family:"Tajawal",Arial,sans-serif}
body{background:#0f1724;display:flex;justify-content:center;align-items:center;position:relative}
.overlay{position:fixed;inset:0;background: rgba(15,23,36,0.65);z-index:0}
.login-container{position:relative; z-index:1; background: rgba(255,255,255,0.06);backdrop-filter: blur(12px);padding:40px 36px;border-radius:16px;width:100%;max-width:400px;box-shadow:0 8px 30px rgba(0,0,0,0.6);text-align:center;color:#fff;animation: fadeIn 0.9s ease;}
@keyframes fadeIn {from {opacity:0; transform:translateY(25px);} to {opacity:1; transform:translateY(0);}}
.login-container h2{font-size:26px;margin-bottom:28px;font-weight:700;}
.input-wrapper{position:relative;margin-bottom:16px;}
.input-wrapper input{width:100%;padding:12px 40px;border-radius:10px;border:none;background: rgba(255,255,255,0.08);color:#fff;font-size:15px;}
.input-wrapper .icon-right{position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#06b6d4;font-size:18px;}
.input-wrapper .icon-toggle{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#06b6d4;font-size:18px;cursor:pointer;}
.login-container button{width:100%;padding:12px;border:none;border-radius:10px;background:#06b6d4;font-size:16px;font-weight:700;color:#042027;cursor:pointer;transition:0.3s;}
.login-container button:hover{background:#0ea5a6;transform:translateY(-3px);}
.error{background: rgba(239,68,68,0.25);padding:10px;border-radius:10px;margin-bottom:15px;color:#fff;font-weight:bold;}
@media(max-width:480px){.login-container{padding:28px 20px;}.login-container h2{font-size:22px;}}
</style>
</head>
<body>
<div class="overlay"></div>
<div class="login-container">
<h2><i class="fa-solid fa-right-to-bracket"></i> تسجيل الدخول</h2>
<?php if(isset($_GET['error'])): ?>
<p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>
<form action="process_login.php" method="POST">
<div class="input-wrapper">
<i class="fa-solid fa-envelope icon-right"></i>
<input type="email" name="email" placeholder="البريد الإلكتروني" required>
</div>
<div class="input-wrapper">
<i class="fa-solid fa-lock icon-right"></i>
<i class="fa-solid fa-eye icon-toggle" onclick="togglePass()"></i>
<input type="password" id="pass" name="password" placeholder="كلمة المرور" required>
</div>
<button type="submit">دخول</button>
</form>
<br>
<p>لا تمتلك حساب؟  <a href="register.php" style="color:#06b6d4;font-weight:700;">انشاء حساب</a></p>
</div>
<script>
function togglePass(){
var input = document.getElementById("pass");
input.type = (input.type === "password") ? "text" : "password";
}
</script>
</body>
</html>
