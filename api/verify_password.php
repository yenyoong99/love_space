<?php
session_start();

$password = json_decode(file_get_contents('php://input'))->password;
$correctPassword = "admin123";

if ($password === $correctPassword) {
    $_SESSION['user'] = $correctPassword;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}