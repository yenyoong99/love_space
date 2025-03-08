<?php
header('Content-Type: application/json');
require("config/session.php");
require_once("config/conn.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => '无效的请求方法']);
    exit;
}

// Get the JSON data from the request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if all required fields are present
if (!isset($data['date']) || !isset($data['amount']) || !isset($data['description']) || !isset($data['category'])) {
    echo json_encode(['success' => false, 'message' => '缺少必要的字段']);
    exit;
}

// Sanitize and validate input
$date = trim($data['date']);
$amount = trim($data['amount']);
$description = trim($data['description']);
$category = trim($data['category']);

// Validate date format (YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
    echo json_encode(['success' => false, 'message' => '无效的日期格式']);
    exit;
}

// Validate amount
if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => '无效的金额']);
    exit;
}

// Validate category
$valid_categories = ['餐饮', '购物', '交通', '娱乐', '其他'];
if (!in_array($category, $valid_categories)) {
    echo json_encode(['success' => false, 'message' => '无效的类别']);
    exit;
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO expenses (date, amount, description, category) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sdss", $date, $amount, $description, $category);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '支出已成功添加']);
} else {
    echo json_encode(['success' => false, 'message' => '添加支出失败: ' . $conn->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

