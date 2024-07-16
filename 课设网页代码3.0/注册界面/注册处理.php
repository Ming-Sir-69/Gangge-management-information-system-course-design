<?php
include '../共用资源/数据库连接.php';
session_start();

// 获取表单数据
$realname = $_POST['realname'];
$nickname = $_POST['nickname'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$province = $_POST['province'];
$city = $_POST['city'];
$district = $_POST['district'];
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];
$avatar = $_POST['avatar']; // 头像 ID 或者 custom
$role = $_POST['role'];

// 定义一个函数来显示弹窗消息
function showMessage($message) {
    echo "<script>
        alert('$message');
        setTimeout(function() {
            window.history.back();
        }, 3000);
    </script>";
    exit;
}

// 检查昵称是否已存在
$sql = "SELECT * FROM 用户信息 WHERE 昵称 = '$nickname'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    showMessage("昵称已存在，请选择其他昵称。");
}

// 检查手机号码是否已存在
$sql = "SELECT * FROM 用户信息 WHERE 手机号 = '$phone'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    showMessage("手机号码已存在，请使用其他手机号码。");
}

// 检查密码和确认密码是否一致
if ($password !== $confirm_password) {
    showMessage("密码和确认密码不一致。");
}

// 对密码进行加密
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// 查询最大用户ID
$sql = "SELECT MAX(用户ID) AS max_id FROM 用户信息";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$new_user_id = $row['max_id'] + 1;

// 上传用户头像
if ($avatar === 'custom' && $_FILES['custom_avatar']['error'] == UPLOAD_ERR_OK) {
    $new_avatar_id = $new_user_id + 5;
    $uploadDir = '../共用资源/图片/头像/用户/';
    $uploadFile = $uploadDir . '用户头像' . $new_avatar_id . '.jpg';
    if (move_uploaded_file($_FILES['custom_avatar']['tmp_name'], $uploadFile)) {
        $avatar = $new_avatar_id; // 将头像ID存储为新ID
    } else {
        showMessage("头像上传失败，请重试。");
    }
} else {
    // 使用预设头像时，将头像ID保持原样
    $avatar = intval($avatar);
}

// 插入数据到数据库
$sql = "INSERT INTO 用户信息 (用户ID, 真实姓名, 昵称, 手机号, 密码, 所在地区, 出生日期, 性别, 角色, 头像) VALUES ('$new_user_id', '$realname', '$nickname', '$phone', '$hashed_password', '$province $city $district', '$birthdate', '$gender', '$role', '$avatar')";
if ($conn->query($sql) === TRUE) {
    $_SESSION['user_id'] = $new_user_id;
    $_SESSION['nickname'] = $nickname;
    header("Location: 注册成功展示.php");
    exit();
} else {
    echo "<script>
        alert('注册失败：" . $conn->error . "');
        setTimeout(function() {
            window.history.back();
        }, 3000);
    </script>";
}

$conn->close();
?>
