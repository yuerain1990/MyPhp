<?php
    session_start();
    // 身份验证
    if ($_SESSION['username'] == '') header("Location:login.php");

    include_once('config.php');
    include_once('class.php');

    $homeid = (int)$_GET['id'];

    // 实例化MySQL类
    $db = new DB_Connect($db_name, $db_host, $db_user, $db_pass);

    // 检查房间是否存在
    $sql = "SELECT * FROM chat_home WHERE id='{$_GET['id']}'";
    $data = $db->fetch_rows($sql);
    var_dump($data);
    if (!$data['id']) die('房间不存在');

    $_SESSION['homeid'] = $homeid;

    // 进入聊天室
    $username = $_SESSION['username'];
    $time = time();
    $ip = getenv("REMOTE_ADDR");

    echo "清除前";
    // 清除自己的在线记录（如果有的话，比如换房间）
    $sql = "DELETE FROM chat_online WHERE username='$username'";
    $db->query($sql);
    echo "清除后";

    // 重新添加
    $sql = "INSERT INTO chat_online (id, homeid, username, intime, ip) VALUES (NULL, '$homeid', '$username', '$time', '$ip')";
    $db->query($sql);
    echo "重新添加后";
?>
<html>
<head>
<title><?=$data['name']?></title>
<meta http-equiv="pragma" content="no-cache">
<META http-equiv="Content-Type" content="text/html;charset=gb2312">
<META http-equiv="expires" content="0">
</head>
<script language="javascript">
var username = "<?=$_SESSION['username']?>";
var hoomname = "<?=$_SESSION['name']?>";
var homeid = "<?=$_SESSION['id']?>";
document.write("Hello World!");
// 建立Ajax实例
function InitAjax()
{
    var ajax = null;
    try {
        ajax = new ActiveXObject("Microsoft.XMLHTTP");      //旧版本
    } catch (e) {
        ajax = false;
    }
    if (!ajax && typeof(XMLHttpRequest)!='undefined') {
        ajax = new XMLHttpRequest();                        // 新版本
    }
    return ajax;
}

// 取回聊天记录
function getmessage()
{
    var url = "message.php?hid="+homeid;
    var ajax = InitAjax();
    ajax.open('GET', url, false);   // false 同步执行, true 异步执行
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            eval(ajax.responseText);
        }
    }
    ajax.send(null);
    setTimeout("getmessage()", 1500);
}

// 发送聊天信息
function setmessage() 
{
    var mtouser = window.inputmess.document.getElementById('touser').value;
    var mcontent = window.inputmess.document.getElementById('content').value;

    if (mtouser == '') {
        mtouser = '所有人';
        window.inputmess.document.getElementById('touser').value = "所有人";
    }
    if (mtouser == username) {
        window.inputmess.document.getElementById('touser').value = "所有人";
        window.inputmess.document.getElementById('content').value = "";
        showMessagegg("您自己和自己说话？");
        return false;
    }

    window.inputmess.document.getElementById('content').value = "";
    var url = "post.php";
    var ajax = InitAjax();
    var postStr = "touser=" + mtouser + "&content=" + mcontent;
    if (mcontent != "" && mcontent != "\n" && mcontent != "\r\n" && mcontent != "\n\n") {
        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
        ajax.sent(postStr);
    }
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            eval(ajax.responseText);
        }
    }
}

// 进入欢迎
function showMessageFramePreWrite() 
{
    var str = "<html>";
    str += "<head>";
    str += "<meta charset='gb2312'>";
    str += "<style type='text/css'>";
    str += "body{font-size:14px;line-height:160%;font-family:'宋体';}a{font-size:14px;}.tm{font-size:12px;color:#777777}";
    str += "</style>";
    str += "</head>";
    str += "<body>";
    str += "<font color=#ff0000><b>【系统提示】</b></font>;欢迎来到"+hoomname+"!<br>";
    window.showmessage.document.write(str);
}

// 写入聊天信息
function writeMessage(content, from, to, time) 
{
    var message = '';
    var tostr = '';
    var fromstr = '';
    if (to == username) {
        tostr = "<font color='red'>你</font>";
    } else {
        tostr = "<a href=\"javascript:;\"onclick=\"parent.getusrename('"+to+"');return false;\"><font color='#008800'>"+to+"</font></a>";
    }
    if (from == username) {
        fromstr = "<font color='red'>你</font>";
    } else {
        fromstr = "<a href=\"javascript:;\"onclick=\"parent.getusrename('"+from+"');return false;\"><font color='#008800'>"+from+"</font></a>";
    }
    message += fromstr + " 对 " + tostr + " 说： " + "<font color='#000088'>" + content + "</font>" + "<span class=tm>(" + time + ")</span><br>;
    window.showmessage.document.write(message);
}

// 取回在线列表
function getonline()
{
    var url = "online.php";
    var ajax = InitAjax();
    ajax.open('GET', url, false);   // false 同步执行, true 异步执行
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            eval(ajax.responseText);
        }
    }
    ajax.send(null);
    setTimeout("getonline()", 5000);
}

// 写入在线列表
function writeonline(word)
{
    var str = '<li><a href="javascript:;"onclick="parent.getusrename(\'所有人\');return false;">所有人</a></li>';
    str += word;
    window.onlinelist.document.getElementById('online').innerHTML = str;
}

// 系统提示
function showMessagegg(word)
{
    var str = "<font color=#ff0000><b>【系统提示】</b></font>:" + word + "</br>";
    window.showmessage.document.write(str);
}

// 滚屏
function scrollWindow()
{
    if (!window.inputmess.document.getElementById('scroll').checked)
        return false;
    window.scrollmessage.scrollTo(showmessage.document.body.scrollLeft, showmessage.document.body.scrollTop+20);
}

// 清屏
function clearScreen()
{
    if (!confirm("此操作会丢失所有聊天记录，建议将聊天记录保存到本地。\n\n\t你真的要清除屏幕信息吗？"))
        return false;
    window.showmessage.document.close();
    window.showmessage.document.open();
    showMessageFramePreWrite();
}

// 取得用户名
function getusername(uname) 
{
    window.inputmess.document.getElementById('touser').value = uname;
}

// 退出聊天室
function quitroom()
{
    top.location.href = "quit.php";
}

// 进入初始
function initChatRoom()
{
    showMessageFramePreWrite();
    getmessage();
    setInterval("scrollWindow()", 50);
}
</script>
<frameset cols="*,200" frameborder=1>
    <frameset rows="*,80" frameborder=0>
        <frame src="about:blank" name="showmessage">
        <frame src="msg.htm" name="inputmess" noresize >
    </frameset>
    <frame src="online.htm" name="onlinelist">
</frameset>
<noframes></noframes>
<body onLoad="initChatRoom();">
</body>
</html>

