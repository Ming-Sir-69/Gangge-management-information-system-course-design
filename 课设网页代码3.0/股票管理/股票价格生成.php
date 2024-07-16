<?php
include '../共用资源/数据库连接.php';

// 设置新的参数
$mu = 0.04; // 调整后的预期回报率
$sigma = 0.1; // 调整后的波动率

// 获取当前时间
$current_time = new DateTime();
$current_hour = (int)$current_time->format('H');
$current_minute = (int)$current_time->format('i');

// 获取所有股票ID和最新价格
$query = "SELECT 股票ID, 当前价格, 更新时间 FROM 股票信息";
$result = $conn->query($query);
if (!$result) {
    die("查询失败: " . $conn->error);
}

$stocks = [];
while ($row = $result->fetch_assoc()) {
    $stocks[] = $row;
}

// 股票价格生成和更新
foreach ($stocks as $stock) {
    $stockID = $stock['股票ID'];
    $lastPrice = $stock['当前价格']; // 保存上次查询的价格
    $lastUpdate = new DateTime($stock['更新时间']);
    $interval = $lastUpdate->diff($current_time);
    $days = (int)$interval->format('%a');
    $total_changes = 0;

    // 判断变动次数
    if ($days <= 1) {
        $last_hour = (int)$lastUpdate->format('H');
        $last_minute = (int)$lastUpdate->format('i');
        
        if ($last_hour < 9 || ($last_hour == 9 && $last_minute < 30)) {
            if ($current_hour < 9 || ($current_hour == 9 && $current_minute < 30)) {
                $total_changes = 0;
            } elseif ($current_hour < 13) {
                $total_changes = 1;
            } elseif ($current_hour < 15) {
                $total_changes = 2;
            } else {
                $total_changes = 2;
            }
        } elseif ($last_hour < 13) {
            if ($current_hour < 13) {
                $total_changes = 0;
            } elseif ($current_hour < 15) {
                $total_changes = 1;
            } else {
                $total_changes = 1;
            }
        } elseif ($last_hour < 15) {
            $total_changes = 0;
        }
    } else {
        if ($current_hour < 9 || ($current_hour == 9 && $current_minute < 30)) {
            $total_changes = 2 * $days;
        } elseif ($current_hour < 13) {
            $total_changes = 2 * $days + 1;
        } else {
            $total_changes = 2 * $days + 2;
        }
    }

    // 进行价格计算
    $dt = $total_changes / 252;
    $currentPrice = $lastPrice;
    for ($i = 0; $i < $total_changes; $i++) {
        $random = mt_rand() / mt_getrandmax();
        $currentPrice *= exp(($mu - 0.5 * pow($sigma, 2)) * $dt + $sigma * sqrt($dt) * $random);
    }

    // 更新当前价格和上次查询价格
    $current_time_str = $current_time->format('Y-m-d H:i:s');
    $query = "UPDATE 股票信息 SET 当前价格 = ?, 上次查询价格 = ?, 更新时间 = ? WHERE 股票ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ddsi", $currentPrice, $lastPrice, $current_time_str, $stockID);
    if (!$stmt->execute()) {
        die("更新失败: " . $stmt->error);
    }
}

// 获取开盘价和收盘价
foreach ($stocks as $stock) {
    $stockID = $stock['股票ID'];
    $query = "SELECT 成交单价, MIN(创建时间) AS 最早时间, MAX(创建时间) AS 最晚时间 
              FROM 用户股票持有 
              WHERE 股票ID = ? AND DATE(创建时间) = DATE(?) 
              GROUP BY 股票ID";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $stockID, $current_time_str);
    $stmt->execute();
    $stmt->bind_result($price, $earliest_time, $latest_time);
    if ($stmt->fetch()) {
        $open_price = $price;
        $stmt->fetch(); // Fetch second result for closing price
        $close_price = $price;

        // 插入到股票历史价格表
        $query = "INSERT INTO 股票历史价格 (股票ID, 日期, 开盘价, 收盘价) VALUES (?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE 开盘价 = VALUES(开盘价), 收盘价 = VALUES(收盘价)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isdd", $stockID, $current_time_str, $open_price, $close_price);
        if (!$stmt->execute()) {
            die("插入失败: " . $stmt->error);
        }
    }
    $stmt->close();
}

// 计算最大回撤
foreach ($stocks as $stock) {
    $stockID = $stock['股票ID'];
    $query = "SELECT 开盘价, 收盘价 FROM 股票历史价格 WHERE 股票ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $stockID);
    $stmt->execute();
    $result = $stmt->get_result();

    $prices = [];
    while ($row = $result->fetch_assoc()) {
        $prices[] = $row;
    }
    $stmt->close();

    $max_open_price = max(array_column($prices, '开盘价'));
    $max_close_price = max(array_column($prices, '收盘价'));
    $max_price = max($max_open_price, $max_close_price);

    $min_open_price = min(array_column($prices, '开盘价'));
    $min_close_price = min(array_column($prices, '收盘价'));
    $min_price = min($min_open_price, $min_close_price);

    if ($max_price == 0) {
        $max_drawdown = 0;
    } else {
        $max_drawdown = (($max_price - $min_price) / $max_price) * 100;
    }

    // 更新最大回撤到股票信息表
    $query = "UPDATE 股票信息 SET 最大回撤 = ? WHERE 股票ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("di", $max_drawdown, $stockID);
    if (!$stmt->execute()) {
        die("更新失败: " . $stmt->error);
    }
    $stmt->close();
}

// 计算涨跌幅
foreach ($stocks as $stock) {
    $stockID = $stock['股票ID'];
    $query = "SELECT MAX(日期) AS 最新日期 FROM 股票历史价格 WHERE 股票ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $stockID);
    $stmt->execute();
    $stmt->bind_result($latest_date_str);
    $stmt->fetch();
    $latest_date = new DateTime($latest_date_str);
    $start_date = clone $latest_date;
    $start_date->modify('-15 days');
    $stmt->close();

    $query = "SELECT 开盘价, 收盘价 FROM 股票历史价格 WHERE 股票ID = ? AND 日期 BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $start_date_str = $start_date->format('Y-m-d');
    $stmt->bind_param("iss", $stockID, $start_date_str, $latest_date_str);
    $stmt->execute();
    $result = $stmt->get_result();

    $prices = [];
    while ($row = $result->fetch_assoc()) {
        $prices[] = $row;
    }
    $stmt->close();

    $open_prices = array_column($prices, '开盘价');
    $close_prices = array_column($prices, '收盘价');

    $max_close_price = max($close_prices);
    $min_open_price = min($open_prices);

    if ($min_open_price == 0) {
        $涨跌幅 = 0;
    } else {
        $涨跌幅 = (($max_close_price - $min_open_price) / $min_open_price) * 100;
    }

    // 更新涨跌幅到股票信息表
    $query = "UPDATE 股票信息 SET 涨跌幅 = ? WHERE 股票ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("di", $涨跌幅, $stockID);
    if (!$stmt->execute()) {
        die("更新失败: " . $stmt->error);
    }
    $stmt->close();
}

// 返回最新的价格信息和上次记录的价格信息
return [
    'last_record_time' => $lastUpdate->format('Y-m-d H:i:s'),
    'current_time' => $current_time->format('Y-m-d H:i:s')
];
?>
