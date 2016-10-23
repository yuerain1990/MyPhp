<?php
    // 功能：设置文件管理路径的表单
    // 包含公共文件
    require("inc.php");

    if ($_POST['step'] <> '2') {
        # code...
        // 设置表单页面
        // 取得目录列表
        $dirs = $file->getDirs(".");

        if (file_exists('upload_dir.php')) {
            // 如果用户已经设置过文件管理目录
            include('upload_dir.php');
            // 给模板文件变量赋值
            $smarty->assign('act', '文件上传目录已设置为' . $upload_path);
        } else {
            # code...
            // 用户未设置过
            $smarty->assign('act', '设置文件上传目录');
        }

        // 给模板变量赋值
        $smarty->assign("html_title", "设置文件上传、管理目录");
        $smarty->assign("dirs", $dirs);

        // 调用模板页显示
        $smarty->display('setdir.htm');
    } else {
        # code...
        // 表单提交处理
        // 保存文件设置目录
        if ($file->SaveUploadDir($_POST['dir'])) {
            # code...
            $smarty->assign('text', '文件上传目录设置为' . $_POST['dir'] . '<br>已保存成功。');
        } else {
            # code...
            // 文件设置目录失败
            $smarty->assign('text', '文件上传目录设置失败！');
        }   

        // 给模板变量赋值
        $smarty->assign('html_title', '文件管理');

        // 调用模板页显示
        $smarty->display('index.htm');
    }
?>