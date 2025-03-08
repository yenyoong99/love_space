<?php
session_start();

// 设置访问密码
$access_password = "admin123"; // 修改为您想要的密码

// 检查用户是否已登录
if (!isset($_SESSION['authenticated'])) {
    // 如果未登录，检查提交的密码
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $access_password) {
            $_SESSION['authenticated'] = true;
            header("Location: " . $_SERVER['PHP_SELF']); // 防止表单重复提交
            exit;
        } else {
            $error = "Invalid password. Please try again.";
        }
    }

    // 如果未通过验证，显示登录表单
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #1e1e2f;
                color: #f9f9f9;
            }
            .login-container {
                text-align: center;
                background: #2e2e48;
                padding: 30px;
                border: 1px solid #444;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }
            input[type="password"] {
                padding: 12px;
                width: 90%;
                margin-bottom: 15px;
                border: 1px solid #666;
                border-radius: 6px;
                background-color: #3e3e5e;
                color: #f9f9f9;
            }
            button {
                padding: 12px 24px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 16px;
            }
            button:hover {
                background-color: #0056b3;
            }
            .error {
                color: #ff6b6b;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>Login</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="password" name="password" placeholder="Enter password" required>
                <br>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

require_once("api/config/conn.php");
// 分页设置
$limit = 10; // 每页显示的记录数
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // 当前页
$offset = ($page - 1) * $limit; // 计算偏移量

// 获取总记录数
$total_query = "SELECT COUNT(*) AS total FROM visitor_logs";
$total_result = $conn->query($total_query);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// 获取当前页的数据
$sql = "SELECT * FROM visitor_logs ORDER BY visit_date DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// 检查数据是否存在
if (!$result) {
    die('Query failed: ' . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Logs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1e1e2f;
            color: #f9f9f9;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #ff9800;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #2e2e48;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 12px;
            text-align: left;
            color: #f9f9f9;
        }
        th {
            background-color: #3e3e5e;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #393953;
        }
        tr:hover {
            background-color: #505071;
        }
        .pagination {
            text-align: center;
            margin: 20px 0;
        }
        .pagination a {
            display: inline-block;
            margin: 0 8px;
            padding: 10px 16px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #2e2e48;
        }
        .pagination a.active {
            background-color: #ff9800;
            color: #fff;
            border: 1px solid #ff9800;
        }
        .pagination a:hover {
            background-color: #ff5722;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Visitor Logs</h1>
    <?php if ($total_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IP Address</th>
                    <th>URL</th>
                    <th>User Agent</th>
                    <th>Visit Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                        <td><?php echo htmlspecialchars($row['url']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_agent']); ?></td>
                        <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>No visitor logs found.</p>
    <?php endif; ?>

    <?php
    // 关闭连接
    $result->free();
    $conn->close();
    ?>
</body>
</html>
