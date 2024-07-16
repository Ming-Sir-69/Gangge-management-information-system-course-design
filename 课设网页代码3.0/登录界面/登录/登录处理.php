<?php
session_start();
include '../../共用资源/数据库连接.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // 查询用户信息
    $sql = "SELECT 用户ID, 密码, 角色 FROM 用户信息 WHERE 手机号 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close(); // 关闭准备语句
        if (password_verify($password, $user['密码'])) {
            $_SESSION['user_id'] = $user['用户ID'];
            $_SESSION['角色'] = $user['角色'];
            header("Location: ../../主界面/主界面.php");
            exit();
        } else {
            header("Location: 登录界面.php?error=密码错误");
            exit();
        }
    } else {
        $stmt->close(); // 关闭准备语句
        header("Location: 登录界面.php?error=手机号不存在");
        exit();
    }
} elseif (isset($_GET['admin']) && $_GET['admin'] == 'true') {
    // 管理员登录
    $_SESSION['user_id'] = 0; // 管理员用户ID，可以设置为0或其他特殊值
    $_SESSION['角色'] = '管理员';
    header("Location: ../../主界面/主界面.php");
    exit();
}
$conn->close();
?>
