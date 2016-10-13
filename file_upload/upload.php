<?php
/*
    if ($_POST['ok']) {
        # code...
        $uploaddir = 'E:/software/phptool/Apache24/htdocs/my_file/MyPhp/file_upload/'; // 需要写完整路径 相对路径未研究
        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

        if (is_uploaded_file($_FILES['userfile']['tmp_name']) && move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            # code...
            echo "文件已经上传";
        } else {
            # code...
            echo "文件上传处理出错";
        }
    } else {
        # code...
?>
<!-- 单个文件上传 -->
<form name="up" action="upload.php" enctype="multipart/form-data" method="post">
<input name="userfile" type="file" size="60" />
<input name="ok" type="submit" value="上传" />
</form> 

<?php
    }
*/
?>

<?php
    if ($_POST['ok']) {
        # code...
        $uploaddir = 'E:/software/phptool/Apache24/htdocs/my_file/MyPhp/file_upload/'; // 需要写完整路径 相对路径未研究
        // $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

        // if (is_uploaded_file($_FILES['userfile']['tmp_name']) && move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        //     # code...
        //     echo "文件已经上传";
        // } else {
        //     # code...
        //     echo "文件上传处理出错";
        // }
        foreach ($_FILES['file']['error'] as $key => $error) {
            # code...
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['file']['tmp_name'][$key];
                $name = $_FILES['file']['name'][$key];
                $uploadfile = $uploaddir . basename($name);
                move_uploaded_file($tmp_name, $uploadfile);
                echo $name . "上传成功</br>";
            }
        }
    } else {
        # code...
?>
<!-- 多个文件上传 -->
<form name="up" action="upload.php" enctype="multipart/form-data" method="post">
<input name="file[]" type="file" size="60" />
<input name="file[]" type="file" size="60" />
<input name="file[]" type="file" size="60" />
<input name="ok" type="submit" value="上传" />
</form> 

<?php
    }
?>