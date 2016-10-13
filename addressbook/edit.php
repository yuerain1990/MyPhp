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
        echo "进入POST";
        // 有表单数据提交
        // 执行SQL查询，读出用户表
        $sql = "UPDATE addressbook SET 
        email='" . $_POST['email'] . "', 
        name='" . $_POST['name'] . "', 
        sex='" . $_POST['sex'] . "', 
        birthday='" . $_POST['birthday'] . "', 
        unit='" . $_POST['unit'] . "', 
        address='" . $_POST['address'] . "', 
        post='" . $_POST['post'] . "', 
        msn='" . $_POST['msn'] . "', 
        qq='" . $_POST['qq'] . "', 
        office_phone='" . $_POST['office_phone'] . "', 
        home_phone='" . $_POST['home_phone'] . "', 
        mobile_phone='" . $_POST['mobile_phone'] . "' 
        WHERE user_id='" . $_POST['user_id'] . "'";

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
        echo "进入GET";
        $sql = "SELECT * from addressbook WHERE user_id='" . $_GET['user_id'] . "'"; 
        // $sql = "SELECT * from addressbook WHERE user_id='1'"; 
        echo $sql . "</br>";
        $result = mysqli_query($con, $sql);
        if ($result and mysqli_num_rows($result) != 0) {
            # code...
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/1999/html">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb8030" />
<title>修改联系人资料</title>
</head>

<body>
    <form name="form" action="edit.php" method="post">
    <input name="user_id" type="hidden" value="<?php echo $row['user_id']; ?>" />
    <table border="1" cellpadding="3" cellspacing="1">
    <tr><td>姓名</td><td><input type="text" name="name" size="60" value="<?php echo $row['name']; ?>"></td><tr>
    <tr><td>电子邮件</td><td><input type="text" name="email" size="60" value="<?php echo $row['email']; ?>"></td><tr>
    <tr><td>性别</td><td><select name="sex"><option value="1" <?php if($row['sex'] == 1){echo "selected";} ?>>男</option><option value="2" <?php if($row['sex'] == 2){echo "selected";} ?>>女</option></td><tr>
    <tr><td>出生日期</td><td><input type="text" name="birthday" size="60" value="<?php echo $row['birthday']; ?>"></td><tr>
    <tr><td>单位</td><td><input type="text" name="unit" size="60" value="<?php echo $row['unit']; ?>"></td><tr>
    <tr><td>地址</td><td><input type="text" name="address" size="60" value="<?php echo $row['address']; ?>"></td><tr>
    <tr><td>邮编</td><td><input type="text" name="post" size="60" value="<?php echo $row['post']; ?>"></td><tr>
    <tr><td>MSN</td><td><input type="text" name="msn" size="60" value="<?php echo $row['msn']; ?>"></td><tr>
    <tr><td>QQ</td><td><input type="text" name="qq" size="60" value="<?php echo $row['qq']; ?>"></td><tr>
    <tr><td>工作电话</td><td><input type="text" name="office_phone" size="60" value="<?php echo $row['office_phone']; ?>"></td><tr>
    <tr><td>家庭电话</td><td><input type="text" name="home_phone" size="60" value="<?php echo $row['home_phone']; ?>"></td><tr>
    <tr><td>手机</td><td><input type="text" name="mobile_phone" size="60" value="<?php echo $row['mobile_phone']; ?>"></td><tr>
    <tr><td colspan="2" align="center"><input type="submit" name="ok" value="提交"><input type="reset" name="cancel" value="重填"></td><tr>
    </table>
    </form>
</body>
</html>

<?php
        }
        else
        {
            echo "该联系人不存在";
        }
 /*   } else {
?>
<html>
<head>
<title>请输入要修改的用户编号</title>
</head>
<body>
<form name="form" action="edit.php" method="get">
<table border="1" cellpadding="3" cellspacing="1">
<tr><td>用户编号</td><td><input type="text" name="user_id" size="60"></td><tr>
<tr><td colspan="2" align="center"><input type="submit" name="ok" value="提交"><input type="reset" name="cancel" value="重填"></td><tr>
</form>
</body>
</html>

<?php*/
    }
?>