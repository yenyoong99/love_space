<?php
require("config/session.php");
require_once("config/conn.php");

// 获取 JSON 数据
$input = json_decode(file_get_contents('php://input'), true);
$senderName = $input['sender'] ?? null;
$message = $input['message'] ?? null;

if (!$senderName || !$message) {
    echo json_encode(['success' => false, 'message' => '数据不完整！']);
    exit;
}

// 随机生成位置
$x = rand(10, 90) + mt_rand() / mt_getrandmax(); // 10% - 90%
$y = rand(10, 70) + mt_rand() / mt_getrandmax(); // 10% - 70%

$sql = "INSERT INTO wishes (sender_name, message, x, y) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssdd", $senderName, $message, $x, $y); 
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'wish' => [
                'sender' => $senderName,
                'message' => $message,
                'x' => $x,
                'y' => $y
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '愿望保存失败！']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => '数据库查询准备失败！']);
}
?>
