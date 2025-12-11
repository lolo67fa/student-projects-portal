<?php
require_once __DIR__ . '/../functions.php';
requireRole(['student']);
$user = $_SESSION['user'];
$projects = getProjects($user['id']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>لوحة الطالب - بوابة مشاريع الطلاب</title>

<!-- Font Awesome للأيقونات -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{box-sizing:border-box;margin:0;padding:0}
html, body { height:100%; font-family:"Tajawal", Arial, sans-serif; }

/* ----- خلفية ثابتة ----- */
body {
    background: url('/assets/images/bg_student.jpg') no-repeat center center fixed;
    background-size: cover;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding-top:50px;
}

.overlay{
    position:fixed; inset:0;
    background: rgba(15,23,36,0.65);
    z-index:0;
}

/* ----- الحاوية الرئيسية ----- */
.container{
    position:relative; z-index:1;
    max-width:1000px;
    width:90%;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    padding:30px 24px;
    border-radius:16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.6);
    color:#fff;
}

/* عنوان الترحيب */
.container h2{
    font-size:26px;
    margin-bottom:16px;
}

/* زر رفع مشروع جديد */
.btn{
    display:inline-block;
    padding:10px 16px;
    border-radius:10px;
    background:#06b6d4;
    color:#042027;
    font-weight:700;
    text-decoration:none;
    margin-bottom:20px;
    transition:0.2s;
}
.btn:hover{ background:#0ea5a6; transform:translateY(-2px); }

/* جدول المشاريع */
.table{
    width:100%;
    border-collapse: collapse;
    margin-top:16px;
    background: rgba(255,255,255,0.05);
    border-radius:10px;
    overflow:hidden;
}

.table th, .table td{
    padding:12px 16px;
    text-align:right;
}

.table th{
    background: rgba(6,182,212,0.2);
    color:#fff;
    font-weight:700;
}

.table tr:nth-child(even){ background: rgba(255,255,255,0.02); }
.table tr:hover{ background: rgba(6,182,212,0.1); }

.table a{
    color:#06b6d4;
    text-decoration:none;
    font-weight:600;
}

.table a:hover{ text-decoration:underline; }

/* Responsive */
@media(max-width:720px){
    .container{ padding:20px; }
    .table th, .table td{ padding:10px; font-size:14px; }
}
</style>
</head>
<body>
<div class="overlay"></div>

<div class="container">
    <h2>مرحبًا <?= e($user['name']) ?></h2>
    <a class="btn" href="upload_project.php"><i class="fa-solid fa-upload" style="margin-left:6px;"></i> رفع مشروع جديد</a>

    <h3>مشاريعك</h3>
    <?php if(!$projects): ?>
        <p>لم تقم برفع أي مشروع بعد.</p>
    <?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>العنوان</th>
                <th>الحالة</th>
                <th>تاريخ</th>
                <th>عرض</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($projects as $p): ?>
            <tr>
                <td><?= e($p['title']) ?></td>
                <td><?= e($p['status']) ?></td>
                <td><?= e($p['created_at']) ?></td>
                <td><a href="view_project.php?id=<?= $p['id'] ?>"><i class="fa-solid fa-eye"></i> عرض</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
