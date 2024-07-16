<?php
include '../共用资源/session_connect.php';
include '../共用资源/会话管理.php';
checkUserSession();
include '../共用资源/数据库连接.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT 头像, 昵称, 真实姓名, 手机号, 出生日期, 性别, 所在地区, 用户ID, 角色 FROM 用户信息 WHERE 用户ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// 存储用户信息到会话中
$_SESSION['user_info'] = $user;

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
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户信息界面</title>
    <link rel="stylesheet" href="用户管理样式.css">
</head>
<body>
    <div class="top-bar">
        <div class="user-info">
            <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="<?php echo htmlspecialchars($avatar_alt); ?>" class="avatar">
            <span class="user-details"><?php echo htmlspecialchars($user['昵称']); ?></span>
            <span class="user-details"><?php echo htmlspecialchars($user['用户ID']); ?></span>
            <span class="user-role"><?php echo htmlspecialchars($user['角色']); ?></span>
        </div>
        <button class="back-button" onclick="window.location.href='../主界面/主界面.php'">返回主界面</button>
        <button class="logout-button" onclick="logout()">退出登录</button>
    </div>

    <div id="fullscreen-overlay" class="fullscreen-overlay" onclick="closeFullImage()">
        <img id="fullscreen-image" src="" alt="全屏头像">
    </div>

    <div class="user-info-container">
        <h1>用户信息</h1>
        <table>
            <tr><td>头像:</td><td><img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="<?php echo htmlspecialchars($avatar_alt); ?>" class="avatar"></td></tr>
            <tr><td>昵称:</td><td><?php echo htmlspecialchars($user['昵称']); ?></td></tr>
            <tr><td>真实姓名:</td><td><?php echo htmlspecialchars($user['真实姓名']); ?></td></tr>
            <tr><td>手机号:</td><td><?php echo htmlspecialchars($user['手机号']); ?></td></tr>
            <tr><td>出生日期:</td><td><?php echo htmlspecialchars($user['出生日期']); ?></td></tr>
            <tr><td>性别:</td><td><?php echo htmlspecialchars($user['性别']); ?></td></tr>
            <tr><td>所在地区:</td><td><?php echo htmlspecialchars($user['所在地区']); ?></td></tr>
        </table>
        <button class="edit-button" onclick="window.location.href='用户信息修改界面.php'">编辑信息</button>
    </div>

    <script src="用户管理.js"></script>
</body>
</html>
