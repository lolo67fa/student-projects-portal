<?php
require_once __DIR__ . '/../functions.php';
requireRole(['student']);
$user = $_SESSION['user'];
$msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $res = handleFileUpload('project_file');
    if(isset($res['error'])) $msg = $res['error'];
    else {
        $file_path = $res['path'];
        $ok = uploadProject($user['id'],$title,$description,$file_path);
        if($ok) header('Location: dashboard.php?msg=' . urlencode('تم رفع المشروع بنجاح.'));
        else $msg = 'خطأ في حفظ بيانات المشروع.';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>رفع مشروع - بوابة مشاريع الطلاب</title>

<!-- Font Awesome للأيقونات -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ----- Reset بسيط ----- */
*{box-sizing:border-box;margin:0;padding:0}
html, body { height:100%; font-family:"Tajawal", Arial, sans-serif; }

/* ----- خلفية صورة ----- */
body {
    background: url('/assets/images/bg_login.jpg') no-repeat center center fixed;
    background-size: cover;
    display:flex; justify-content:center; align-items:flex-start;
    padding-top:80px;
    color:#fff;
}

/* ----- Overlay داكن ----- */
.overlay{
    position:fixed;
    inset:0;
    background: rgba(15,23,36,0.65);
    z-index:0;
}

/* ----- Topbar ----- */
.topbar{
    position:fixed;
    top:0; right:0; left:0;
    height:50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 22px;
    background: rgba(0,0,0,0.25);
    backdrop-filter: blur(6px);
    z-index:2;
    border-bottom:1px solid rgba(255,255,255,0.1);
}
.topbar .logo{ font-weight:700; font-size:18px; }
.topbar .nav a{
    color:#dbeafe;
    text-decoration:none;
    padding:6px 10px;
    border-radius:8px;
    font-weight:600;
    margin-left:12px;
}
.topbar .nav a.logout{ background: rgba(255,255,255,0.08); }

/* ----- حاوية رفع المشروع ----- */
.container{
    position:relative;
    z-index:1;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    padding:36px 32px;
    border-radius:16px;
    width:100%;
    max-width:500px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.6);
}

/* ----- العنوان ----- */
.container h2{
    font-size:24px;
    margin-bottom:24px;
    text-align:center;
    font-weight:700;
}

/* ----- رسائل الخطأ ----- */
.error{
    background: rgba(239,68,68,0.2);
    padding:10px 12px;
    margin-bottom:16px;
    border-radius:8px;
    color:#fff;
    font-weight:600;
}
.success{
    background: rgba(16,185,129,0.2);
    padding:10px 12px;
    margin-bottom:16px;
    border-radius:8px;
    color:#fff;
    font-weight:600;
}

/* ----- حقول الإدخال مع أيقونات ----- */
.input-wrapper{
    position:relative;
    margin-bottom:18px;
}
.input-wrapper input,
.input-wrapper textarea,
.input-wrapper input[type="file"]{
    width:100%;
    padding:12px 38px 12px 14px;
    border-radius:10px;
    border:none;
    background: rgba(255,255,255,0.08);
    color:#fff;
    font-size:15px;
}
.input-wrapper textarea{ resize:vertical; }
.input-wrapper label{
    display:block;
    margin-bottom:6px;
    font-size:14px;
    color:#cfe8ff;
}
.input-wrapper i{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    color:#06b6d4;
    font-size:18px;
    pointer-events:none;
}

/* ----- زر الرفع ----- */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background: #06b6d4;
    font-size:16px;
    font-weight:700;
    color:#042027;
    cursor:pointer;
    transition:0.2s;
}
button:hover{
    background:#0ea5a6;
    transform:translateY(-2px);
}

/* ----- responsive ----- */
@media(max-width:480px){
    .container{ padding:28px 20px; }
    .container h2{ font-size:20px; }
}
</style>
</head>
<body>
<div class="overlay"></div>

<!-- Topbar -->
<div class="topbar">
    <div class="logo">بوابة مشاريع الطلاب</div>
    <div class="nav">
        <span><?= e($user['name']) ?> (<?= e($user['role']) ?>)</span>
        <a class="logout" href="../logout.php">تسجيل خروج</a>
    </div>
</div>

<!-- الحاوية الرئيسية -->
<div class="container">
<h2>رفع مشروع جديد</h2>

<?php if($msg): ?><div class="error"><?= e($msg) ?></div><?php endif; ?>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="input-wrapper">
        <input type="text" name="title" placeholder="عنوان المشروع" required>
        <i class="fa-solid fa-heading"></i>
    </div>

    <div class="input-wrapper">
        <textarea name="description" placeholder="وصف المشروع" rows="6"></textarea>
        <i class="fa-solid fa-align-left"></i>
    </div>

    <div class="input-wrapper">
        <label>اختر ملف المشروع (pdf, doc, zip, ppt)</label>
        <input type="file" name="project_file" required>
        <i class="fa-solid fa-file"></i>
    </div>

    <button type="submit">رفع</button>
</form>
</div>
<script>
// الحد الأقصى للملف بالبايت (20MB)
const MAX_FILE_SIZE = 100 * 1024 * 1024; // 100MB

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const fileInput = form.querySelector('input[type="file"]');

    form.addEventListener('submit', (e) => {
        if(fileInput.files.length > 0){
            const file = fileInput.files[0];
            if(file.size > MAX_FILE_SIZE){
                e.preventDefault();
                alert('حجم الملف كبير جدًا! الحد الأقصى هو 100MB.');
            }
        }
    });
});

</script>

</body>
</html>
