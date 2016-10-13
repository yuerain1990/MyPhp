<?php
    if ($_POST['name']) {
        # code...
        // 有表单数据提交
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
        // 执行SQL查询，读出用户表
        $sql = "INSERT INTO addressbook (email, name, sex, birthday, unit, address, post, msn, qq, office_phone, home_phone, mobile_phone) VALUES ('" . $_POST['email'] . "', '" . $_POST['name'] . "', '" . $_POST['sex'] . "', '" . $_POST['birthday'] . "', '" . $_POST['unit'] . "', '" . $_POST['address'] . "', '" . $_POST['post'] . "', '" . $_POST['msn'] . "', '" . $_POST['qq'] . "', '" . $_POST['office_phone'] . "', '" . $_POST['home_phone'] . "', '" . $_POST['mobile_phone'] . "')";
        echo $sql . "</br>";
        if ($result = mysqli_query($con, $sql)) {
            # code...
            echo "联系人：" . $_POST['name'] . "已添加。";
            /* free result set */
            //mysqli_free_result($result);
        } else {
            echo "add data failed</br>";
        }
        mysqli_close($con);
    } else {
        # code...
        echo "获取失败";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/1999/html">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb8030" />
<title>添加联系人</title>
</head>

<body>
    <form name="form" action="add.php" method="post">
    <table border="1" cellpadding="3" cellspacing="1">
    <tr><td>姓名</td><td><input type="text" name="name" size="60"></td><tr>
    <tr><td>电子邮件</td><td><input type="text" name="email" size="60"></td><tr>
    <tr><td>性别</td><td><select name="sex"><option value="1">男</option><option value="2">女</option></td><tr>
    <tr><td>出生日期</td><td><input type="text" name="birthday" size="60"></td><tr>
    <tr><td>单位</td><td><input type="text" name="unit" size="60"></td><tr>
    <tr><td>地址</td><td><input type="text" name="address" size="60"></td><tr>
    <tr><td>邮编</td><td><input type="text" name="post" size="60"></td><tr>
    <tr><td>MSN</td><td><input type="text" name="msn" size="60"></td><tr>
    <tr><td>QQ</td><td><input type="text" name="qq" size="60"></td><tr>
    <tr><td>工作电话</td><td><input type="text" name="office_phone" size="60"></td><tr>
    <tr><td>家庭电话</td><td><input type="text" name="home_phone" size="60"></td><tr>
    <tr><td>手机</td><td><input type="text" name="mobile_phone" size="60"></td><tr>
    <tr><td colspan="2" align="center"><input type="submit" name="ok" value="提交"><input type="reset" name="cancel" value="重填"></td><tr>
    </table>
    </form>
</body>
</html>

<?php
    }
?>