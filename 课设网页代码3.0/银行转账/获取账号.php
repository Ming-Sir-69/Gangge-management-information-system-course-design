<?php
include '../共用资源/数据库连接.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $query = "SELECT 卡号 FROM 用户银行账户 WHERE 用户ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $options = '<option value="">请选择账号</option>';
    while ($row = $result->fetch_assoc()) {
        $options .= '<option value="' . htmlspecialchars($row['卡号']) . '">' . htmlspecialchars($row['卡号']) . '</option>';
    }
    echo $options;
}
?>
