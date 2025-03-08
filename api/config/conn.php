<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'love_db');
    define('DB_PASSWORD', 'love_db_password');
    define('DB_NAME', 'love_db');
    define('OPEN_AI_KEY', 'sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxx');
    
    /* Attempt to connect to MySQL database */
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $conn->set_charset("utf8mb4");
    
    // Check connection
    if($conn->connect_error){
        header("Location: access_denied.php?error=connect_error");
        // die("ERROR: Could not connect. " . $mysqli->connect_error);
    }
?>