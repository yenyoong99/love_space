<?php
require("config/session.php");

$imageName = isset($_GET['image']) ? basename($_GET['image']) : null;

$scriptPath = __DIR__;
$imagePath = "$scriptPath/uploads/" . $imageName;

// 确保文件在指定目录内
if (!file_exists($imagePath) || strpos(realpath($imagePath), realpath("$scriptPath/uploads/")) !== 0) {
    http_response_code(404);
    exit('Image not found.');
}

$mimeType = mime_content_type($imagePath);
header('Content-Type: ' . $mimeType);
readfile($imagePath);
?>