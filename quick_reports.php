<?php
require_once __DIR__ . '/../functions.php';
requireRole(['admin']);

// البيانات
$users = getAllUsers();
$projects = getProjects();

// احصاء حسب الحالة
$stats = ['active'=>0, 'pending'=>0, 'completed'=>0];
foreach($projects as $p){
    $status = $p['status'];
    if(isset($stats[$status])) $stats[$status]++;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقارير سريعة</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{
            font-family: "Tajawal", Arial, sans-serif;
            background:#0f1724;
            color:#fff;
            direction:rtl;
        }

        .admin-bg {
            position:fixed;
            inset:0;
            z-index:-2;
            background-image: url('/assets/images/bg.jpg');
            background-position:center;
            background-size:cover;
            filter: brightness(0.45) saturate(1.05);
        }
        .overlay {
            position:fixed;
            inset:0;
            z-index:-1;
            background: linear-gradient(180deg, rgba(2,6,23,0.45), rgba(6,18,36,0.65));
        }

        .topbar {
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:14px 22px;
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .topbar .logo { font-weight:800; font-size:18px; display:flex; align-items:center; gap:10px; }
        .topbar .nav { display:flex; gap:14px; align-items:center; }
        .topbar .nav a { color:#dbeafe; text-decoration:none; padding:8px 10px; border-radius:8px; font-weight:600 }
        .topbar .nav a.logout { background: rgba(255,255,255,0.06); }

        .container {
            max-width:1100px;
            margin:40px auto;
            padding:26px;
        }

        h2 {
            font-size:28px;
            margin-bottom:20px;
            color:#e6f0ff;
        }

        .grid {
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap:22px;
            align-items:stretch;
        }

        .card {
            background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));
            border-radius:14px;
            padding:20px;
            box-shadow: 0 8px 30px rgba(2,6,23,0.6);
            border: 1px solid rgba(255,255,255,0.04);
            backdrop-filter: blur(8px);
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            min-height:140px;
        }

        .card .top {
            display:flex;
            align-items:center;
            gap:14px;
        }

        .card-icon {
            width:52px; height:52px; border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            font-size:24px;
            background: linear-gradient(135deg,#06b6d4,#0ea5a6);
            color:#fff;
            box-shadow: 0 6px 18px rgba(14,165,166,0.18);
        }

        .card h3 { font-size:20px; margin-bottom:6px; color:#fff; }
        .card p { font-size:14px; color:#d1e9ff; }

        @media(max-width:720px){
            .card-icon { width:44px;height:44px;font-size:20px; }
        }
    </style>
</head>
<body>

<div class="admin-bg"></div>
<div class="overlay"></div>

<?php include __DIR__ . '/../partials/topbar.php'; ?>

<div class="container">
    <h2><i class="fa-solid fa-chart-simple"></i> تقارير سريعة</h2>

    <div class="grid">
        <div class="card">
            <div class="top">
                <div class="card-icon"><i class="fa-solid fa-users"></i></div>
                <div>
                    <h3>عدد المستخدمين</h3>
                    <p><?= count($users) ?> مستخدم</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="top">
                <div class="card-icon"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <h3>المشاريع النشطة</h3>
                    <p><?= $stats['active'] ?> مشروع</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="top">
                <div class="card-icon"><i class="fa-solid fa-clock"></i></div>
                <div>
                    <h3>قيد المراجعة</h3>
                    <p><?= $stats['pending'] ?> مشروع</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="top">
                <div class="card-icon"><i class="fa-solid fa-check-double"></i></div>
                <div>
                    <h3>المكتملة</h3>
                    <p><?= $stats['completed'] ?> مشروع</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
