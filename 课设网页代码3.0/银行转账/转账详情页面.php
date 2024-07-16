<?php
$amount = $_GET['amount'];
$transactionID = $_GET['transactionID'];
$nickname = $_GET['nickname'];
$transactionType = $_GET['transactionType'];
$recipientAccount = $_GET['recipientAccount'];
$avatarNumber = intval($_GET['avatarNumber']);

// 处理头像路径和描述
if ($avatarNumber <= 5) {
    switch ($avatarNumber) {
        case 1:
            $avatarPath = "../共用资源/图片/头像/预设/男一.jpg";
            $avatarAlt = "男一";
            break;
        case 2:
            $avatarPath = "../共用资源/图片/头像/预设/男二.jpg";
            $avatarAlt = "男二";
            break;
        case 3:
            $avatarPath = "../共用资源/图片/头像/预设/女一.jpg";
            $avatarAlt = "女一";
            break;
        case 4:
            $avatarPath = "../共用资源/图片/头像/预设/女二.jpg";
            $avatarAlt = "女二";
            break;
        case 5:
            $avatarPath = "../共用资源/图片/头像/预设/非人.jpg";
            $avatarAlt = "非人";
            break;
        default:
            $avatarPath = "../共用资源/图片/头像/预设/男一.jpg";
            $avatarAlt = "默认头像";
    }
} else {
    $avatarPath = "../共用资源/图片/头像/用户/用户头像" . ($avatarNumber) . ".jpg?" . time(); // 添加时间戳
    $avatarAlt = "用户头像";
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>账单详情</title>
    <link rel="stylesheet" href="转账样式.css">
</head>
<body>
    <div class="transaction-details">
        <div class="back-link">
            <a href="../主界面/主界面.php">&lt; 返回</a>
        </div>
        <h1>账单详情</h1>
        <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="<?php echo htmlspecialchars($avatarAlt); ?>" class="avatar">
        <p><?php echo htmlspecialchars($nickname); ?></p>
        <h2>-<?php echo htmlspecialchars($amount); ?></h2>
        <p>交易成功</p>
        <p>创建时间：<?php echo date('Y-m-d H:i:s'); ?></p>
        <p>交易类型：<?php echo htmlspecialchars($transactionType); ?></p>
        <p>对方账户：<?php echo htmlspecialchars($recipientAccount); ?></p>
        <p>交易ID：<?php echo htmlspecialchars($transactionID); ?></p>
    </div>
</body>
</html>
