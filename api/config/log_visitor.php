<?php
// require("config/session.php");
require_once("config/conn.php");

function get_client_ip() {
    // Cloudflare's original IP header
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    // Check for forwarded IPs
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // HTTP_X_FORWARDED_FOR can contain a list of IPs, pick the first one
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }

    // Fallback to REMOTE_ADDR
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
}
// 获取访问者信息
$ip_address = get_client_ip();
$url = $_GET['url'] ?? $_SERVER['REQUEST_URI'] ?? 'Unknown'; // 通过 GET 参数传入 URL
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$visit_date = date('Y-m-d H:i:s');
$referer = $_SERVER['HTTP_REFERER'] ?? 'Unknown';
$method = $_SERVER['REQUEST_METHOD'] ?? 'Unknown';

// 可选：记录额外数据
$additional_data = json_encode([
    'headers' => getallheaders(),
    'custom_data' => 'example_value' // 根据需要动态扩展
]);

// 准备 SQL 插入语句
$sql = "INSERT INTO visitor_logs (ip_address, url, user_agent, visit_date, referer, method, additional_data) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// 使用 MySQLi 的预处理语句
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]));
}

// 绑定参数
$stmt->bind_param('sssssss', $ip_address, $url, $user_agent, $visit_date, $referer, $method, $additional_data);
$stmt->execute();

// 执行语句并检查是否成功
// if ($stmt->execute()) {
//     echo json_encode(['status' => 'success', 'message' => 'Visitor logged successfully.']);
// } else {
//     http_response_code(500);
//     echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
// }