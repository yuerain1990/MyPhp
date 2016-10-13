<?php
    // MySQL服务器地址
    $host = "localhost:3306";
    // MySQL用户名
    $user = "root";
    // MySQL密码
    $pass = "123456";
    // 要使用的数据
    $dbname = "phpbook";
    // 建立和数据库的连接
    $con = mysqli_connect($host, $user, $pass, $dbname);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed:%s\n", mysqli_connect_error());
        // exit();
    }
    if (!$con) {
        echo "Error:unable to connect to MySQL.";
    } else {
        echo "connect success</br>";
    }
    // 设置编码
    mysqli_query($con, "set names utf8");
    if ($_POST['user_id']) {
        # code...
        echo "进入POST</br>";
        // 有表单数据提交
        // 执行SQL查询，读出用户表
        $sql = "SELECT * FROM addressbook";
        echo $sql . "</br>";
        if ($result = mysqli_query($con, $sql)) {
            # code...
            echo "联系人：" . $_POST['name'] . "已修改。";
            /* free result set */
            //mysqli_free_result($result);
        } else {
            echo "add data failed</br>";
        }
        mysqli_close($con);
    } elseif($_GET['user_id']) {
    // else {
        # code...
        echo "进入GET</br>";
        $sql = "DELETE from addressbook WHERE user_id='" . $_GET['user_id'] . "'"; 
        echo $sql . "</br>";
        $result = mysqli_query($con, $sql);
        $num = mysqli_num_rows($result);
        var_dump($result);
        var_dump($num);
        if ($result) {
            # code...
            echo "联系人已删除。";
        } else
        {
            echo "该联系人不存在。";
        }
/*    } else {
?>
<html>
<head>
<title>请输入要删除的用户编号</title>
</head>
<body>
<form name="form" action="del.php" method="get">
<table border="1" cellpadding="3" cellspacing="1">
<tr><td>用户编号</td><td><input type="text" name="user_id" size="60"></td><tr>
<tr><td colspan="2" align="center"><input type="submit" name="ok" value="提交"><input type="reset" name="cancel" value="重填"></td><tr>
</form>
</body>
</html>

<?php*/
    }
?>