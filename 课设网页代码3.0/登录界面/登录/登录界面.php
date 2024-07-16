<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录界面</title>
    <link rel="stylesheet" href="../../注册界面/注册界面.css">
    <link rel="stylesheet" href="登录样式.css">
    <style>
    .password-toggle {
        cursor: pointer;
        margin-left: 10px;
        font-size: 1.2em;
    }
    .valid {
        color: #34c759; /* 绿色 */
    }
    .invalid {
        color: #ff3b30; /* 红色 */
    }
    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff3cd; /* 浅黄色背景 */
        color: #856404; /* 深黄色字体 */
        border: 1px solid #ffeeba; /* 浅黄色边框 */
        padding: 20px;
        border-radius: 5px;
        z-index: 1000;
    }
    .password-hint {
        display: none;
        color: #6e6e73; /* 中灰色字体 */
        font-size: 0.9em;
    }
</style>
</head>
<body>
    <div class="header">
        <h1>登录</h1>
    </div>
    <div class="content">
        <div class="form-container">
            <form action="登录处理.php" method="post">
            <div class="form-group">
                <label for="phone">手机号</label>
                <input type="text" id="phone" name="phone" oninput="validatePhone()" maxlength="11" pattern="\d*" required>
                <span id="phone-validity" class="validity-indicator"></span>
            </div>
                <div class="form-group">
                    <label for="password">密码</label>
                    <input type="password" id="password" name="password" oninput="validatePassword()" maxlength="15" onfocus="showHint()" onblur="hideHint()" required>
                    <span class="password-toggle" onclick="togglePasswordVisibility()">🙈</span>
                    <span id="password-validity" class="validity-indicator"></span>
                    <div id="password-hint" class="password-hint">密码只能包含a~z、A~Z、0~9、@、&、#这些符号，长度为6-15位。</div>
                </div>
                <div class="form-group">
                    <button type="submit">登录</button>
                </div>
                <div class="form-group">
                    <a href="../重置密码/重置验证.php">忘记密码？</a>
                </div>
                <div class="form-group">
                    <a href="../../注册界面/注册界面.php">新用户？前往注册</a>
                </div>
            </form>
        </div>
    </div>

    <div id="popup" class="popup"></div>

    <script src="登录界面.js"></script>
    <script>
        function showPopup(message) {
            var popup = document.getElementById("popup");
            popup.textContent = message;
            popup.style.display = "block";
            setTimeout(function() {
                popup.style.display = "none";
            }, 3000);//弹窗3秒后自动消失
        }

        <?php if (isset($_GET['error'])): ?>
        showPopup("<?php echo htmlspecialchars($_GET['error']); ?>");
        <?php endif; ?>
    </script>
</body>
</html>
