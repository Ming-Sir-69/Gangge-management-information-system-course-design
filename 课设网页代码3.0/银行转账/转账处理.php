<?php
session_start();
require_once '../共用资源/数据库连接.php';

// 获取表单数据
$recipientAccount = $_POST['recipientAccount'] ?? null;
$amount = $_POST['amount'] ?? null;
$paymentCard = $_POST['paymentCard'] ?? null;
$transactionType = $_POST['transactionType'] ?? null;

// 检查表单数据是否为空并显示弹窗
if (!$paymentCard) {
    echo "<script>alert('请选择您的支付账号'); window.location.href = '转账页面.php';</script>";
    exit();
}

if (!$amount) {
    echo "<script>alert('请合理输入转账金额'); window.location.href = '转账页面.php';</script>";
    exit();
}

if (!$recipientAccount) {
    echo "<script>alert('请选择收款账号'); window.location.href = '转账页面.php';</script>";
    exit();
}

if (!$transactionType) {
    echo "<script>alert('请选择交易类型'); window.location.href = '转账页面.php';</script>";
    exit();
}

// 开始数据库操作
$sql = "SELECT 余额 FROM 用户银行账户 WHERE 卡号 = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $paymentCard);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $paymentBalance = $row['余额'];

    // 计算转账总额（含手续费）
    $totalAmount = $amount * 1.001;

    if ($paymentBalance >= $totalAmount) {
        // 查询收款账户余额和用户信息
        $sql = "SELECT 余额, 昵称, 头像 FROM 用户银行账户 INNER JOIN 用户信息 ON 用户银行账户.用户ID = 用户信息.用户ID WHERE 卡号 = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $recipientAccount);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $recipientBalance = $row['余额'];
            $recipientNickname = $row['昵称'];
            $avatarNumber = $row['头像'];

            // 隐私处理
            $recipientNickname = mb_substr($recipientNickname, 0, 1) . str_repeat('*', mb_strlen($recipientNickname) - 2) . mb_substr($recipientNickname, -1);
            $recipientAccountHidden = mb_substr($recipientAccount, 0, 1) . str_repeat('*', mb_strlen($recipientAccount) - 4) . mb_substr($recipientAccount, -3);

            // 计算新的余额
            $newPaymentBalance = $paymentBalance - $totalAmount;
            $newRecipientBalance = $recipientBalance + $amount;

            // 开始转账事务
            $conn->begin_transaction();
            try {
                // 更新转出账户余额
                $sql = "UPDATE 用户银行账户 SET 余额 = ? WHERE 卡号 = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ds", $newPaymentBalance, $paymentCard);
                $stmt->execute();

                // 更新收款账户余额
                $sql = "UPDATE 用户银行账户 SET 余额 = ? WHERE 卡号 = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ds", $newRecipientBalance, $recipientAccount);
                $stmt->execute();

                // 记录交易信息
                $sql = "INSERT INTO 交易信息 (转出卡号, 转入卡号, 交易金额, 交易类型, 创建时间) VALUES (?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssds", $paymentCard, $recipientAccount, $amount, $transactionType);
                $stmt->execute();

                // 获取插入的交易ID
                $transactionID = $conn->insert_id;

                // 提交事务
                $conn->commit();

                // 构建交易ID
                $currentTime = date('YmdHis');
                $displayTransactionID = $currentTime . $transactionID;

                // 显示成功信息并展示交易详情
                echo "
                <script>
                    alert('转账成功');
                    setTimeout(function() {
                        window.location.href = '转账详情页面.php?amount={$amount}&transactionID={$displayTransactionID}&nickname={$recipientNickname}&transactionType={$transactionType}&recipientAccount={$recipientAccountHidden}&avatarNumber={$avatarNumber}';
                    }, 3000);
                </script>";
            } catch (Exception $e) {
                // 回滚事务
                $conn->rollback();
                echo "<script>alert('转账失败，请重试。'); window.location.href = '转账页面.php';</script>";
            }
        } else {
            echo "<script>alert('收款账户不存在。'); window.location.href = '转账页面.php';</script>";
        }
    } else {
        echo "<script>alert('余额不足，无法完成转账。'); window.location.href = '转账页面.php';</script>";
    }
} else {
    echo "<script>alert('转出账户不存在。'); window.location.href = '转账页面.php';</script>";
}
?>
