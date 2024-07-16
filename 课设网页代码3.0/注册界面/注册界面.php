<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>注册界面</title>
    <link rel="stylesheet" href="注册界面.css">
    <script src="注册界面.js" defer></script>
</head>

<body>
    <div class="header">
        <h1>跨银行金融管理系统</h1>
    </div>

    <div class="content">
        <div class="form-container">
            <form action="注册处理.php" method="post" enctype="multipart/form-data" id="registration-form">
                <div class="form-group">
                    <label for="realname">真实姓名</label>
                    <input type="text" id="realname" name="realname" required>
                </div>
                <div class="form-group">
                    <label for="nickname">昵称</label>
                    <input type="text" id="nickname" name="nickname" required>
                    <div id="nickname-availability"></div>
                </div>
                <div class="form-group">
                    <label for="phone">手机号码</label>
                    <input type="text" id="phone" maxlength="11" name="phone" required>
                    <div id="phone-availability"></div>
                </div>
                <div class="form-group">
                    <label for="password">密码</label>
                    <input type="password" id="password" name="password" pattern="[a-zA-Z0-9@&#]{6,15}" maxlength="15" required onfocus="showHint()" onblur="hideHint()">
                    <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                    <span id="password-validity" class="validity-indicator"></span>
                    <div id="password-hint" class="password-hint">密码只能包含a~z、A~Z、0~9、@、&、#这些符号，长度为6-15位。</div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">再次确认密码</label>
                    <input type="password" id="confirm_password" maxlength="15" name="confirm_password" required>
                    <span class="password-toggle" onclick="togglePassword('confirm_password')">👁️</span>
                    <span id="confirm-password-validity" class="validity-indicator"></span>
                </div>
                <div class="form-group">
                    <label for="province">所在地区</label>
                    <select id="province" name="province" onchange="updateCity()" required>
                        <option value="" selected disabled>选择省份</option>
                        <option value="天津">天津</option>
                        <option value="日本">日本</option>
                    </select>
                    <select id="city" name="city" onchange="updateDistrict()" required>
                        <option value="" selected disabled>选择城市</option>
                    </select>
                    <select id="district" name="district" required>
                        <option value="" selected disabled>选择区县</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="birthdate">出生日期</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                </div>
                <div class="form-group">
                    <label for="gender">性别</label>
                    <select id="gender" name="gender" required>
                        <option value="" selected gender>选择性别</option>
                        <option value="男">男</option>
                        <option value="女">女</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="role">身份角色</label>
                    <select id="role" name="role" required>
                        <option value="个人用户">用户</option>
                        <option value="管理员">管理员</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>选择头像</label>
                    <div class="avatar-container">
                    <img src="../共用资源/图片/头像/预设/男一.jpg" alt="男一" onclick="selectAvatar(1)">
                    <img src="../共用资源/图片/头像/预设/男二.jpg" alt="男二" onclick="selectAvatar(2)">
                    <img src="../共用资源/图片/头像/预设/女一.jpg" alt="女一" onclick="selectAvatar(3)">
                    <img src="../共用资源/图片/头像/预设/女二.jpg" alt="女二" onclick="selectAvatar(4)">
                    <img src="../共用资源/图片/头像/预设/非人.jpg" alt="非人" onclick="selectAvatar(5)">
                    <label for="custom_avatar" onclick="triggerFileUpload()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" fill-rule="evenodd" d="M9 7a5 5 0 0 1 10 0v8a7 7 0 1 1-14 0V9a1 1 0 0 1 2 0v6a5 5 0 0 0 10 0V7a3 3 0 1 0-6 0v8a1 1 0 1 0 2 0V9a1 1 0 1 1 2 0v6a3 3 0 1 1-6 0z" clip-rule="evenodd"></path>
                        </svg>
                        <input type="file" id="custom_avatar" name="custom_avatar" accept="image/jpeg" onchange="previewImage(event)" style="display: none;">
                    </label>
                </div>
                <input type="hidden" id="avatar" name="avatar">
                <div class="preview" id="preview"></div>
                </div>
                <div class="form-group">
                    <button type="submit">注册</button>
                </div>
                <div class="form-group">
                    <button type="button" onclick="window.location.href='../登录界面/登录/登录界面.php'">返回登录</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
