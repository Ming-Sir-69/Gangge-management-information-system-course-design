<?php
include 'session_connect.php';

function checkUserSession() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../登录界面/登录/登录界面.php");
        exit();
    }
}

function checkAdminSession() {
    return (isset($_SESSION['角色']) && $_SESSION['角色'] == '管理员');
}
?>
