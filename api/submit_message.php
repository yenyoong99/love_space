<?php
require("config/session.php");
require_once("config/conn.php");

$data = json_decode(file_get_contents('php://input'), true);

$message = $conn->real_escape_string($data['message']);
$mood = $conn->real_escape_string($data['mood']);
$sender_name = $conn->real_escape_string($data['sender_name']);

$conn->query("INSERT INTO messages (sender_name, message, mood) VALUES ('$sender_name', '$message', '$mood')");
echo json_encode(["success" => true]);
