<?php
header('Content-Type: application/json');
require("config/session.php");
require_once("config/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['id'])) {
        $id = $data['id'];
        
        // 准备 SQL 语句
        $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ?");
        
        // 绑定参数并执行
        $stmt->bind_param("i", $id);
        $deleteResult = $stmt->execute();

        if ($deleteResult) {
            echo json_encode(['success' => true, 'message' => '支出记录已成功删除']);
        } else {
            echo json_encode(['success' => false, 'message' => '删除支出记录失败: ' . $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => '未提供有效的支出ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效的请求方法']);
}

$conn->close();
?>