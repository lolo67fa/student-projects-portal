<?php
require_once __DIR__ . '/../functions.php';
requireRole(['admin']);
$users = getAllUsers();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['action']) && $_POST['action'] === 'toggle_status'){
        $uid = (int)$_POST['user_id'];
        $status = $_POST['status'] === 'active' ? 'inactive' : 'active';
        updateUserStatus($uid,$status);
        header('Location: manage_users.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>إدارة المستخدمين</title>

    <!-- Font Awesome للأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- ستايل داخل نفس الصفحة -->
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

        table {
            width:100%;
            border-collapse:collapse;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(6px);
            border-radius:12px;
            overflow:hidden;
            box-shadow:0 4px 20px rgba(0,0,0,0.3);
        }
        table thead {
            background: rgba(0,0,0,0.25);
        }
        table thead th {
            padding:12px 16px;
            text-align:right;
            font-weight:600;
        }
        table tbody td {
            padding:12px 16px;
            border-bottom:1px solid rgba(255,255,255,0.1);
        }
        table tbody tr:last-child td {
            border-bottom:none;
        }

        button {
            padding:6px 12px;
            border:none;
            border-radius:8px;
            font-weight:600;
            cursor:pointer;
            transition:0.2s;
        }
        button.active {
            background:#06b6d4;
            color:#042027;
        }
        button.inactive {
            background:#ef4444;
            color:#fff;
        }
        button:hover {
            transform:translateY(-2px);
            box-shadow:0 6px 14px rgba(0,0,0,0.3);
        }

        @media(max-width:720px){
            table thead th, table tbody td{padding:8px 10px;}
        }
    </style>
</head>
<body>

<div class="admin-bg"></div>
<div class="overlay"></div>

<?php include __DIR__ . '/../partials/topbar.php'; ?>

<div class="container">
    <h2><i class="fa-solid fa-users-gear"></i> قائمة المستخدمين</h2>
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد</th>
                <th>الدور</th>
                <th>الحالة</th>
                <th>تحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?= e($u['name']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['role']) ?></td>
                <td><?= ucfirst($u['status']) ?></td>
                <td>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <input type="hidden" name="status" value="<?= $u['status'] ?>">
                        <button type="submit" name="action" value="toggle_status"
                            class="<?= $u['status'] === 'active' ? 'inactive' : 'active' ?>">
                            <?= $u['status'] === 'active' ? 'تعطيل' : 'تفعيل' ?>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
