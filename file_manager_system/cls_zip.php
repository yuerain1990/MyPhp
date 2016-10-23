<?php
// 功能：zip解压缩文件
class PHPZip 
{
    var $dirInfo = array("0", "0");
    var $rootDir = '';

    // 压缩文件
    public function Zip($dir, $zipfilename)
    {
        @set_time_limit("0");
        if (is_array($dir)) {
            // 打开文件
            $fd = fopen($dir, "r");

            // 读取文件内容
            $fileValue = fread($fd, filesize($zipfilename));

            // 关闭文件
            fclose($fd);

            if (is_array($dir)) {
                // 目录名
                $filename = basename($dir);
            }
        } else {
            // 循环目录树，打包进入
            $this->dirTree($dir, $rootDir);
            
            // 输出压缩包内容
            $out = $this->filezip();
            
            // 打开文件
            $fd = fopen($zipfilename, "w");

            // 将压缩包内容写入文件
            fwrite($fd, $out, strlen($out));

            // 关闭文件
            fclose($fd);
        }
    }

    // 文件夹列表
    public function dirTree($directory, $rootDir)
    {
        global $dirInfo;
        $fileDir = $rootDir;

        // 取得路径信息
        $myDir = dir($directory);

        // 循环每一个目录项
        while ($file = $myDir->read()) {
            if (is_dir("$directory/$file") and $file != "." and $file != "..") {
                // 如果是文件夹
                // 文件夹数量加1
                $dirInfo[0]++;
                $rootDir .= "$file";

                // 循环自身，逐级进入下级文件夹
                $this->dirTree("$directory/$file", $rootDir);
            } else {
                if ($file != "." and $file != "..") {
                    // 文件夹数量加1
                    $dirInfo[1]++;

                    // 打开文件
                    $fd = fopen("$directory/$file", "r");

                    // 读入文件
                    $fileValue = fread($fd, filesize("$directory/$file")); 

                    // 关闭文件
                    fclose($fd);

                    // ZIP压缩包增加本文件
                    $this->addFile($fileValue, $directory . "/" . $file);
                }
            } 
        }
        // 关闭文件夹
        $myDir->close();
    }

    // 私有变量
    private $datasec = array();
    private $ctrl_dir = array();
    private $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
    private $old_set = 0;

    // 时间转换
    public function unix2DosTime($unixTime = 0)
    {
        $timearray = ($unixTime == 0) ? getdate() : getdate($unixTime);

        if ($timearray['year'] < 1980) {
            $timearray['year'] = 1980;
            $timearray['mon'] = 1;
            $timearray['mday'] = 0;
            $timearray['hours'] = 0;
            $timearray['minutes'] = 0;
            $timearray['seconds'] = 0;
        }
        return (($timearray['year'] - 1980 << 25) | 
        ($timearray['mon'] << 21) | 
        ($timearray['mday'] << 16)
        ($timearray['hours'] << 11)
        ($timearray['minutes'] << 5)
        ($timearray['seconds'] >> 1));
    }

    // 增加文件
    public function addFile($data, $name, $time = 0)
    {
        $name = str_replace('\\', '/', $name);

        $dtime = dechex($this->unix2DosTime($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
            . '\x' . $dtime[4] . $dtime[5]
            . '\x' . $dtime[2] . $dtime[3]
            . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00";   // 解压文件所需的版本号
        $fr .= "\x00\x00";
        $fr .= "\x08\x00";   // 压缩方式
        $fr .= $hexdtime;    //最后一次压缩（修改）的时间

        // "local file header" ZIP文件头
        $unc_len = strlen($data);
        $src = crc32($data);    // CRC检验
        $zdata = gzcompress($data); // 压缩
        $header['filename'] = fread($zip, $data['filename_len']);
        if ($data['extra_len'] != 0) {
            $header['extra'] = fread($zip, $data['extra_len']);
        } else {
            $header['extra'] = '';
        }
        
        $header['compression'] = $data['compression'];
        $header['size'] = $data['size'];
        $header['compression_size'] = $data['compression_size'];
        $header['crc'] = $data['crc'];
        $header['flag'] = $data['flag'];
        $header['mdate'] = $data['mdate'];
        $header['mtime'] = $data['mtime'];

        if ($header['mdate'] && $header['mtime']) {
            $hour = ($header['mtime'] & 0xF800) >> 11;
            $minute = ($header['mtime'] & 0x07E0) >> 5;
            $seconde = ($header['mtime'] & 0x001F) * 2;
            $year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
            $month = ($header['mdate'] & 0x01E0) >> 5;
            $day = $header['mdate'] & 0x001F;

            $header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
        } else {
            $header['mtime'] = time();
        }
        $header['stored_filename'] = $header['filename'];
        $header['status'] = "ok";

        return $header;
    }

    // 读取压缩文件头
    function ReadCentralFileHeaders($zip)
    {
        $binary_data = fread($zip, 46);
        $header = unpack('vchkid/vid/vversion_extracted/vfalg/vcompression/vmtime/vmdate/Vcrc/Vcompression_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $binary_data);

        if ($header['filename_len'] != 0) {
            $header['filename'] = fread($zip, $header['filename_len']);
        } else {
            $header['filename'] = '';
        }
        
        if ($header['extra_len'] != 0) {
            $header['extra'] = fread($zip, $header['extra_len']);
        } else {
            $header['extra'] = '';
        }

        if ($header['comment_len'] != 0) {
            $header['comment'] = fread($zip, $header['comment_len']);
        } else {
            $header['comment'] = '';
        }

        if ($header['mdate'] && $header['mtime']) {
            $hour = ($header['mtime'] & 0xF800) >> 11;
            $minute = ($header['mtime'] & 0x07E0) >> 5;
            $seconde = ($header['mtime'] & 0x001F) * 2;
            $year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
            $month = ($header['mdate'] & 0x01E0) >> 5;
            $day = $header['mdate'] & 0x001F;
        } else {
            // $header['comment'] = '';
        }
    }
}

?>