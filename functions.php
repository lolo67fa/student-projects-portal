<?php
session_start();
require_once __DIR__ . '/config.php';

// 🔹 حماية النص من الاختراق XSS
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// 🔹 تسجيل مستخدم جديد
function registerUser($name, $email, $password, $role = 'student') {
    global $conn;
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $hashed, $role]);
}

// 🔹 جلب المستخدم بالبريد الإلكتروني
function getUserByEmail($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 🔹 تسجيل الدخول
function loginUser($email, $password) {
    $user = getUserByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

// 🔹 التحقق من الدور (الصلاحيات)
function requireRole($roles = []) {
    if (!isset($_SESSION['user'])) {
        header('Location: /index.php');
        exit;
    }
    if (!in_array($_SESSION['user']['role'], (array)$roles)) {
        die('❌ غير مصرح بالدخول لهذه الصفحة.');
    }
}

// 🔹 رفع الملفات (المشاريع)
function handleFileUpload($file_input_name) {
    $uploads_dir = __DIR__ . '/uploads';
    if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0755, true);

    if (!isset($_FILES[$file_input_name])) 
        return ['error' => 'لم يتم رفع أي ملف.'];

    $file = $_FILES[$file_input_name];
    if ($file['error'] !== UPLOAD_ERR_OK)
        return ['error' => 'خطأ في الرفع. كود: ' . $file['error']];

    $maxBytes = 20 * 1024 * 1024; // 20 MB
    if ($file['size'] > $maxBytes)
        return ['error' => 'الملف كبير جدًا (الحد الأقصى 20MB).'];

    $allowed = ['pdf', 'doc', 'docx', 'zip', 'rar', 'ppt', 'pptx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed))
        return ['error' => 'نوع الملف غير مسموح.'];

    $newName = uniqid('proj_', true) . '.' . $ext;
    $destination = $uploads_dir . '/' . $newName;

    if (!move_uploaded_file($file['tmp_name'], $destination))
        return ['error' => 'فشل في نقل الملف.'];

    return ['path' => 'uploads/' . $newName, 'name' => $file['name']];
}

// 🔹 رفع مشروع جديد
function uploadProject($user_id, $title, $description, $file_path) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO projects (user_id, title, description, file_path) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $title, $description, $file_path]);
}

// 🔹 جلب جميع المشاريع
function getProjects($user_id = null) {
    global $conn;
    if ($user_id) {
        $stmt = $conn->prepare("
            SELECT p.*, u.name AS student_name 
            FROM projects p 
            JOIN users u ON p.user_id = u.id 
            WHERE user_id = ? 
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $conn->query("
            SELECT p.*, u.name AS student_name 
            FROM projects p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC
        ");
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 🔹 جلب مشروع واحد بالتفصيل
function getProjectById($id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT p.*, u.name AS student_name, u.email AS student_email 
        FROM projects p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 🔹 إضافة تعليق من المعلم
function addComment($project_id, $teacher_id, $comment) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comments (project_id, teacher_id, comment) VALUES (?, ?, ?)");
    return $stmt->execute([$project_id, $teacher_id, $comment]);
}

// 🔹 جلب جميع التعليقات على مشروع
function getComments($project_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.*, u.name AS teacher_name 
        FROM comments c 
        JOIN users u ON c.teacher_id = u.id 
        WHERE c.project_id = ? 
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$project_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 🔹 تحديث حالة المشروع
function updateProjectStatus($project_id, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE projects SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $project_id]);
}

// 🔹 حذف مشروع
function deleteProject($project_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    return $stmt->execute([$project_id]);
}

// 🔹 جلب جميع المستخدمين (للأدمن)
function getAllUsers() {
    global $conn;
    $stmt = $conn->query("SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 🔹 تحديث حالة المستخدم
function updateUserStatus($user_id, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $user_id]);
}
?>
