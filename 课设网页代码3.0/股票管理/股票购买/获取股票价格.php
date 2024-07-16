<?php
include('../../共用资源/数据库连接.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stockId = $_GET['stock_id'];
    $query = "SELECT 当前价格 FROM 股票信息 WHERE 股票ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $stockId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['当前价格'];
    } else {
        echo "0.00";
    }
}
?>
