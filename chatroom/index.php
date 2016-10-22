<?php
    // 启用session
    session_start();
    // 身份验证
    if ($_SESSION['username'] == '') header("Location:login.php");

    include_once('config.php');
    include_once('class.php');

    // 实例化MySQL类
    $db = new DB_Connect($db_name, $db_host, $db_user, $db_pass);
?>
<html>
<head>
<title></title>
</head>
<body>
<table width="500" align="center" border="1">
<tr>
    <td>请选择房间进入</td>
</tr>
<tr>
    <td>
    <?
    // 列表
    $sql = "SELECT * FROM chat_home";
    $data = $db->fetch_list($sql);
    $count = $db->get_num();
    for ($i=0; $i < $count; $i++) { 
        # code...
        echo '<a href="main.php?id=' . $data[$i]['id'] . '">' . $data[$i]['name'] . '</a>  '; 
    }
    ?>
    </td>
</tr>
</table>
</body>
</html>