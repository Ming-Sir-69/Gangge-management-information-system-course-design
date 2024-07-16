<?php
include '../共用资源/会话管理.php';
include '../共用资源/数据库连接.php';
checkUserSession();

$userId = $_SESSION['user_id'];

// 查询用户的真实姓名
$query = "SELECT 真实姓名 FROM 用户信息 WHERE 用户ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$realName = '';
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $realName = $user['真实姓名'];
} else {
    $realName = '用户';
}

// 查询用户是否有绑定的银行卡
$query = "SELECT COUNT(*) AS card_count FROM 用户银行账户 WHERE 用户ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$showModal = $row['card_count'] == 0;

// 查询除当前用户外的所有绑定银行卡的用户ID及其真实姓名
$query = "SELECT 用户信息.用户ID, 用户信息.真实姓名 
          FROM 用户信息 
          JOIN 用户银行账户 ON 用户信息.用户ID = 用户银行账户.用户ID 
          WHERE 用户信息.用户ID != ? 
          GROUP BY 用户信息.用户ID, 用户信息.真实姓名";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$otherUsers = array();
while ($row = $result->fetch_assoc()) {
    $otherUsers[] = $row;
}

// 查询当前用户的所有银行卡号
$query = "SELECT 卡号, 银行名称 FROM 用户银行账户 WHERE 用户ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$userBankAccounts = array();
while ($row = $result->fetch_assoc()) {
    $userBankAccounts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>银行转账</title>
    <link rel="stylesheet" href="转账样式.css">
    <style>
        /* 覆盖层样式 */
        .overlay {
            display: <?php echo $showModal ? 'block' : 'none'; ?>;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.5); /* 白色半透明背景 */
            z-index: 1000;
        }

        /* 弹窗样式 */
        .modal {
            display: <?php echo $showModal ? 'block' : 'none'; ?>;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #f5f5f7; /* 浅灰色背景 */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1001;
        }

        /* 禁用页面内容 */
        body.disable-scroll {
            overflow: hidden;
        }

        .modal-buttons {
            padding: 10px 20px;
            border-radius: 8px; /* 圆角 */
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin: 10px 5px; /* 添加外边距 */
            display: inline-block;
        }

        .button {
            padding: 10px 20px;
            background-color: #007aff; /* 苹果蓝色 */
            color: white;
            border: none;
            border-radius: 8px; /* 圆角 */
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin: 10px 5px; /* 添加外边距 */
            display: inline-block;
        }

        .button:hover {
            background-color: #005bb5; /* 深蓝色悬停 */
        }
    </style>
</head>
<body class="<?php echo $showModal ? 'disable-scroll' : ''; ?>">
    <?php if ($showModal): ?>
    <div class="overlay"></div>
    <div class="modal">
        <p>抱歉，您还未添加银行卡</p>
        <div class="modal-buttons">
            <button onclick="window.location.href='./账户添加/账户添加.php'">添加银行卡</button>
            <button onclick="window.location.href='../主界面/主界面.php'">返回</button>
        </div>
    </div>
    <?php endif; ?>
    <div class="container">
        <header>
            <button class="back-button" onclick="window.location.href='../主界面/主界面.php'">&lt; 返回</button>
            <div class="welcome-message">
                <?php echo htmlspecialchars($realName); ?>，欢迎使用转账
            </div>
            <h1>转账</h1>
        </header>

        <form method="post" action="转账处理.php">
            <!-- 收款人信息 -->
            <section class="recipient-info">
                <h2>收款人</h2>
                <label for="recipientName">户名</label>
                <select id="recipientName" name="recipientName" onchange="fetchBankAccounts(this.value)">
                    <option value="">请选择收款人</option>
                    <?php foreach ($otherUsers as $user): ?>
                        <option value="<?php echo htmlspecialchars($user['用户ID']); ?>">
                            <?php echo htmlspecialchars($user['真实姓名']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label for="recipientAccount">账号</label>
                <select id="recipientAccount" name="recipientAccount" readonly>
                    <!-- 根据选择的用户动态加载其账号 -->
                </select>
                <br>
                <label for="bank">银行</label>
                <input type="text" id="bank" name="bank" readonly>
            </section>

            <!-- 付款卡信息 -->
            <section class="payment-card">
                <h2>付款卡</h2>
                <label for="paymentCard">选择付款卡</label>
                <select id="paymentCard" name="paymentCard">
                    <option value="">请选择付款卡</option>
                    <?php foreach ($userBankAccounts as $account): ?>
                        <option value="<?php echo htmlspecialchars($account['卡号']); ?>">
                            <?php echo htmlspecialchars($account['卡号'] . ' (' . $account['银行名称'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <!-- 交易类型 -->
                <label for="transactionType">交易类型</label>
                <select id="transactionType" name="transactionType">
                    <option value="购物">购物</option>
                    <option value="服务">服务</option>
                    <option value="餐饮">餐饮</option>
                    <option value="交通">交通</option>
                    <option value="转账">转账</option>
                    <option value="其他">其他</option>
                </select>
            </section>

            <!-- 转账金额输入 -->
            <section class="transfer-amount">
                <h2>转账金额</h2>
                <label for="amount">￥</label>
                <input type="number" id="amount" name="amount" placeholder="金额" step="0.01">
                <p>手续费 0.1%</p>
            </section>

            <p id="availableBalance">可用余额 ￥0.00</p>  
            <button type="button" onclick="window.location.href='账户添加/账户添加.php'" style="margin-left: 10px; padding: 10px; border: none; background-color: #4CAF50; color: white; border-radius: 4px; cursor: pointer;">添加银行卡</button>

            <!-- 下一步按钮 -->
            <button type="submit" class="next-button">下一步</button>
        </form>

        <!-- 安全提示 -->
        <section class="safety-tips">
            <h2>安全提示：</h2>
            <p>1. 为了您的资金安全，切莫相信以冒充公检法、领导或亲人朋友、或以取消“百万保障”、刷单、网购退款、快递理赔、鼓吹大额投资理财等理由要求进行的转账汇款，谨防诈骗，并请您务必确认收款人身份及转账事由。</p>
            <p>2. 不扫描可疑二维码、不安装不明App软件、妥善保管卡号、密码、短信验证码等重要信息。</p>
        </section>
    </div>

    <script src="转账.js"></script>
</body>
</html>
