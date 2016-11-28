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
 * 登陆界面逻辑判断
 * 1、接收angularjs传递数据
 * 2、判断是否为空，若为空返回提示语marked，用户名/密码不得为空
 * 3、不为空，进行判断是否正确，错误，返回提示语，用户名/密码错误
 * 4、成功，返回登录成功（后改为界面跳转）
 */
$login = array();//angularjs数据传递
$errors = array();//记录用户名是否为空或者其他
$save = array();
$data = array();

//json数据解析开始
$rawpostdata = file_get_contents("php://input");
$post = json_decode($rawpostdata,true);
//json数据解析结束

$errors['login'] = false;
if(empty($post['nickname']) || empty($post['password'])){
    $marked = "用户名/密码不得为空";
    $errors['block'] = true;
}else{
    $errors['block'] = false;
    $login['nickname'] = $post['nickname'];
    $login['password'] = $post['password'];
    $sql_lognin_all = "SELECT * FROM signin";
    $result_all = $conn->query($sql_lognin_all);
    while($row = $result_all->fetch_assoc()){
        if($row['nickName'] == $login['nickname'] && $row['passWord']== $login['password']){
              $errors['login'] = true;
        }
    }
}

if($errors['block']){
    $marked = "用户名/密码不得为空";
}elseif($errors['login']){
    $marked = "登录成功";
    save_individual_message($conn,$login['nickname'],$save['username']);
    $save['nickname'] = $login['nickname'];
}else{
    $marked = "用户名/密码错误";
}
$data['marked'] = $marked;
$data['errors'] = $errors;
$data['save'] = $save;
echo json_encode($data);

//要返回一个nickname和username回去，使用save来
function save_individual_message($conn,$nickname,&$username){
    $sql_select_username = "SELECT userName FROM signin WHERE  nickName = '$nickname'";
    $username_result =mysqli_query($conn,$sql_select_username);
    while($row = mysqli_fetch_array($username_result)){
        $username = $row['userName'];
    }
}
?>

