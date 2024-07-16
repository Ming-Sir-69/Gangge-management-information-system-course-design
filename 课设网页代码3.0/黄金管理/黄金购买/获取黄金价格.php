<?php
include('../共用资源/数据库连接.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $brand = $_GET['brand'];
    $query = "SELECT 价格 FROM 黄金价格 WHERE 品牌 = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $brand);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['价格'];
    } else {
        echo "0.00";
    }
}
?>
