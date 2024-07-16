<?php
include '../共用资源/数据库连接.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountNumber = $_POST['accountNumber'];
    $query = "SELECT 银行名称 FROM 用户银行账户 WHERE 卡号 = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $accountNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    $bankName = '';
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $bankName = $row['银行名称'];
    }
    echo $bankName;
}
?>
