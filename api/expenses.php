<?php
header('Content-Type: application/json');
require("config/session.php");
require_once("config/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    if (isset($_GET['action']) && $_GET['action'] === 'category_totals') {
        // 获取各分类的支出总额
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');

        $stmt = $conn->prepare("SELECT category, SUM(amount) as total FROM expenses WHERE YEAR(date) = ? AND MONTH(date) = ? GROUP BY category");
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        $categoryTotals = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode($categoryTotals);
    } else {
        // 获取支出记录
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $itemsPerPage = 10; // 每页显示的记录数
    
        // 计算总记录数和总支出
        $countStmt = $conn->prepare("SELECT COUNT(*) as total, SUM(amount) as totalAmount FROM expenses WHERE YEAR(date) = ? AND MONTH(date) = ?");
        $countStmt->bind_param("ii", $year, $month);
        $countStmt->execute();
        $totalResult = $countStmt->get_result();
        $totalData = $totalResult->fetch_assoc();
        $totalItems = $totalData['total'];
        $totalAmount = $totalData['totalAmount'];
    
        // 计算总页数
        $totalPages = ceil($totalItems / $itemsPerPage);
    
        // 计算 OFFSET
        $offset = ($page - 1) * $itemsPerPage;
    
        // 获取当前页的记录
        $stmt = $conn->prepare("SELECT * FROM expenses WHERE YEAR(date) = ? AND MONTH(date) = ? ORDER BY date DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("iiii", $year, $month, $itemsPerPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $expenses = $result->fetch_all(MYSQLI_ASSOC);
    
        echo json_encode([
            'expenses' => $expenses,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalAmount' => $totalAmount
        ]);
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 添加新的支出记录
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("INSERT INTO expenses (date, amount, description, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $data['date'], $data['amount'], $data['description'], $data['category']);
    $result = $stmt->execute();

    echo json_encode(['success' => $result]);
}

$conn->close();
?>