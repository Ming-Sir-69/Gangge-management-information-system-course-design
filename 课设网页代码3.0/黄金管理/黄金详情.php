<?php
// 保持session连接状态
session_start();
include('../共用资源/session_connect.php');

// 引用黄金价格生成脚本
$price_info = include('黄金价格生成.php');

// 获取黄金价格生成的信息
$last_record_time = $price_info['last_record_time'];
$last_market_price = $price_info['last_market_price'];
$last_brand_prices = $price_info['last_brand_prices'];
$current_time = $price_info['current_time'];
$current_market_price = $price_info['current_market_price'];
$current_brand_prices = $price_info['current_brand_prices'];

// HTML展示
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>黄金详情</title>
    <link rel="stylesheet" type="text/css" href="黄金管理样式.css">
</head>
<body>
    <div class="top-bar">
        <a href="../主界面/主界面.php" class="back-link">&lt; 返回</a>
        <h1>黄金详情</h1>
        <a href="黄金详情.php" class="refresh-link">刷新</a>
    </div>
    <div class="container">
        <div class="left-box">
            <h2>上次记录</h2>
            <h3>时间：<?php echo date("Y-m-d H:i:s", strtotime($last_record_time)); ?></h3>
            <h3>市场均价：<?php echo $last_market_price; ?> ¥</h3>
            <h3>品牌价格：</h3>
            <ul>
                <?php foreach ($last_brand_prices as $brand => $price): ?>
                    <li><?php echo $brand; ?>：<?php echo $price; ?> ¥</li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="right-box">
            <h2>当前记录</h2>
            <h3>时间：<?php echo date("Y-m-d H:i:s", strtotime($current_time)); ?></h3>
            <h3>市场均价：<?php echo $current_market_price; ?> ¥</h3>
            <h3>品牌价格：</h3>
            <ul>
                <?php foreach ($current_brand_prices as $brand => $price): ?>
                    <li><a href="黄金购买/黄金购买.php?brand=<?php echo urlencode($brand); ?>"><?php echo $brand; ?></a>：<?php echo $price; ?> ¥</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
