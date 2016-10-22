<?php
// 功能：显示在线用户列表
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
session_start();

include_once('config.php');
include_once('class.php');

// 实例化MySQL类
$db = new DB_Connect($db_name, $db_host, $db_user, $db_pass);

$stime = 120;
$username = $_SESSION['username'];
$homeid = $_SESSION['homeid'];
$time = time();
// $ip = getenv("REMOTE_ADDR"); //::1
// $ip = $_SERVER['REMOTE_ADDR']; //::1
// $ip = $_SERVER['SERVER_ADDR']; //::1
$ip = "127.0.0.1";

// 更新自己的时间
$sql = "UPDATE chat_online SET intime = '$time' WHERE username = '$username'";
$db->query($sql);

// 清除过期消息
$stime = $time - $stime;
$sql = "DELETE FROM chat_online WHERE intime < '$stime' and homeid = '$homeid'";
$db->query($sql);

// 读取数据
$sql = "SELECT * FROM chat_online WHERE homeid = '$homeid'";
$data = $db->fetch_list($sql);
$count = $db->get_num();

for ($i=0; $i < $count; $i++) { 
    $online = "<li><a href=\"javascript:;\"onclick=\"parent.getusername(\\'{$data[$i]['username']}\\');return false;\">{$data[$i]['username']}</a></li>";
    echo "writeonline('$online');";
}
?>