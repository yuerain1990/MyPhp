<?php
/****
 * 类名DB_Connect
 * 构造方法
 * DB_Connect(库名, 主机名, 用户名, 密码);
 */
class DB_Connect{
    var $link;
    var $num_rows;
    var $error;

    function DB_Connect($db, $host="localhost", $user="root", $pass="123456")
    {
        // @是忽略错误
        $this->link = @mysqli_connect($host, $user, $pass, $db) or die("Could not connect : " . mysqli_connect_error());
        @mysqli_query($this->link, "set names utf8");
        $this->error = mysqli_error($this->link);
    }

    function closesql()
    {
        @mysqli_close($this->link);
    }

    function fetch_list($sql, $type=1)
    {
        if($type == 1) $mysqli_type = MYSQLI_BOTH;
        if($type == 2) $mysqli_type = MYSQLI_ASSOC;
        if($type == 3) $mysqli_type = MYSQLI_NUM;
        $result = mysqli_query($this->link, $sql);
        $this->error = mysqli_error($this->link);
        $this->num_rows = mysqli_num_rows($result);
        if ($result) {
            # code...
            while ($line = @mysqli_fetch_array($result, $mysqli_type)) {
                # code...
                $rows[] = $line;
            }
            @mysqli_free_result($result);
            return $rows;
        } else {
            # code...
            return false;
        }
    }

    function fetch_rows($sql, $type=1)
    {
        if($type == 1) $mysqli_type = MYSQLI_BOTH;
        if($type == 2) $mysqli_type = MYSQLI_ASSOC;
        if($type == 3) $mysqli_type = MYSQLI_NUM;
        $result = mysqli_query($this->link, $sql);
        $this->error = mysqli_error($this->link);
        $this->num_rows = mysqli_num_rows($result);
        if ($result) {
            # code...
            $rows = @mysqli_fetch_array($result, $mysqli_type);
            @mysqli_free_result($result);
            return $rows;
        } else {
            # code...
            return false;
        }
    }

    function query($sql)
    {
        $result = mysqli_query($this->link, $sql);
        $this->num_rows = @mysqli_affected_rows($this->link);
        $this->error = mysqli_error($this->link);
        return $result;
    }

    function get_num()
    {
        return $this->num_rows;
    }

    function get_error()
    {
        return $this->error;
    }
}
?>