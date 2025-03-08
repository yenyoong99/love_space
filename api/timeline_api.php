<?php
header('Content-Type: application/json');

require("config/session.php");
require_once("config/conn.php");

// 通用错误响应函数
function sendErrorResponse($message) {
    echo json_encode(['error' => $message]);
    exit;
}

// 验证请求类型
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'addEvent') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $imgPaths = []; // 用于保存多个图片路径

        // 验证必要字段
        if (empty($title) || empty($content) || empty($date)) {
            sendErrorResponse('标题、内容和日期不能为空');
        }

        // 处理图片上传
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            foreach ($_FILES['images']['name'] as $key => $imageName) {
                $imgName = time() . '_' . basename($imageName);
                $imgPath = 'api/uploads/' . $imgName;
                $targetPath = $uploadDir . $imgName;

                if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $targetPath)) {
                    $imgPaths[] = $imgPath; // 保存图片路径
                } else {
                    sendErrorResponse("图片 '{$imageName}' 上传失败");
                }
            }
        }

        // 如果没有上传图片，默认 $imgPaths 为 null 或空数组
        $imgPathsJson = !empty($imgPaths) ? json_encode($imgPaths) : null;

        // 插入数据库
        $stmt = $conn->prepare("INSERT INTO timeline (date, title, content, images) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $date, $title, $content, $imgPathsJson);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'data' => [
                    'date' => $date,
                    'title' => $title,
                    'content' => $content,
                    'images' => $imgPaths
                ]]);
            } else {
                sendErrorResponse('数据库插入失败: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            sendErrorResponse('SQL 预处理失败: ' . $conn->error);
        }
    } else {
        sendErrorResponse('无效的操作');
    }
    exit;
}

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

        // 将数据库中的图片字段处理为统一格式
        foreach ($events as &$event) {
            $images = $event['images'] ?? null;

            // 兼容处理：如果是 JSON 格式，解析为数组；否则将单路径转为数组
            if ($images) {
                $decoded = json_decode($images, true);
                $event['images'] = $decoded !== null ? $decoded : [$images];
            } else {
                $event['images'] = [];
            }
        }

        echo json_encode($events);
    } else {
        echo json_encode(['error' => 'SQL 预处理失败: ' . $conn->error]);
    }
    exit;
}

// 如果请求不符合条件
sendErrorResponse('无效的请求');
?>
