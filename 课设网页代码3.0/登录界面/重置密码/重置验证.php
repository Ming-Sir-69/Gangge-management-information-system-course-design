<?php
session_start();
include '../../共用资源/数据库连接.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $userid = $_POST['userid'];

    // 添加日志记录
    error_log("手机号: $phone, 用户ID: $userid", 0);

    // 查询用户信息
    $sql = "SELECT 用户ID FROM 用户信息 WHERE 手机号 = ? AND 用户ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $phone, $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close(); // 关闭准备语句
        error_log("用户ID匹配成功", 0);

        // 设置会话变量
        $_SESSION['user_id'] = $userid;

        header("Location: 重置密码.php"); // 跳转到重置密码界面
        exit();
    } else {
        $stmt->close(); // 关闭准备语句
        error_log("手机号或用户ID不匹配", 0);
        header("Location: 重置验证.php?error=手机号或用户ID不匹配");
        exit();
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重置验证</title>
    <link rel="stylesheet" href="../../注册界面/注册界面.css">
    <link rel="stylesheet" href="../登录/登录样式.css">
</head>
<body>
    <div class="header">
        <h1>重置验证</h1>
    </div>
    <div class="content">
        <div class="form-container">
            <form action="重置验证.php" method="post">
                <div class="form-group">
                    <label for="phone">手机号</label>
                    <input type="text" id="phone" name="phone" oninput="validatePhone()" maxlength="11" required>
                    <span id="phone-validity" class="validity-indicator"></span>
                </div>
                <div class="form-group">
                    <label for="userid">用户ID</label>
                    <input type="text" id="userid" name="userid" maxlength="5" required>
                </div>
                <div class="form-group">
                    <button type="submit">开始重置</button>
                    <a href="../登录/登录界面.php" class="btn btn-secondary">返回</a>
                </div>

            </form>
        </div>
    </div>

    <div id="popup" class="popup"></div>

    <script src="重置验证.js"></script>
    <script>
        <?php if (isset($_GET['error'])): ?>
        showPopup("<?php echo htmlspecialchars($_GET['error']); ?>");
        <?php endif; ?>
    </script>
</body>
</html>

