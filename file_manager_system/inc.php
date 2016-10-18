<?php
    // 功能：公共的系统配置文件 
    // 包含文件类
    require 'cls_file.php';

    // 包含模板类文件
    require '../include/libs/Smarty.class.php';

    // 创建模板类实例
    $smarty = new Smarty;

    // 编译
    $smarty->compile_check = true;

    // 不提示调试信息
    $smarty->debugging = false;

    // 创建文件类实例
    $file = new file();

    // 每页文件列表记录数
    $perpage = 30;
?>