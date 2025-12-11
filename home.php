<?php
session_start();
require_once 'config.php'; // ملف الاتصال بقاعدة البيانات ($conn)

// دالة حماية النصوص
function e($string){
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// جلب مشاريع الطلاب الموافق عليها فقط
$stmt = $conn->prepare("
    SELECT p.*, u.name AS student_name
    FROM projects p
    JOIN users u ON p.user_id = u.id
    WHERE u.role='student' AND p.status='approved'
    ORDER BY p.created_at DESC
");
$stmt->execute();
$student_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>بوابة مشاريع الطلاب</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ---------- Reset ---------- */
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:"Tajawal", Arial, sans-serif; background:#f1f5f9; color:#1f2937; scroll-behavior: smooth;}
a{text-decoration:none; color:inherit;}
h2,h3{margin-bottom:16px;}

/* ---------- Header ---------- */
header{
  position:fixed;
  top:0; left:0; right:0;
  background: #0f1724;
  color:#fff;
  padding:10px 20px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  z-index:1000;
}
header .logo{font-weight:700; font-size:22px; color: #fff;}
header nav{display:flex; gap:15px;}
header nav a{
  padding:6px 12px; border-radius:6px; background: rgba(255,255,255,0.08);
  transition: 0.3s;
}
header nav a:hover{ background:#06b6d4; color:#fff; }

.menu-toggle{
  display:none;
  font-size:24px;
  cursor:pointer;
}

/* Responsive Menu */
@media(max-width:768px){
  header nav{
    display:flex;
    flex-direction: column;
    position: absolute;
    top:-300px;
    right: 0;
    width: 200px;
    background: #0f1724;
    padding: 10px;
    border-radius: 0 0 8px 8px;
    gap: 10px;
    transition: top 0.4s ease;
  }
  header nav.show{ top: 60px; }
  .menu-toggle{display:block;}
}

/* ---------- Slider ---------- */
.slider {
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  position: relative;
  margin-top:60px;
}

.slider img {
  flex: 0 0 100%;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s;
}

.slider img:hover {
  transform: scale(1.05);
}

/* ---------- Section Cards ---------- */
.section{
  padding:80px 20px;
  max-width:1200px;
  margin:auto;
  text-align:center;
}
.cards-container{
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap:20px;
  justify-items:center;
}
.card{
  background: #fff;
  padding:20px;
  border-radius:16px;
  width:100%;
  box-shadow: 0 8px 30px rgba(0,0,0,0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover{
  transform: translateY(-5px);
  box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}
.card h3{margin-bottom:10px; font-size:20px; color:#2563eb;}
.card p{margin-bottom:6px; line-height:1.5; font-size:14px;}
.card a{
  color:#06b6d4;
  font-weight:600;
  transition: 0.3s;
}
.card a:hover{ text-decoration:underline; }
button.show-comment-btn{
  margin-top:10px;
  padding:8px 15px;
  background:#06b6d4;
  color:#fff;
  border:none;
  border-radius:6px;
  cursor:pointer;
  transition:0.3s;
}
button.show-comment-btn:hover{ background:#0ea5a6; }
.add-comment input, .add-comment textarea{
  margin-bottom:8px; padding:8px; width:100%; border-radius:6px; border:1px solid #ccc;
}
.add-comment button{
  padding:8px 15px; background:#06b6d4; color:#fff; border:none; border-radius:6px; cursor:pointer;
}
.add-comment button:hover{ background:#0ea5a6; }

/* ---------- About ---------- */
.about{
  background: #06b6d4;
  color:#fff;
  padding:60px 20px;
  border-radius:16px;
  max-width:900px;
  margin:80px auto;
  text-align:center;
  box-shadow:0 8px 30px rgba(0,0,0,0.15);
}
.about h2{font-size:28px; margin-bottom:20px; text-shadow:1px 1px 3px rgba(0,0,0,0.3);}
.about p{font-size:16px; margin-bottom:12px; line-height:1.7;}

/* ---------- Contact ---------- */
.contact{
  background: #f1f5f9;
  padding:40px 20px;
  border-radius:16px;
  max-width:900px;
  margin:80px auto;
  text-align:center;
  box-shadow:0 8px 30px rgba(0,0,0,0.05);
}
.contact h2{margin-bottom:20px; color:#2563eb;}

/* ---------- Responsive ---------- */
@media(max-width:768px){
  .slider{height:40vh;}
  .about{padding:40px 15px;}
  .cards-container{grid-template-columns:1fr;}
}
</style>
</head>
<body>
<header>
  <div class="logo">بوابة مشاريع الطلاب</div>
  <nav>
    <a href="#students">مشاريع الطلاب</a>
    <a href="#about">عن الموقع</a>
    <a href="#contact">تواصل معنا</a>
    <a href="logout.php">تسجيل خروج</a>
  </nav>
  <i class="fa fa-bars menu-toggle"></i>
</header>

<!-- Slider -->
<div class="slider" id="slider">
  <img src="assets/images/icons/Website_Editor_Graphic_1562657799.jpg" alt="Slide 1">
</div>

<!-- مشاريع الطلاب -->
<section id="students" class="section">
  <h2>مشاريع الطلاب</h2>
  <div class="cards-container">
    <?php foreach($student_projects as $p): ?>
    <div class="card">
      <h3><?= e($p['title']) ?></h3>
      <p><strong>الطالب:</strong> <?= e($p['student_name']) ?></p>
      <p><strong>الوصف:</strong> <?= nl2br(e($p['description'])) ?></p>
      
      <p><strong>الملف:</strong><br>
        <?php 
        // تصحيح المسار: لا تضيف uploads/ مرتين
        $projectFile = isset($p['file_path']) && !empty($p['file_path']) ? $p['file_path'] : null;

        if($projectFile && file_exists($projectFile)): ?>
            <a href="<?= $projectFile ?>" target="_blank"><i class="fa-solid fa-file"></i> عرض / تحميل</a>
        <?php else: ?>
            <span>الملف غير موجود</span>
        <?php endif; ?>
      </p>

      <button class="show-comment-btn">أضف تعليق</button>
      <form action="add_comment.php" method="POST" class="add-comment" style="display:none;">
          <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
          <input type="text" name="name" placeholder="اسمك" required>
          <input type="email" name="email" placeholder="البريد الإلكتروني" required>
          <textarea name="comment" rows="3" placeholder="أضف تعليقك..." required></textarea>
          <button type="submit">إرسال</button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- About -->
<section id="about" class="about">
  <h2>عن الموقع</h2>
  <p>مرحباً بك في بوابة مشاريع الطلاب! هذا الموقع يتيح عرض المشاريع التعليمية للطلاب بطريقة منظمة، مع إمكانية إضافة التعليقات لأي شخص.</p>
  <p>يمكنك الاطلاع على ملفات المشاريع وترك ملاحظاتك بسهولة وسلاسة. نهدف إلى تعزيز التفاعل بين الطلاب والمعلمين.</p>
  <p>استمتع بتجربة تصفح سلسة وجميلة مع تصنيف المشاريع بشكل واضح ومرتب.</p>
</section>

<!-- Contact -->
<section id="contact" class="contact">
  <h2>تواصل معنا</h2>
  <p>البريد الإلكتروني : lolo67fas@gmail.com</p>
  <p>الهاتف: 055678118</p>
  <p>العنوان: مدينة الرياض، السعودية</p>
</section>

<script>
const toggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('header nav');
toggle.addEventListener('click', ()=> nav.classList.toggle('show'));

const slider = document.getElementById('slider');
const slides = slider.querySelectorAll('img');
let currentIndex = 0;

function nextSlide() {
  const nextIndex = (currentIndex + 1) % slides.length;
  slides[currentIndex].style.transform = 'translateX(-100%)';
  slides[nextIndex].style.transform = 'translateX(0)';
  currentIndex = nextIndex;
}
slides.forEach((img, i) => {
  img.style.position = 'absolute';
  img.style.top = '0';
  img.style.left = '0';
  img.style.transform = i === 0 ? 'translateX(0)' : 'translateX(100%)';
});
setInterval(nextSlide, 3000);

document.querySelectorAll('.show-comment-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const form = btn.nextElementSibling; 
        if(form.style.display === 'none' || form.style.display === ''){
            form.style.display = 'block';
            btn.textContent = 'إخفاء التعليق';
        } else {
            form.style.display = 'none';
            btn.textContent = 'أضف تعليق';
        }
    });
});
</script>
</body>
</html>
