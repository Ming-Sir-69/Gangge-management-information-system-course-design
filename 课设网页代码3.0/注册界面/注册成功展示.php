<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['nickname'])) {
    header("Location: 注册界面.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nickname = $_SESSION['nickname'];
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>注册成功</title>
    <link rel="stylesheet" href="注册界面.css">
</head>
<body>
    <div class="header">
        <h1>跨银行金融管理系统</h1>
    </div>

    <div class="content">
        <div class="form-container">
            <h1>注册成功</h1>
            <p>您的用户ID：<span id="user-id"><?php echo $user_id; ?></span> <button onclick="copyUserId()">复制</button></p>
            <p>您的昵称：<?php echo $nickname; ?></p>
            <div class="form-group">
                <button onclick="window.location.href='../登录界面/登录/登录界面.php'">返回登录</button>
            </div>
        </div>
    </div>

    <script>
        function copyUserId() {
            var copyText = document.getElementById('user-id');
            var textArea = document.createElement('textarea');
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('Copy');
            textArea.remove();
            alert('用户ID已复制到剪贴板');
        }
    </script>
</body>
</html>
