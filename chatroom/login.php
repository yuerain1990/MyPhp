<?php
    // 启用session
    session_start();
    // 包含公共配置文件
    include_once('config.php');
    include_once('class.php');
    
    // 实例化MySQL类
    $db = new DB_Connect($db_name, $db_host, $db_user, $db_pass);

    if ($_POST['username'] != '') {
        # code...
        // 取得表单传递过来的值
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // 查询数据库
        $sql = "SELECT * FROM chat_user WHERE username='$user' AND password='$pass'";
        $data = $db->fetch_rows($sql);
        if ($db->get_nums() <> 0) {
            # code...
            // 查询到相关记录
            $_SESSION['username'] = $user;
            // 跳转到聊天室首页
            header("Location:index.php");
        } else {
            # code...
            // 用户名密码不正确，重新登陆
            header("Location:login.php");
        }
    } else {
        # code...
        // 登陆表单显示
?>

<html>
<head>
<title></title>
</head>
<body>
<form method="post" action="login.php">
用户名：<input name="username" type="text" size="10">
密码：<input name="password" type="text" size="10">
<input name="submit" type="submit" value="登陆">
</form>
</body>
</html>

<?php
    }
?>