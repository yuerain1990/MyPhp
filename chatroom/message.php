<?php
// 功能：返回用户浏览器的信息
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
session_start();

include_once('config.php');
include_once('class.php');

// 实例化MySQL类
$db = new DB_Connect($db_name, $db_host, $db_user, $db_pass);

$id = (int)$_COOKIE['id'];

if ($id == '') {
    $sql = "SELECT max(id) as sid FROM chat_mess";
    $data = $db->fetch_rows($sql);
    $id = $data['sid'];
    setcookie('id', $id);
}

$sql = "SELECT * FROM chat_mess WHERE id > '{$id}' and homeid = '{$_SESSION['homeid']}'";
$row = $db->fetch_list($sql);
$count = $db->get_num();
$end = @end($row);

if ($count > 0) {
    setcookie("id", $end[0]);
    for ($i=0; $i < $count; $i++) { 
        $row[$i]['time'] = date("H:i:s", $row[$i]['time']);
        echo "writeMessage('{$row[$i]['content']}','{$row[$i]['fromuser']}','{$row[$i]['touser']}','{$row[$i]['time']}'";
    }
}
?>