<?php
require_once "functions.php";

// البريد الذي تريد إعادة تعيين كلمة المرور له
$email = "admin@uni.edu";

// تحقق أن المستخدم موجود
$user = getUserByEmail($email);
if (!$user) {
    die("⚠️ المستخدم غير موجود في قاعدة البيانات.");
}

// كلمة المرور الجديدة
$newPassword = "admin123";

// تشفير كلمة المرور
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

// تحديث كلمة المرور وحالة الحساب
$stmt = $conn->prepare("UPDATE users SET password=?, status='active' WHERE email=?");
$success = $stmt->execute([$hashed, $email]);

if ($success) {
    echo "✅ تم إعادة تعيين كلمة المرور بنجاح.<br>";
    echo "📧 البريد: $email<br>";
    echo "🔑 كلمة المرور الجديدة: $newPassword<br>";
    echo "الحساب الآن مفعل ويمكن تسجيل الدخول.";
} else {
    echo "❌ فشل تحديث كلمة المرور.";
}
?>
