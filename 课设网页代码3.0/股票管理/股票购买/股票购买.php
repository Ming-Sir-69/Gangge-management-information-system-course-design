<?php
// 保持session连接状态
session_start();
include('../../共用资源/session_connect.php');
include('../../共用资源/数据库连接.php');

// 检查是否指定股票ID
if (!isset($_GET['stock_id']) || empty($_GET['stock_id'])) {
    echo "<script>alert('未指定股票ID'); window.location.href='../股票详情.php';</script>";
    exit();
}

// 获取用户ID
$userId = $_SESSION['user_id'];

// 获取股票信息
$stockId = $_GET['stock_id'];
$sql = "SELECT 当前价格 FROM 股票信息 WHERE 股票ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $stockId);
$stmt->execute();
$result = $stmt->get_result();
$pricePerShare = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pricePerShare = $row['当前价格'];
}

// 查询用户的银行账户信息
$query = "SELECT 卡号 FROM 用户银行账户 WHERE 用户ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userBankAccounts = [];
while ($row = $result->fetch_assoc()) {
    $userBankAccounts[] = $row;
}

if (empty($userBankAccounts)) {
    $showModal = true;
} else {
    $showModal = false;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>股票购买</title>
    <link rel="stylesheet" href="股票购买样式.css">
</head>
<body>
    <div class="top-bar">
        <a href="../股票详情.php" class="back-link">&lt; 返回</a>
        <h1>股票购买</h1>
    </div>

    <?php if ($showModal): ?>
    <div class="overlay"></div>
    <div class="modal">
        <p>抱歉，您还未添加银行卡</p>
        <button onclick="window.location.href='账户添加/账户添加.php'">添加银行卡</button>
        <button onclick="window.location.href='../股票详情.php'">返回</button>
    </div>


    <?php else: ?>
    <div class="container">
        <form id="purchaseForm" method="post" action="购买处理.php">
            <input type="hidden" name="stock_id" value="<?php echo htmlspecialchars($stockId); ?>">
            <input type="hidden" name="pricePerShare" value="<?php echo htmlspecialchars($pricePerShare); ?>">

            <div class="form-group">
                <label for="paymentCard">选择付款卡</label>
                <select id="paymentCard" name="paymentCard" onchange="fetchBalance(this.value)">
                    <option value="">请选择付款卡</option>
                    <?php foreach ($userBankAccounts as $account): ?>
                        <option value="<?php echo htmlspecialchars($account['卡号']); ?>">
                            <?php echo htmlspecialchars($account['卡号']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="availableBalance">可用余额</label>
                <span id="availableBalance">￥0.00</span>
            </div>

            <div class="form-group">
                <label for="quantity">购买数量 (股)</label>
                <input type="number" id="quantity" name="quantity" step="1" oninput="calculateTotal()">
            </div>

            <div class="form-group">
                <label for="totalPrice">总价</label>
                <span id="totalPrice">￥0.00</span>
            </div>

            <button type="submit">确认购买</button>
        </form>
    </div>
    <?php endif; ?>
    <script src="股票购买.js"></script>
</body>
</html>
