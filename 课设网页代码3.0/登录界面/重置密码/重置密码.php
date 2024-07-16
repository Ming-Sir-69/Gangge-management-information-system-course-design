<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重置密码</title>
    <link rel="stylesheet" href="../../注册界面/注册界面.css">
    <link rel="stylesheet" href="../登录/登录样式.css">
</head>
<body>
    <div class="header">
        <h1>重置密码</h1>
    </div>
    <div class="content">
        <div class="form-container">
            <form action="重置密码.php" method="post" oninput="validatePassword(); confirmPasswordMatch();">
                <div class="form-group">
                    <label for="password">新密码</label>
                    <input type="password" id="password" name="password" maxlength="15" pattern="[a-zA-Z0-9@&#]{6,15}" required onfocus="showHint()" onblur="hideHint()">
                    <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                    <span id="password-validity" class="validity-indicator"></span>
                    <div id="password-hint" class="password-hint">密码只能包含a~z、A~Z、0~9、@、&、#这些符号，长度为6-15位。</div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">再次确认密码</label>
                    <input type="password" id="confirm_password" name="confirm_password" maxlength="15" required>
                    <span class="password-toggle" onclick="togglePassword('confirm_password')">👁️</span>
                    <span id="confirm-password-validity" class="validity-indicator"></span>
                </div>
                <div class="form-group">
                    <button type="submit">重置密码</button>
                </div>
                <?php if (isset($error)): ?>
                <div class="form-group">
                    <span class="error-message"><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="重置密码.js"></script>
</body>
</html>

<?php
session_start();
include '../../共用资源/数据库连接.php';

// 检查用户是否已通过重置验证
if (!isset($_SESSION['user_id'])) {
    header("Location: 重置验证.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        // 使用哈希加密新密码
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $user_id = $_SESSION['user_id'];

        // 更新数据库中的密码
        $sql = "UPDATE 用户信息 SET 密码 = ? WHERE 用户ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);
        if ($stmt->execute()) {
            $stmt->close();
            session_destroy(); // 销毁会话
            header("Location: ../登录/登录界面.php?message=密码重置成功，请重新登录");
            exit();
        } else {
            $stmt->close();
            $error = "密码重置失败，请重试";
        }
    } else {
        $error = "两次输入的密码不匹配";
    }
}
$conn->close();
?>