<?php
include '../共用资源/session_connect.php';
include '../共用资源/会话管理.php';
include '../共用资源/数据库连接.php';
checkUserSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $avatar_id = $_POST['avatar_id'];
    $target_dir = "../共用资源/图片/头像/用户/";
    $target_file = $target_dir . "用户头像" . $avatar_id . ".jpg";

    // 检查是否上传了新文件
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        // 删除旧文件
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        // 保存新文件
        move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file);
    }

    // 更新用户其他信息
    $nickname = $_POST['nickname'];
    $realname = $_POST['realname'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $location = $_POST['location'];
    $user_id = $_SESSION['user_id'];

    // 删除旧信息
    $delete_sql = "UPDATE 用户信息 SET 昵称 = NULL, 真实姓名 = NULL, 手机号 = NULL, 出生日期 = NULL, 性别 = NULL, 所在地区 = NULL WHERE 用户ID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 插入新信息
    $update_sql = "UPDATE 用户信息 SET 昵称 = ?, 真实姓名 = ?, 手机号 = ?, 出生日期 = ?, 性别 = ?, 所在地区 = ? WHERE 用户ID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssi", $nickname, $realname, $phone, $birthdate, $gender, $location, $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // 更新会话中的用户信息
    $_SESSION['user_info'] = [
        '头像' => $avatar_id,
        '昵称' => $nickname,
        '真实姓名' => $realname,
        '手机号' => $phone,
        '出生日期' => $birthdate,
        '性别' => $gender,
        '所在地区' => $location,
        '用户ID' => $user_id,
        '角色' => $_SESSION['user_info']['角色']
    ];

    header("Location: 用户信息界面.php?" . time());
    exit();
}
?>
