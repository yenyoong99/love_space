<?php
header("Content-Type: application/json");

http_response_code(403); // 返回 403 Forbidden 状态码
echo json_encode([
    "error" => "Access Denied"
]);
exit;
?>