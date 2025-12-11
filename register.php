<?php require_once "functions.php"; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>تسجيل جديد - بوابة مشاريع الطلاب</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
html, body { height:100%; font-family:"Tajawal", Arial, sans-serif; }
body {
    background: url('assets/images/bg_login.jpg') no-repeat center center fixed;
    background-size: cover;
    display:flex; justify-content:center; align-items:center;
}
.overlay{position:fixed; inset:0; background: rgba(15,23,36,0.65); z-index:0;}
.login-container{
    position:relative; z-index:1;
    background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);
    padding:40px 36px; border-radius:16px; width:100%; max-width:400px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.6); color:#fff; text-align:center;
}
.login-container h2{ font-size:24px; margin-bottom:28px; font-weight:700; display:flex; justify-content:center; gap:10px; }
.input-wrapper{ position:relative; margin-bottom:16px; }
.input-wrapper input, .input-wrapper select{
    width:100%; padding:12px 40px 12px 40px; border-radius:10px; border:none;
    background: rgba(255,255,255,0.08); color:#fff; font-size:15px;
}
.input-wrapper input::placeholder, .input-wrapper select option{ color:#d1d5db; }
.input-wrapper .icon-right{ position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#06b6d4; font-size:18px; }
.login-container form button{ width:100%; padding:12px; border:none; border-radius:10px; background:#06b6d4; font-size:16px; font-weight:700; color:#042027; cursor:pointer; transition:0.2s; }
.login-container form button:hover{ background:#0ea5a6; transform:translateY(-2px);}
.login-container p{ margin-top:18px; font-size:14px; color:#cfe8ff; }
.login-container p a{ color:#06b6d4; text-decoration:none; font-weight:600; }
.login-container .error{ background: rgba(239,68,68,0.2); padding:10px 12px; margin-bottom:16px; border-radius:8px; color:#fff; font-weight:600; }
.login-container .success{ background: rgba(16,185,129,0.2); padding:10px 12px; margin-bottom:16px; border-radius:8px; color:#fff; font-weight:600; }
</style>
</head>
<body>
<div class="overlay"></div>

<div class="login-container">
    <h2> تسجيل جديد</h2>

    <?php if(isset($_GET['msg'])): ?>
        <div class="success"><?= e($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
        <div class="error"><?= e($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="process_register.php" method="POST">
        <div class="input-wrapper">
            <input type="text" name="name" placeholder="الاسم الكامل" required>
            <i class="fa-solid fa-user icon-right"></i>
        </div>
        <div class="input-wrapper">
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <i class="fa-solid fa-envelope icon-right"></i>
        </div>
        <div class="input-wrapper">
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <i class="fa-solid fa-lock icon-right"></i>
        </div>

        <!-- Dropdown للدور، خيار واحد فقط: طالب -->
        <div class="input-wrapper">
            <select name="role" required>
                <option value="student" selected>طالب</option>
            </select>
            <i class="fa-solid fa-user icon-right"></i>
        </div>

        <button type="submit">تسجيل</button>
    </form>

    <p>لديك حساب؟ <a href="index.php">تسجيل الدخول</a></p>
</div>
</body>
</html>
