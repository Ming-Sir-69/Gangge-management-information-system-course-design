<?php
// 保持session连接状态
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../共用资源/session_connect.php');
include('../共用资源/数据库连接.php');

// 检查用户是否已登录
if (!isset($_SESSION['user_id'])) {
    echo "用户未登录。";
    exit;
}

$userId = $_SESSION['user_id'];

// 查询用户的所有银行卡号和对应余额
$sql = "SELECT 余额 FROM 用户银行账户 WHERE 用户ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$bankAssets = 0;
while ($row = $result->fetch_assoc()) {
    $bankAssets += $row['余额'];
}
$stmt->close();

// 存储银行资产到POST
$_POST['bank_assets'] = $bankAssets;

// 暂时存储连接状态
$temp_conn = $conn;

// 运行黄金价格生成代码
include('../黄金管理/黄金价格生成.php');

// 恢复连接状态
$conn = $temp_conn;

// 查询用户持有的黄金数量和现在的金价
$sql = "SELECT uh.持有数量, hp.价格 
        FROM 用户黄金持有 uh 
        JOIN 黄金价格 hp ON uh.黄金价格ID = hp.黄金价格ID 
        WHERE uh.用户ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$goldAssets = 0;
while ($row = $result->fetch_assoc()) {
    $goldAssets += $row['持有数量'] * $row['价格'];
}
$stmt->close();

// 存储黄金资产到POST
$_POST['gold_assets'] = $goldAssets;

// 再次存储连接状态
$temp_conn = $conn;

// 运行股票价格生成代码
include('../股票管理/股票价格生成.php');

// 恢复连接状态
$conn = $temp_conn;

// 查询用户持有的股票数量和现在的股价
$sql = "SELECT us.持有数量, si.当前价格 
        FROM 用户股票持有 us 
        JOIN 股票信息 si ON us.股票ID = si.股票ID 
        WHERE us.用户ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$stockAssets = 0;
while ($row = $result->fetch_assoc()) {
    $stockAssets += $row['持有数量'] * $row['当前价格'];
}
$stmt->close();

// 存储股票资产到POST
$_POST['stock_assets'] = $stockAssets;

// 获取股票价格的更新时间
$sql = "SELECT 更新时间 FROM 股票信息 ORDER BY 更新时间 DESC LIMIT 1";
$result = $conn->query($sql);
$updateTime = "";
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $updateTime = $row['更新时间'];
}

// 存储更新时间到POST
$_POST['update_time'] = $updateTime;

// 关闭数据库连接
$conn->close();
?>
