<?php
// 功能：用户退出登陆
session_start();
session_destroy();

echo "<script>alert('已经退出！');top.location.href = 'index.php';</script>";
?>