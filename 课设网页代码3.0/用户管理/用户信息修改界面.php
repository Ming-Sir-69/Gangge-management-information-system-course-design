<?php
include '../共用资源/session_connect.php';
include '../共用资源/会话管理.php';
checkUserSession();

// 从会话中获取用户信息
$user = $_SESSION['user_info'];

$avatar_number = intval($user['头像']);
$avatar_path = "";
$avatar_alt = "";

if ($avatar_number <= 5) {
    switch ($avatar_number) {
        case 1:
            $avatar_path = "../共用资源/图片/头像/预设/男一.jpg";
            $avatar_alt = "男一";
            break;
        case 2:
            $avatar_path = "../共用资源/图片/头像/预设/男二.jpg";
            $avatar_alt = "男二";
            break;
        case 3:
            $avatar_path = "../共用资源/图片/头像/预设/女一.jpg";
            $avatar_alt = "女一";
            break;
        case 4:
            $avatar_path = "../共用资源/图片/头像/预设/女二.jpg";
            $avatar_alt = "女二";
            break;
        case 5:
            $avatar_path = "../共用资源/图片/头像/预设/非人.jpg";
            $avatar_alt = "非人";
            break;
        default:
            $avatar_path = "../共用资源/图片/头像/预设/非人.jpg";
            $avatar_alt = "默认头像";
    }
} else {
    $avatar_path = "../共用资源/图片/头像/用户/用户头像" . ($avatar_number) . ".jpg?" . time(); // 添加时间戳
    $avatar_alt = "用户头像";
}

$nickname = htmlspecialchars($user['昵称'] ?? '未知昵称');
$userID = htmlspecialchars($user['用户ID'] ?? '未知用户ID');
$role = htmlspecialchars($user['角色'] ?? '未知角色');
$gender = htmlspecialchars($user['性别'] ?? '');
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户信息修改界面</title>
    <link rel="stylesheet" href="用户管理样式.css">
</head>
<body>
    <div class="top-bar">
        <div class="user-info">
            <a href="javascript:void(0);" onclick="triggerFileUpload();">
                <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="<?php echo htmlspecialchars($avatar_alt); ?>" class="avatar">
            </a>
            <span class="user-details"><?php echo $nickname; ?></span>
            <span class="user-details"><?php echo $userID; ?></span>
            <span class="user-role"><?php echo $role; ?></span>
        </div>
        <button class="back-button" onclick="window.location.href='../主界面/主界面.php'">返回主界面</button>
        <button class="logout-button" onclick="logout()">退出登录</button>
    </div>

    <div id="fullscreen-overlay" class="fullscreen-overlay" onclick="closeFullImage()">
        <img id="fullscreen-image" src="" alt="全屏头像">
    </div>

    <div class="user-info-container">
        <h1>用户信息修改</h1>
        <form action="用户信息更新处理.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="avatar_id" value="<?php echo $avatar_number; ?>">
            <input type="file" id="avatarInput" name="avatar" style="display:none;">
            <table>
                <tr><td>头像:</td><td><img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="<?php echo htmlspecialchars($avatar_alt); ?>" class="avatar" onclick="triggerFileUpload();"></td></tr>
                <tr><td>昵称:</td><td><input type="text" name="nickname" value="<?php echo $nickname; ?>"></td></tr>
                <tr><td>真实姓名:</td><td><input type="text" name="realname" value="<?php echo htmlspecialchars($user['真实姓名'] ?? ''); ?>"></td></tr>
                <tr><td>手机号:</td><td><input type="text" name="phone" value="<?php echo htmlspecialchars($user['手机号'] ?? ''); ?>" maxlength="11"></td></tr>
                <tr><td>出生日期:</td><td><input type="date" name="birthdate" value="<?php echo htmlspecialchars($user['出生日期'] ?? ''); ?>"></td></tr>
                <tr><td>性别:</td>
                    <td>
                        <select id="gender" name="gender" required>
                            <option value="" disabled>选择性别</option>
                            <option value="男" <?php echo $gender == '男' ? 'selected' : ''; ?>>男</option>
                            <option value="女" <?php echo $gender == '女' ? 'selected' : ''; ?>>女</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>所在地区:</td>
                    <td>
                        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($user['所在地区'] ?? ''); ?>" onfocus="showLocationHint()" onblur="hideLocationHint()">
                        <div id="location-hint" class="location-hint">请按照省、市、区来填写</div>
                    </td>
                </tr>
            </table>
            <button type="submit">保存</button>
        </form>
    </div>

    <script src="用户管理.js"></script>
</body>
</html>
