<?php
session_start();

$response = ['loggedIn' => isset($_SESSION['user'])];
header('Content-Type: application/json');
echo json_encode($response);