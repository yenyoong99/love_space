<?php
session_start();

require_once 'log_visitor.php';

//function to check if the session variable member_id is on set
function logged_in() {
	return isset($_SESSION['user']);
}

function confirm_logged_in() {
	if (!logged_in()) {
	    http_response_code(403); // 返回 403 Forbidden 状态码
        echo json_encode([
            "error" => "未登录，请先登录！"
        ]);
		header( "Location: access_denied.php?error=connect_error" ); die;
	}
}

confirm_logged_in();
?>