<?php
/****************数据库的链接以及开头start*****************/
header("Content-Type: text/html; charset=utf-8");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finicial_management";

// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname);
// 检测连接
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$conn->query("set names 'utf8'");
/****************数据库的链接以及开头over*****************/

/*
 * 密码找回界面的简单逻辑
 * 1、数据传递，判断是否全部填写完成
 * 2、检测用户名和邮箱是否一一对应
 * 3、对应成功后，更改随机密码，发送邮箱
*/

//json数据解析开始
$rawpostdata = file_get_contents("php://input");
$post = json_decode($rawpostdata,true);
//json数据解析结束

$forgot = array();
$data = array();
$check = false;
if(empty($post['nickname']) || empty($post['email'])){
    $marked = "请填写用户名（注意是登录名）/密码";
} else{
    $marked = "已完整填写";
    $forgot['nickname'] = $post['nickname'];
    $forgot['email'] = $post['email'];
    $sql_lognin_all = "SELECT * FROM signin";
    $result_all = $conn->query($sql_lognin_all);
    while($row = $result_all->fetch_assoc()){
        if($row['nickName'] == $forgot['nickname'] && $row['EMail']== $forgot['email']){
            $check = true;
        }
    }
    if($check){
        $marked = "邮件已发送";
    }else{
        $marked = "用户名或者邮箱错误";
    }
}
$data['marked'] = $marked;
echo json_encode($data);

