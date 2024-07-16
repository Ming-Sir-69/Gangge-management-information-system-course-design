<?php
// 保持session连接状态
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../共用资源/session_connect.php');
include('../共用资源/会话管理.php');

// 检查用户是否已登录
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('用户未登录'); window.location.href='../登录界面/登录界面.php';</script>";
    exit();
}

// 运行总资产处理文件
include('总资产处理.php');

// 获取POST中的资产信息
$bankAssets = $_POST['bank_assets'];
$goldAssets = $_POST['gold_assets'];
$stockAssets = $_POST['stock_assets'];
$updateTime = $_POST['update_time'];

// 计算总资产
$totalAssets = $bankAssets + $goldAssets + $stockAssets;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>总资产</title>
    <link rel="stylesheet" href="总资产样式.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="top-bar">
        <a href="../主界面/主界面.php" class="back-link">&lt; 返回</a>
        <h1>总资产</h1>
        <div class="update-time">更新时间：<?php echo htmlspecialchars($updateTime); ?></div>
        <a href="总资产界面.php" class="refresh-link">刷新</a>
    </div>
    <div class="container">
        <div class="total-assets">
            <h2>总资产: ￥<?php echo htmlspecialchars(number_format($totalAssets, 2)); ?></h2>
            <canvas id="assetsChart"></canvas>
        </div>
        <div class="asset-card" onclick="window.location.href='../银行转账/转账页面.php'">
            <h2>银行资产</h2>
            <p>￥<?php echo htmlspecialchars(number_format($bankAssets, 2)); ?></p>
        </div>
        <div class="asset-card" onclick="window.location.href='../黄金管理/黄金详情.php'">
            <h2>黄金资产</h2>
            <p>￥<?php echo htmlspecialchars(number_format($goldAssets, 2)); ?></p>
        </div>
        <div class="asset-card" onclick="window.location.href='../股票管理/股票详情.php'">
            <h2>股票资产</h2>
            <p>￥<?php echo htmlspecialchars(number_format($stockAssets, 2)); ?></p>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('assetsChart').getContext('2d');
        const assetsChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['银行资产', '黄金资产', '股票资产'],
                datasets: [{
                    data: [<?php echo $bankAssets; ?>, <?php echo $goldAssets; ?>, <?php echo $stockAssets; ?>],
                    backgroundColor: ['#4CAF50', '#FFD700', '#1E90FF']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '￥' + context.raw.toLocaleString();
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
