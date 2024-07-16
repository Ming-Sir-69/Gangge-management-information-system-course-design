<?php
// 引用数据库连接文件
include('../共用资源/数据库连接.php');

// 定义品牌系数
$brands = [
    '小铭黄金' => ['purity' => 1.01, 'craft' => 1.01, 'size' => 0.99],
    '小杨黄金' => ['purity' => 0.98, 'craft' => 1.00, 'size' => 1.02],
    '小孙黄金' => ['purity' => 1.00, 'craft' => 1.01, 'size' => 0.99],
];

// 获取当前市场均价及上次记录时间
$sql = "SELECT 价格, 记录时间 FROM 黄金价格 WHERE 黄金价格ID = 0 ORDER BY 记录时间 DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_market_price = $row['价格'];
    $last_record_time = $row['记录时间'];
} else {
    $last_market_price = 548.0;
    $last_record_time = date("Y-m-d H:i:s", strtotime("today"));
}

// 计算时间差（秒）
$time_difference = time() - strtotime($last_record_time);

// 获取当前时间
$current_time = date("Y-m-d H:i:s");

// 定义预期回报率和波动率
$mu = 0.01;
$sigma = 0.02;

// 价格波动算法
function simulatePriceFluctuation($current_price, $mu, $sigma, $time_seconds) {
    $delta_t = $time_seconds / 3600; // 将时间差转换为小时
    $epsilon = (mt_rand() / mt_getrandmax()) * 2 - 1; // 生成标准正态分布随机数

    // 计算新价格
    $new_price = $current_price * exp(($mu - 0.5 * $sigma * $sigma) * $delta_t + $sigma * sqrt($delta_t) * $epsilon);

    return round(max(540, min(550, $new_price)), 2); // 确保价格在合理范围内并四舍五入
}

// 生成新的市场均价
$current_market_price = simulatePriceFluctuation($last_market_price, $mu, $sigma, $time_difference);

// 更新市场均价到数据库
$sql = "UPDATE 黄金价格 SET 价格 = ?, 记录时间 = ? WHERE 黄金价格ID = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $current_market_price, $current_time);
$stmt->execute();
$stmt->close();

// 计算品牌价格
function calculateBrandPrice($marketPrice, $brandCoefficients) {
    $brandPrices = [];
    foreach ($brandCoefficients as $brand => $coefficients) {
        $brandPrice = $marketPrice * $coefficients['purity'] * $coefficients['craft'] * $coefficients['size'];
        $brandPrices[$brand] = round($brandPrice, 2);
    }
    return $brandPrices;
}

$current_brand_prices = calculateBrandPrice($current_market_price, $brands);

// 更新各品牌价格到数据库
$sql = "UPDATE 黄金价格 SET 价格 = ?, 记录时间 = ? WHERE 品牌 = ?";
$stmt = $conn->prepare($sql);
foreach ($current_brand_prices as $brand => $price) {
    $stmt->bind_param("dss", $price, $current_time, $brand);
    $stmt->execute();
}
$stmt->close();

// 查询上次记录的品牌价格
$last_brand_prices = [];
$sql = "SELECT 品牌, 价格 FROM 黄金价格 WHERE 品牌 != '市场均价' AND 记录时间 = (SELECT MAX(记录时间) FROM 黄金价格 WHERE 品牌 != '市场均价')";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $last_brand_prices[$row['品牌']] = $row['价格'];
}

// 返回需要的信息
return [
    'last_record_time' => $last_record_time,
    'last_market_price' => $last_market_price,
    'last_brand_prices' => $last_brand_prices,
    'current_time' => $current_time,
    'current_market_price' => $current_market_price,
    'current_brand_prices' => $current_brand_prices
];
?>
