<?php
require("config/session.php");
require_once("config/conn.php");

$itemsPerPage = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$totalItems = $conn->query("SELECT COUNT(*) AS count FROM messages")->fetch_assoc()['count'];
$totalPages = ceil($totalItems / $itemsPerPage);

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT $itemsPerPage OFFSET $offset");
$messages = $result->fetch_all(MYSQLI_ASSOC);

// if (!isset($_SESSION['user'])) {
//     $messages = ""
// }

echo json_encode([
    "messages" => $messages,
    "currentPage" => $page,
    "totalPages" => $totalPages
]);

