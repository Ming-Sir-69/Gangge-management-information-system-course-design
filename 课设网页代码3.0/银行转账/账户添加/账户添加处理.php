<?php
session_start();
include('../../共用资源/session_connect.php'); // 引入 session 连接
include('../../共用资源/会话管理.php'); // 引入会话管理
include('../../共用资源/数据库连接.php'); // 引入数据库连接

// 调试输出
if (!isset($_SESSION['user_id'])) {
    echo "用户未登录。";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    exit;
}

$userID = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bank_name = $_POST['bank_name'];
    $card_number = str_replace(' ', '', $_POST['cardNumber']); // 移除空格
    $expiry_date = $_POST['cardExpiry'];
    $security_code = $_POST['security_code'];
    $initial_balance = $_POST['initial_balance'];

    $errors = [];

    // 验证输入
    if (!preg_match('/^\d{11,16}$/', $card_number)) {
        $errors[] = "卡号必须为11到16位数字。";
    }
    if (!preg_match('/^\d{4}$/', $expiry_date)) {
        $errors[] = "有效期格式不正确，必须为MMYY。";
    } else {
        $month = (int)substr($expiry_date, 0, 2);
        $year = (int)substr($expiry_date, 2, 2);
        if (!checkdate($month, 1, 2000 + $year)) {
            $errors[] = "有效期无效。";
        } else {
            $expiry_date_formatted = sprintf('20%02d-%02d-01', $year, $month);
        }
    }
    if (!preg_match('/^\d{3}$/', $security_code)) {
        $errors[] = "安全码必须为3位数字。";
    }

    // 检查卡号是否已存在
    $sql = "SELECT * FROM 用户银行账户 WHERE 卡号 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $card_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "该卡号已存在。";
    }

    if (count($errors) > 0) {
        $errorString = implode("\\n", $errors);
        echo "<script>alert('发生以下错误：\\n$errorString'); window.history.back();</script>";
        exit;
    } else {
        // 插入新账户
        $sql = "INSERT INTO 用户银行账户 (卡号, 用户ID, 银行名称, 有效期, 安全码, 余额) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssd", $card_number, $userID, $bank_name, $expiry_date_formatted, $security_code, $initial_balance);

        if ($stmt->execute()) {
            echo "<script>alert('添加成功。'); window.location.href='../转账页面.php';</script>";
        } else {
            echo "<script>alert('添加失败: " . $conn->error . "'); window.history.back();</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
