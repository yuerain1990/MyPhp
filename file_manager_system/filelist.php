<?php
    // 功能：文件列表页
    // 包含公共文件
    require("inc.php");

    // 如果有POST提交过来的action域的值，则赋值$action；如果没有则用GET方式提交过来的值，如果都没有，默认值设置为"dirlist"
    $action = ($_POST['action'] ? $_POST['action'] : ($_GET['action'] ? $_GET['action'] : "dirlist"));

    if (file_exists('upload_dir.php')) {
        // 如果存在upload_dir.php文件，就包含进来
        include('upload_dir.php');

        // 为当前目录名赋值
        $cur_dir = $_GET['dir'] ? $_GET['dir'] : $upload_path . $_GET['dir'];

        switch ($action) {
            // 默认主页，当前文件夹内文件列表
            case "dirlist":
                // 取得当前目录下的路径信息，下级文件夹或文件
                $paths = $file->getPath($cur_dir);

                // 为模板页变量赋值
                $smarty->assign('html_title', '文件列表');
                $smarty->assign('text', '当前目录为' . $cur_dir);
                $smarty->assign('paths', $paths);
                $smarty->assign('cur_dir', $cur_dir);

                // 显示模板页
                $smarty->display('filelist.htm');
                break;
            // 上传文件
            case "upload":
                if ($file->UploadFile('filename', $cur_dir)) {
                    // 文件上传成功
                    $smarty->assign('text', '文件' . $_FILES['filename']['name'] . '已成功上传至' . $cur_dir . '目录。');
                } else {
                    $smarty->assign('text', '文件上传失败！');
                }
                // 为模板页变量赋值
                $smarty->assign('html_title', "文件列表");

                // 取得路径信息
                $paths = $file->getPath($cur_dir);
                $smarty->assign('paths', $paths);
                $smarty->assign('cur_dir', $cur_dir);

                // 显示模板页
                $smarty->display('filelist.htm');
                break;
            // 编辑文本文件
            case "edit":
                if ($_POST['step'] <> 2) {
                    // 取得文件内容
                    $content = file_get_contents($cur_dir . '/' . $_GET['file']);

                    // 为模板页变量赋值
                    $smarty->assign('html_title', "编辑文件");
                    $smarty->assign('text', '文件名：' . $cur_dir . '/' . $_GET['file']);
                    $smarty->assign('content', $content);

                    // 显示模板页
                    $smarty->display('fileedit.htm');
                } else {
                    // 关闭魔术引导开关，阻止PHP自动提交过来的引号等特殊符号添加斜线，以免额外增加文件大小
                    // 写入文件
                    file_put_contents($cur_dir . '/' . $_GET['file'], (get_magic_quotes_gpc() ? stripslashes($_POST['content']) : $_POST['content']));
                    
                    // 取得路径信息
                    $paths = $file->getPath($cur_dir);

                    // 为模板页变量赋值
                    $smarty->assign('html_title', "文件列表");
                    $smarty->assign('text', '文件' . $_GET['file']  . '已保存。 <br> 当前目录为' . $cur_dir);
                    $smarty->assign('paths', $paths);
                    $smarty->assign('cur_dir', $cur_dir);

                    // 显示模板页
                    $smarty->display('filelist.htm');
                }
                break;
            // 在指定目录中创建新文件夹
            case "newdir":
                // 创建新文件夹
                $file->NewDir($cur_dir . '/' . $_POST['dirname']);

                // 取得路径信息
                $paths = $file->getPath($cur_dir);

                // 为模板页变量赋值
                $smarty->assign('html_title', "文件列表");
                $smarty->assign('text', '文件夹' . $_POST['dirname'] . '已创建。<br>当前目录为' . $cur_dir);
                $smarty->assign('paths', $paths);
                $smarty->assign('cur_dir', $cur_dir);

                // 显示模板页
                $smarty->display('filelist.htm');
                break;
            // 删除文件或文件夹
            case "newdir":
                if ($_GET['type'] == 'file') {
                    // 删除文件
                    $file->DelFile($_GET['name']);
                } elseif($_GET['type'] == 'dir') {
                    // 删除文件夹
                    $file->DelDir($_GET['name']);
                }

                // 取得路径信息
                $paths = $file->getPath($cur_dir);

                // 为模板页变量赋值
                $smarty->assign('html_title', "文件列表");
                $smarty->assign('text', '文件/文件夹已删除。<br>当前目录为' . $cur_dir);
                $smarty->assign('paths', $paths);
                $smarty->assign('cur_dir', $cur_dir);

                // 显示模板页
                $smarty->display('filelist.htm');
                break;
            // 压缩文件夹
            case "zip":
                // 包含如ZIP包类
                include_once("cls_zip.php");

                // 创建一个ZIP实例
                $archive = new PHPZip();
                $zip_name = str_replace("/", ".", $cur_dir);

                // 将该目录压缩zip包保存至zip目录下，文件名为目录名（用"."取代目录结构的"/"）和日期时间的结合
                $archive->Zip($cur_dir, 'zip/' . $zip_name . '.' . date("Ymd.His") . '.zip');

                // 取得路径信息
                $paths = $file->getPath($cur_dir);

                // 为模板页变量赋值
                $smarty->assign('html_title', "压缩文件");
                $smarty->assign('text', '压缩文件' . 'zip/' . $zip_name . date("Tmd.His") . '.zip' . '已创建。<br>当前目录为' . $cur_dir);
                $smarty->assign('paths', $paths);
                $smarty->assign('cur_dir', $cur_dir);

                // 显示模板页
                $smarty->display('filelist.htm');
                break;
            // 解压zip包为文件
            case "unzip":
                // 包含如ZIP包类
                include_once("cls_zip.php");

                @set_time_limit("0");

                // 创建新的解压ZIP包类实例
                $archive = new un_Zip();
                //将zip文件压缩至unzip目录下
                $result = $archive->Extract($cur_dir . '/' . $_GET['file'], 'unzip/' . $_GET['file']);
                if ($result == -1) {
                    // 解压失败
                    $smarty->assign('text', '文件 解压错误！');
                } else {
                    // 解压成功
                    $smarty->assign('text', "文件解压完成，共建立$archive->total_folders个目录，$archive->total_files个文件。<br>当前目录为" . "unzip/" . $_GET['file']);
                }

                // 取得路径信息
                $paths = $file->getPath('unzip/' . $_GET['file']);

                // 为模板页变量赋值
                $smarty->assign('html_title', "文件列表");
                $smarty->assign('paths', $paths);
                $smarty->assign('cur_dir', 'unzip/' . $_GET['file']);

                // 显示模板页
                $smarty->display('filelist.htm');
                break;
        }
    } else {
        // 不存在upload_dir.php文件
        $smarty->assign('html_title', '文件管理');
        $smarty->assign('text', '您还没有设置文件管理目录！');

        // 显示模板页
        $smarty->display('filelist.htm');
    }
    
?>