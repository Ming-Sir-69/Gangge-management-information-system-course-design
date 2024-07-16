<?php
include('../../共用资源/会话管理.php');
include('../../共用资源/数据库连接.php');
checkUserSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $stockId = $_POST['stock_id'];
    $pricePerShare = $_POST['pricePerShare'];
    $paymentCard = $_POST['paymentCard'];
    $quantity = $_POST['quantity'];

    // 计算总价
    $totalPrice = $pricePerShare * $quantity;

    // 验证余额是否足够
    $sql = "SELECT 余额 FROM 用户银行账户 WHERE 卡号 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $paymentCard);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['余额'] < $totalPrice) {
        echo "<script>alert('余额不足'); window.location.href='股票购买.php?stock_id=" . urlencode($stockId) . "';</script>";
        exit();
    }

    // 更新余额
    $newBalance = $row['余额'] - $totalPrice;
    $sql = "UPDATE 用户银行账户 SET 余额 = ? WHERE 卡号 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $newBalance, $paymentCard);
    $stmt->execute();

    // 记录股票持有信息
    $sql = "INSERT INTO 用户股票持有 (用户ID, 股票ID, 持有数量, 成交单价, 创建时间) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $userId, $stockId, $quantity, $pricePerShare);
    $stmt->execute();

    // 获取插入的交易ID
    $transactionID = $conn->insert_id;

    // 显示成功信息并返回详情页面
    echo "<script>
            alert('购买成功');
            window.location.href = '购买详情/购买详情.php?quantity={$quantity}&totalPrice={$totalPrice}&transactionID={$transactionID}&stock_id=" . urlencode($stockId) . "';
          </script>";
}
?>
