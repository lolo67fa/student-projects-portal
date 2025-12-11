<?php
require_once __DIR__ . '/../functions.php';
requireRole(['admin']);

// استعلام جديد بدون teacher_id
$stmt = $conn->prepare("
    SELECT 
        c.id,
        c.name,
        c.email,
        c.comment,
        c.created_at,
        p.title AS project_title
    FROM comments c
    JOIN projects p ON c.project_id = p.id
    ORDER BY c.created_at DESC
");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>إدارة التعليقات</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {font-family:"Tajawal",Arial,sans-serif;background:#0f1724;color:#fff;padding:20px;}
table {width:100%;border-collapse:collapse;margin-top:20px;}
th,td {padding:10px;border:1px solid #333;}
th {background:#06b6d4;color:#042027;}
td {background:#1e293b;}
a.btn {display:inline-block;padding:6px 12px;background:#06b6d4;color:#042027;border-radius:6px;text-decoration:none;}
a.btn:hover {background:#0ea5a6;}
</style>
</head>
<body>

<h2>إدارة التعليقات</h2>
<p>عرض جميع التعليقات المرسلة من المستخدمين للمشاريع.</p>

<table>
    <thead>
        <tr>
            <th>المشروع</th>
            <th>اسم المعلق</th>
            <th>البريد الإلكتروني</th>
            <th>التعليق</th>
            <th>التاريخ</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($comments as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['project_title']) ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
            <td><?= $c['created_at'] ?></td>
            <td>
                <a class="btn" href="delete_comment.php?id=<?= $c['id'] ?>" onclick="return confirm('هل أنت متأكد من حذف هذا التعليق؟');">
                    حذف
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
