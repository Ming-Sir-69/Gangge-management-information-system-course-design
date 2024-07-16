<?php
// 保持session连接状态
session_start();
include('../共用资源/session_connect.php');
include('../共用资源/数据库连接.php');

// 引用股票价格生成脚本
$price_info = include('股票价格生成.php');

// 获取股票详细信息
$query = "SELECT 股票ID, 股票名称, 当前价格, 上次查询价格, 更新时间, 涨跌幅, 最大回撤 FROM 股票信息";
$result = $conn->query($query);
if (!$result) {
    die("查询失败: " . $conn->error);
}

$stocks = [];
while ($row = $result->fetch_assoc()) {
    $stocks[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>股票详情</title>
    <link rel="stylesheet" href="股票管理样式.css">
</head>
<body>
    <div class="top-bar">
        <a href="../主界面/主界面.php" class="back-link">&lt; 返回</a>
        <h1>股票详情</h1>
        <a href="股票详情.php" class="refresh-link">刷新</a>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>股票名称</th>
                    <th>上次查询价格</th>
                    <th>当前价格</th>
                    <th>涨跌幅</th>
                    <th>最大回撤</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stocks)): ?>
                    <tr>
                        <td colspan="5">暂无数据</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($stocks as $stock): ?>
                        <tr>
                            <td>
                                <a href="股票购买/股票购买.php?stock_id=<?php echo urlencode($stock['股票ID']); ?>" class="stock-link">
                                    <?php echo htmlspecialchars($stock['股票名称']); ?>
                                </a>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($stock['上次查询价格']); ?> 
                                <div class="small-font"><?php echo htmlspecialchars($price_info['last_record_time']); ?></div>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($stock['当前价格']); ?> 
                                <div class="small-font"><?php echo htmlspecialchars($price_info['current_time']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($stock['涨跌幅']); ?></td>
                            <td><?php echo htmlspecialchars($stock['最大回撤']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
