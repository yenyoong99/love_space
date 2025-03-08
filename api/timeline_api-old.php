<?php
header('Content-Type: application/json');

require("config/session.php");
require_once("config/conn.php");

// 处理请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'addEvent') {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $date = $_POST['date'] ?? '';
        $imgPath = null;

        // 处理图片上传
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/uploads/';
            $imgName = time() . '_' . basename($_FILES['image']['name']);
            $imgPath = 'api/uploads/' . $imgName;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imgName)) {
                echo json_encode(['error' => '图片上传失败']);
                exit;
            }
        }

        // 插入数据库
        $stmt = $conn->prepare("INSERT INTO timeline (date, title, content, image) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $date, $title, $content, $imgPath); // 绑定参数类型为 string
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'data' => [
                    'date' => $date,
                    'title' => $title,
                    'content' => $content,
                    'img' => $imgPath
                ]]);
            } else {
                echo json_encode(['error' => '数据库操作失败']);
            }
            $stmt->close();
        } else {
            echo json_encode(['error' => 'SQL 预处理失败']);
        }
    }
    exit;
}

// 获取数据
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filterYear = $_GET['filterYear'] ?? null;
    $filterMonth = $_GET['filterMonth'] ?? null;

    $query = "SELECT * FROM timeline";
    $params = [];
    $types = '';

    // 动态构建查询条件
    if ($filterYear || $filterMonth) {
        $query .= " WHERE";
        if ($filterYear) {
            $query .= " YEAR(date) = ?";
            $params[] = $filterYear;
            $types .= 's';
        }
        if ($filterMonth) {
            $query .= ($filterYear ? " AND" : "") . " MONTH(date) = ?";
            $params[] = $filterMonth;
            $types .= 's';
        }
    }

    $query .= " ORDER BY date ASC";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $events = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($events);
    } else {
        echo json_encode(['error' => 'SQL 预处理失败']);
    }
    exit;
}
?>
?>