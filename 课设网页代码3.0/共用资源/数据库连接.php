<?php
// 数据库配置信息
$servername = "localhost"; // 数据库服务器地址
$username = "root";        // 数据库用户名
$password = "";            // 数据库密码
$dbname = "铭中注定2.5";  // 数据库名

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 连接保持，不关闭
?>
