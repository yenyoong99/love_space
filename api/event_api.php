<?php
// 设置响应头
header("Content-Type: application/json");

require("config/session.php");
require_once("config/conn.php");

// GET 请求：获取事件
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM events ORDER BY event_date ASC";
    $result = $conn->query($query);

    if ($result) {
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $row['event_date'] = date("Y-m-d", strtotime($row['event_date']));
            $events[] = $row;
        }
        http_response_code(200);
        echo json_encode(["events" => $events], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "获取事件时出错：" . $conn->error]);
    }
    $conn->close();
    exit();
}

// POST 请求：添加事件
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['event_date']) || empty($data['title'])) {
        http_response_code(400);
        echo json_encode(["message" => "事件日期和标题是必需的！"]);
        exit();
    }

    $event_date = $data['event_date'];
    $title = $data['title'];
    $description = isset($data['description']) ? $data['description'] : null;

    $stmt = $conn->prepare("INSERT INTO events (event_date, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $event_date, $title, $description);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "事件添加成功！", "eventId" => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "添加事件时出错：" . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// 不支持的请求方法
http_response_code(405);
echo json_encode(["message" => "请求方法不被允许！"]);
?>