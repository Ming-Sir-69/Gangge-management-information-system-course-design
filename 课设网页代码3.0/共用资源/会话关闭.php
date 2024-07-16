<?php
session_start();
session_unset();
session_destroy();
header("Location: ../登录界面/登录/登录界面.php");
exit();
?>
