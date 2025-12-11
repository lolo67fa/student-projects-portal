<?php
require_once "functions.php";

// تحقق من أن الحساب لم يُنشأ مسبقًا
$email = "admin@uni.edu";
$existing = getUserByEmail($email);

if ($existing) {
    die("⚠️ حساب الأدمن موجود بالفعل.");
}

// إنشاء حساب الأدمن
$name = "Admin";
$password = "admin123"; // كلمة المرور التي تريدها
$role = "admin";
$status = "active";

// تسجيل الحساب
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
$success = $stmt->execute([$name, $email, $hashed, $role, $status]);

if ($success) {
    echo "✅ حساب الأدمن تم إنشاؤه بنجاح.<br>";
    echo "📧 البريد: $email<br>";
    echo "🔑 كلمة المرور: $password";
} else {
    echo "❌ فشل إنشاء حساب الأدمن.";
}
?>
