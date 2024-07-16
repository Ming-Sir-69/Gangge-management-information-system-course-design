<?php
include '../共用资源/session_connect.php';
include '../共用资源/会话管理.php';
checkUserSession();
include '../共用资源/数据库连接.php';

// 获取用户信息
$user_id = $_SESSION['user_id'];
$sql = "SELECT 头像, 昵称, 用户ID, 角色 FROM 用户信息 WHERE 用户ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// 确定跳转链接
$user_link = "";
if ($user['角色'] === '管理员') {
    $user_link = "../用户管理/管理员界面.php?用户ID=" . htmlspecialchars($user['用户ID']);
} else {
    $user_link = "../用户管理/用户信息界面.php?用户ID=" . htmlspecialchars($user['用户ID']);
}

// 确定头像路径
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
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主界面</title>
    <link rel="stylesheet" href="主界面样式.css">
</head>
<body>
    <div class="top-bar">
        <div class="user-info">
            <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="<?php echo htmlspecialchars($avatar_alt); ?>" class="avatar" onclick="viewFullImage('<?php echo htmlspecialchars($avatar_path); ?>')">
            <a href="<?php echo $user_link; ?>" class="user-link">
                <span class="user-details"><?php echo htmlspecialchars($user['昵称']); ?></span>
                <span class="user-details"><?php echo htmlspecialchars($user['用户ID']); ?></span>
            </a>
            <span class="user-role"><?php echo htmlspecialchars($user['角色']); ?></span>
        </div>
        <button class="logout-button" onclick="logout()">退出登录</button>
    </div>

    <div id="fullscreen-overlay" class="fullscreen-overlay" onclick="closeFullImage()">
        <img id="fullscreen-image" src="" alt="全屏头像">
    </div>

    <div class="preview-container">
    <div class="preview-box" onclick="window.location.href='../银行转账/转账页面.php'">
        <h2>转账界面</h2>
        <p>点击进入转账界面</p>
    </div>
    <div class="preview-box" onclick="window.location.href='../黄金管理/黄金详情.php'">
        <h2>黄金界面</h2>
        <p>点击查看黄金详情</p>
        </div>
    </div>

    <div class="preview-container">
        <div class="preview-box" onclick="window.location.href='../股票管理/股票详情.php'">
            <h2>股票详情界面</h2>
            <p>点击查看股票详情</p>
        </div>
        <div class="preview-box" onclick="window.location.href='../总资产/总资产界面.php'">
            <h2>总资产界面</h2>
            <p>点击查看总资产详情</p>
        </div>
    </div>

    <div id="fullscreen-overlay" class="fullscreen-overlay" onclick="closeFullImage()">
        <img id="fullscreen-image" src="" alt="全屏头像">
    </div>

    <script src="主界面.js"></script>
</body>
</html>