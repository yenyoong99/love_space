<?php
require("config/session.php");
require_once("config/conn.php");

// 查询愿望
$sql = "SELECT sender_name AS sender, message, x, y FROM wishes ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$wishes = $stmt->fetch_all(MYSQLI_ASSOC);

// 返回愿望数据
echo json_encode(['success' => true, 'wishes' => $wishes]);
?>
