<?php
// 功能：显示聊天内容
session_start();
include_once('config.php');
include_once('class.php');

// 实例化MySQL类
$db = new DB_Connect($db_name, $db_host, $db_user, $db_pass);

function html($text) {
    $text = StripSlashes($text);
    $text = htmlspecialchars($text);                // HTML码转换
    // $text = preg_replace("\r\n", "", $text);     // 换行
    // $text = preg_replace("\n", "", $text);       // 换行
    return $text;
}

$touser = $_POST['touser'];
$content = $_POST['content'];

$touser = iconv('UTF-8', 'UTF-8', $touser);
$content = iconv('UTF-8', 'UTF-8', $content);
$content = html($content);

$time = time();
$homeid = $_SESSION['homeid'];
$fromuser = $_SESSION['username'];

if ($touser != "所有人")
{
    $sql = "SELECT * FROM chat_online WHERE username = '$touser'";
    $res = $db->fetch_rows($sql);
    if (!$res) die("showMessagegg('该用户不存在！');window.inputmess.document.getElementById('touser').value = '所有人';");
}

$sql = "INSERT INTO chat_mess (id, homeid, fromuser, touser, content, time) VALUES (NULL, '$homeid', '$fromuser', '$touser', '$content', '$time')";
$db->query($sql);
?>