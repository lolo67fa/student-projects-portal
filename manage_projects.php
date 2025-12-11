<?php
require_once __DIR__ . '/../functions.php';
requireRole(['admin']);

// الاتصال بقاعدة البيانات
$host = "localhost";
$dbname = "student_projects_db";
$username = "root";
$password = "";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// حذف مشروع
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: manage_projects.php');
    exit;
}

// تحديث حالة المشروع
if(isset($_POST['update_status'])){
    $project_id = (int)$_POST['project_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE projects SET status = ? WHERE id = ?");
    $stmt->execute([$status, $project_id]);
    header('Location: manage_projects.php');
    exit;
}

// جلب جميع المشاريع
$stmt = $pdo->query("
    SELECT p.*, u.name AS student_name
    FROM projects p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>إدارة المشاريع</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body{font-family: "Tajawal", Arial, sans-serif; background:#0f1724; color:#fff; direction:rtl;}
        .admin-bg {position:fixed; inset:0; z-index:-2; background-image: url('/assets/images/bg.jpg'); background-position:center; background-size:cover; filter: brightness(0.45) saturate(1.05);}
        .overlay {position:fixed; inset:0; z-index:-1; background: linear-gradient(180deg, rgba(2,6,23,0.45), rgba(6,18,36,0.65));}
        .topbar {display:flex; justify-content:space-between; align-items:center; padding:14px 22px; background: rgba(0,0,0,0.25); backdrop-filter: blur(6px); border-bottom: 1px solid rgba(255,255,255,0.04);}
        .topbar .logo { font-weight:800; font-size:18px; display:flex; align-items:center; gap:10px; }
        .topbar .nav { display:flex; gap:14px; align-items:center; }
        .topbar .nav a { color:#dbeafe; text-decoration:none; padding:8px 10px; border-radius:8px; font-weight:600 }
        .topbar .nav a.logout { background: rgba(255,255,255,0.06); }
        .container { max-width:1100px; margin:40px auto; padding:26px; }
        h2 { font-size:28px; margin-bottom:20px; color:#e6f0ff; }
        table { width:100%; border-collapse:collapse; background: rgba(255,255,255,0.05); backdrop-filter: blur(6px); border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.3);}
        table thead { background: rgba(0,0,0,0.25); }
        table thead th { padding:12px 16px; text-align:right; font-weight:600; }
        table tbody td { padding:12px 16px; border-bottom:1px solid rgba(255,255,255,0.1); }
        table tbody tr:last-child td { border-bottom:none; }
        .btn { padding:6px 12px; border:none; border-radius:8px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:0.2s; }
        .btn.delete { background:#ef4444; color:#fff; }
        .btn.view { background:#06b6d4; color:#042027;margin: 10px;}
        .btn:hover { transform:translateY(-2px); box-shadow:0 6px 14px rgba(0,0,0,0.3); }
        select.status-select { padding:4px 6px; border-radius:6px; border:none; background:#2563eb; color:#fff; font-weight:600; cursor:pointer; }
        @media(max-width:720px){ table thead th, table tbody td{padding:8px 10px;} }
    </style>
</head>
<body>
<div class="admin-bg"></div>
<div class="overlay"></div>

<?php include __DIR__ . '/../partials/topbar.php'; ?>

<div class="container">
<h2><i class="fa-solid fa-file-lines"></i> إدارة المشاريع</h2>
<table>
    <thead>
        <tr>
            <th>العنوان</th>
            <th>الطالب</th>
            <th>الحالة</th>
            <th>تاريخ</th>
            <th>إدارة</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($projects as $p): ?>
        <tr>
            <td><?= e($p['title']) ?></td>
            <td><?= e($p['student_name']) ?></td>
            <td>
                <form method="POST" style="display:flex; gap:6px; align-items:center;">
                    <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
                    <select name="status">
                        <option value="pending" <?= $p['status']=='pending'?'selected':'' ?>>قيد المراجعة</option>
                        <option value="approved" <?= $p['status']=='approved'?'selected':'' ?>>مقبول</option>
                        <option value="rejected" <?= $p['status']=='rejected'?'selected':'' ?>>مرفوض</option>
                    </select>
                    <button type="submit" name="update_status" class="btn update"><i class="fa-solid fa-check"></i> تعديل</button>
                </form>
            </td>
            <td><?= e($p['created_at']) ?></td>
            <td>
                <a href="manage_projects.php?delete=<?= $p['id'] ?>" onclick="return confirm('هل تريد الحذف؟')" class="btn delete"><i class="fa-solid fa-trash"></i> حذف</a>
               
                <a href="../student/view_project.php?id=<?= $p['id'] ?>" class="btn view" ><i class="fa-solid fa-eye"></i> عرض</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</body>
</html>
