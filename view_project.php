<?php
require_once __DIR__ . '/../functions.php';
requireRole(['student','teacher','admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$project = getProjectById($id);

// تحقق من وجود المشروع
if(!$project) die('المشروع غير موجود.');

// تحقق من أن المشروع موافق عليه
if($project['status'] !== 'approved') {
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>غير مسموح</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('/assets/images/bg_login.jpg') center/cover no-repeat fixed;
    font-family: "Tajawal", sans-serif;
}

.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

.box {
    position: relative;
    z-index: 2;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    width: 380px;
    padding: 30px;
    border-radius: 20px;
    text-align: center;
    color: #fff;
    box-shadow: 0 0 25px rgba(0,0,0,0.5);
}

.box h2 {
    font-size: 22px;
    margin-bottom: 10px;
    font-weight: bold;
}

.spinner {
    margin: 20px auto;
    width: 60px;
    height: 60px;
    border: 6px solid rgba(255,255,255,0.2);
    border-top-color: #38bdf8;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.btn {
    display: inline-block;
    background: #1d4ed8;
    padding: 10px 18px;
    margin-top: 15px;
    color: #fff;
    text-decoration: none;
    border-radius: 10px;
    font-weight: bold;
    transition: 0.2s;
}

.btn:hover {
    background: #2563eb;
}
</style>
</head>
<body>
<div class="overlay"></div>

<div class="box">
    <h2><i class="fa-solid fa-circle-xmark"></i> لا يمكنك عرض المشروع</h2>
    <p>هذا المشروع لم تتم الموافقة عليه بعد من قبل المشرف.</p>

    <div class="spinner"></div>

    <a href="upload_project.php" class="btn">عودة</a>
</div>

</body>
</html>

<?php
exit;
}

// مسار الملف
$projectFile = '../uploads/' . basename($project['file_path']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>عرض مشروع - بوابة مشاريع الطلاب</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
html, body { height:100%; font-family:"Tajawal", Arial, sans-serif; }
body {
    background: url('/assets/images/bg_login.jpg') no-repeat center center fixed;
    background-size: cover;
    display:flex; justify-content:center; align-items:flex-start;
    padding-top:80px;
    color:#fff;
}
.overlay{
    position:fixed; inset:0;
    background: rgba(15,23,36,0.65);
    z-index:0;
}
.topbar{
    position:fixed; top:0; right:0; left:0;
    height:50px;
    display:flex; justify-content:space-between; align-items:center;
    padding:0 22px;
    background: rgba(0,0,0,0.25);
    backdrop-filter: blur(6px);
    z-index:2;
    border-bottom:1px solid rgba(255,255,255,0.1);
}
.topbar .logo{ font-weight:700; font-size:18px; }
.topbar .nav span{ margin-left:15px; }
.topbar .nav a{ color:#dbeafe; text-decoration:none; background: rgba(255,255,255,0.08); padding:6px 12px; border-radius:8px; font-weight:600; }

.cards-container {
    display: flex; flex-wrap: wrap; gap: 20px;
    justify-content: center; width: 100%; max-width: 1200px;
    margin-top: 100px; z-index:1; position: relative;
}
.card {
    background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);
    padding: 20px; border-radius: 16px; width: 350px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.6);
    display: flex; flex-direction: column; gap: 12px;
}
.card h2{ font-size:20px; color:#fff; margin-bottom:12px;text-align:center; }
.card p{ line-height:1.4; }
.card a{ color:#fff; text-decoration:none; }
.status{
    display:inline-block; padding:4px 10px; border-radius:6px;
    font-size:13px; font-weight:600;
}
.status.pending{ background:#facc15; color:#1f2937; }
.status.approved{ background:#22c55e; color:#fff; }
.status.rejected{ background:#ef4444; color:#fff; }

@media(max-width:480px){
    .cards-container{ flex-direction:column; align-items:center; }
    .card{ width:90%; }
}
</style>
</head>
<body>
<div class="overlay"></div>

<div class="topbar">
    <div class="logo">بوابة مشاريع الطلاب</div>
    <p style="color:#fff; font-weight:bold; font-size:20px;">
        <a href="../home.php" style="color:#fff; text-decoration:none; font-weight:bold;">صفحة الرئيسية</a>
    </p>
    <div class="nav">
        <span><?= e($project['student_name']) ?> (<?= e($project['role'] ?? 'طالب') ?>)</span>
        <a class="logout" href="../logout.php">تسجيل خروج</a>
    </div>
</div>

<div class="cards-container">
    <div class="card">
        <h2><?= e($project['title']) ?></h2>
        <p><strong>الملف:</strong><br>
        <?php if(file_exists($projectFile)): ?>
            <a href="<?= $projectFile ?>" target="_blank"><i class="fa-solid fa-file"></i> عرض / تحميل</a>
        <?php else: ?>
            <span>الملف غير موجود</span>
        <?php endif; ?>
        </p>

        <p><strong>الوصف:</strong><br><?= nl2br(e($project['description'])) ?></p>

        <p><strong>الحالة:</strong> 
            <span class="status <?= e($project['status']) ?>">
                <?= ucfirst(e($project['status'])) ?>
            </span>
        </p>
    </div>
</div>
</body>
</html>
