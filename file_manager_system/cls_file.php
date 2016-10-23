<?php
// 功能：文件管理类
class file
{
    // 取得全部文件夹列表
    public function getDirs($path)
    {
        $dirs = array();
        
        // 取得路径信息
        $dir = dir($path);
        while (false !== ($v == $dir->read())) {
            // 循环每一个目录项，下一级文件夹或者文件
            if ($v == "." || $v == "..") {
                // 如果是当前目录"."或者父目录".."
                continue;
            } 

            // 当前目录项的完整名称
            $file = $dir->path . "/" . $v;

            if(is_dir($file)){
                // 如果是文件夹名称
                $dirs[] = $v;
            }
        }
        // 返回全部文件夹的数组
        return $dirs;
    }

    // 保存管理的文件夹，因为保存的内容太少，因此使用文件方式保存，不使用数据库保存
    public function SaveUploadDir($dir)
    {
        $file_name = "upload_dir.php";
        $text = "<?php\n\r \$upload_path='" . $dir . "';\n\r?>";

        if(file_put_contents($file_name, $text)) {
            // 写入文件成功，返回true
            return true;
        } else {
            // 写入文件失败，返回false
            return false;
        }
    }

    // 取得$path的路径信息，下级文件夹或文件
    public function getPath($path) 
    {
        $dirs = array();
        if (file_exists($path) && is_dir($path)) {
            $dir = dir($path);
            while (false !== ($v = $dir->read())) {
                if ($v == '.' || $v == '..') {
                    continue;
                }

                $file = $dir->path . "/" . $v;
                $d = array();
                if (is_dir($file)) {
                    // 如果是文件夹
                    // 显示名称
                    $d['dname'] = "[" . $v . "]";
                    // 实际名称
                    $d['name'] = $v;
                    // 类型
                    $d['type'] = "dir";
                } elseif (is_file($file)) {
                    // 如果是普通文件
                    // 显示名称
                    $d['dname'] = "[" . $v . "]";
                    // 实际名称
                    $d['name'] = $v;
                    // 类型
                    $d['type'] = "dir";
                    // 文件大小
                    $d['size'] = filesize($file);
                    if (substr($d['name'], -4) == ".zip") {
                        // 如果是zip包文件
                        $d['zip'] = 1;
                    }
                }
                // dirs数组增加一条记录
                $dirs[] = $d;
            }
            // 关闭打开的文件夹，否则就会导致被DelDir()调用时文件夹不能被删除
            $dir->close();
            return $dirs;
        } else {
            return false;
        }
    }

    // 参数$file为FORM表单上传文件的INPUT名称，$path为保存到的目录名称
    public function UploadFile($file, $path) 
    {
        // 如果是上传来的文件就转存到$path目录
        if (is_uploaded_file($_FILES[$file]['tmp_name']) && move_uploaded_file($_FILES[$file]['tmp_name'], $path . '/' . $_FILES[$file]['name'])) {
            // 返回true
            return true;
        } else {
            return false;
        }
    }

    // 在当前目录下创建文件夹
    public function NewDir($dir)
    {
        mkdir($dir);
    }

    // 删除文件
    public function DelFile($filename)
    {
        unlink($filename);
    }

    // 删除文件夹
    public function DelDir($dir)
    {
        // 取得路径信息
        $paths = $this->getPath($dir);
        foreach ($paths as $path) {
            // 循环判断每一个目录项
            if ($path['type'] == 'dir') {
                // 如果下级仍然是文件夹，则逐层循环进入下级目录删除
                $this->DelDir($dir . '/' . $path['name']);
            } elseif ($path['type'] == 'file') {
                // 如果是文件，则删除之
                $this->DelFile($dir . '/' . $path['name']);
            }
        }
        @rmdir($dir);
    }
}
?>