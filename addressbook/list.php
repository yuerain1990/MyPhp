<?php
    # code...
    // 有表单数据提交
    // MySQL服务器地址
    $host = "localhost";
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
    $sql = "SELECT * FROM addressbook WHERE user_id > 0 ORDER BY user_id";
    echo $sql . "</br>";
    echo "通讯录 <a href=add.php>添加新联系人</a>";
    if ($result = mysqli_query($con, $sql)) {
        # code...
        var_dump($result);
        /* free result set */
        //mysqli_free_result($result);
        // 循环显示每个联系人资料
        echo "<table>
        <tr>
        <td>姓名</td>
        <td>电子邮件</td>
        <td>性别</td>
        <td>出生日期</td>
        <td>单位</td>
        <td>地址</td>
        <td>邮编</td>
        <td>MSN</td>
        <td>QQ</td>
        <td>工作电话</td>
        <td>家庭电话</td>
        <td>手机</td>
        <td>操作</td>
        </tr>";
        while ($row = mysqli_fetch_array($result)) {
            # code...
            echo "<tr>
            <td>" . $row['name'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['sex'] . "</td>
            <td>" . $row['birthday'] . "</td>
            <td>" . $row['unit'] . "</td>
            <td>" . $row['address'] . "</td>
            <td>" . $row['post'] . "</td>
            <td>" . $row['msn'] . "</td>
            <td>" . $row['qq'] . "</td>
            <td>" . $row['office_phone'] . "</td>
            <td>" . $row['home_phone'] . "</td>
            <td>" . $row['mobile_phone'] . "</td>
            <td><a href='edit.php?user_id=" . $row['user_id'] . "'>编辑</a>
            <a href='del.php?user_id=" . $row['user_id'] . "'>删除</a></td>
            </tr>";
        }
        echo "</table>";
    } else { //没哟有效记录
        echo "<p>通讯录内还没有联系人！</p>";
    }
    mysqli_close($con);
?>