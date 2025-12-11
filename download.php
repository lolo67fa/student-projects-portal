<?php
$file = $_GET['file'] ?? '';
$path = __DIR__ . '/uploads/' . basename($file);

if(file_exists($path)){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
} else {
    die('الملف غير موجود.');
}
