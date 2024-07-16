<?php
$quantity = $_GET['quantity'];
$totalPrice = $_GET['totalPrice'];
$stockId = $_GET['stock_id'];

// 获取用户信息
session_start();
include('../../../共用资源/session_connect.php');
include('../../../共用资源/数据库连接.php');

$userId = $_SESSION['user_id'];

$sql = "SELECT 昵称, 头像 FROM 用户信息 WHERE 用户ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$nickname = $user['昵称'];
$avatarNumber = intval($user['头像']);

// 处理头像路径和描述
if ($avatarNumber <= 5) {
    switch ($avatarNumber) {
        case 1:
            $avatarPath = "../../../共用资源/图片/头像/预设/男一.jpg";
            $avatarAlt = "男一";
            break;
        case 2:
            $avatarPath = "../../../共用资源/图片/头像/预设/男二.jpg";
            $avatarAlt = "男二";
            break;
        case 3:
            $avatarPath = "../../../共用资源/图片/头像/预设/女一.jpg";
            $avatarAlt = "女一";
            break;
        case 4:
            $avatarPath = "../../../共用资源/图片/头像/预设/女二.jpg";
            $avatarAlt = "女二";
            break;
        case 5:
            $avatarPath = "../../../共用资源/图片/头像/预设/非人.jpg";
            $avatarAlt = "非人";
            break;
        default:
            $avatarPath = "../../../共用资源/图片/头像/预设/男一.jpg";
            $avatarAlt = "默认头像";
    }
} else {
    $avatarPath = "../../../共用资源/图片/头像/用户/用户头像" . ($avatarNumber) . ".jpg?" . time(); // 添加时间戳
    $avatarAlt = "用户头像";
}

// 获取股票名称
$query = "SELECT 股票名称 FROM 股票信息 WHERE 股票ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $stockId);
$stmt->execute();
$result = $stmt->get_result();
$stockName = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stockName = $row['股票名称'];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>购买详情</title>
    <link rel="stylesheet" href="购买详情样式.css">
</head>
<body>
    <div class="transaction-details">
        <div class="back-link">
            <a href="../../股票详情.php">&lt; 返回</a>
        </div>
        <h1>购买详情</h1>
        <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="<?php echo htmlspecialchars($avatarAlt); ?>" class="avatar">
        <p><?php echo htmlspecialchars($nickname); ?></p>
        <h2>恭喜获得<?php echo htmlspecialchars($quantity); ?> 股 <?php echo htmlspecialchars($stockName); ?></h2>
        <p>购买成功</p>
        <p>创建时间：<?php echo date('Y-m-d H:i:s'); ?></p>
        <p>总价：<?php echo htmlspecialchars($totalPrice); ?> ¥</p>
    </div>
</body>
</html>
