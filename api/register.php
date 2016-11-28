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
 * 注册界面简单逻辑
 * 1、数据传递，判断是否全部填写完成
 * 2、需要检测nickname和emil是否重复存在，分别提醒
 * 3、插入数据，并且创建表
*/

$register = array();
$errors = array();
$marked = array();
$data = array();

//json数据解析开始
$rawpostdata = file_get_contents("php://input");
$post = json_decode($rawpostdata,true);
//json数据解析结束


if(empty($post['nickname']) || empty($post['username']) || empty($post['password']) || empty($post['repassword']) || empty($post['email'])){
    $marked = "请将表格填写完整(注意检查邮箱格式)";
} else{
    if($post['password'] != $post['repassword']){
        $marked = "两次密码不一致";
    }
    else{
        $register['nickname'] = $post['nickname'];
        $register['username'] = $post['username'];
        $register['password'] = $post['password'];
        $register['repassword'] = $post['repassword'];
        $register['email'] = $post['email'];
        insert_data($conn,$register['nickname'],$register['username'],$register['password'],$register['email'],$marked);
    }
}
//insert_data($conn,'j5i','ji','ji','jti',$marked);
$data['errors'] = $errors;
$data['marked'] = $marked;
$data['register'] = $register;
echo json_encode($data);


//插入数据
function insert_data($conn,$nickname,$username,$password,$email,&$marked)
{
    $register_check = true;
    //验证用户名没有重复
    $sql_select_check = "SELECT nickName,EMail FROM signin";
    $check_result = $conn->query($sql_select_check);
    while ($check_row = $check_result->fetch_assoc()) {
        if ($check_row['nickName'] == $nickname) {
            $marked = "对不起，该昵称已存在，请更改";
            $register_check = false;
        } elseif ($check_row['EMail'] == $email) {
            $marked = "对不起，此邮箱已注册，请换一个邮箱";
            $register_check = false;
        }
    }
    if ($register_check == true) {
        $image = "img/imgSrc/1.jpg";
        $myphone = null;
        $takeover = 0;
        $sql = "INSERT INTO signin (nickName, userName, passWord,EMail,Image,myPhone,takeOver)
            VALUES ('$nickname', '$username', '$password','$email','$image','$myphone',$takeover)";
        $conn->query("SET NAMES UTF-8");
        if (mysqli_query($conn, $sql)) {
//            echo "Table MyGuests created successfully";
            //在接口处不要用输出，否则会造成错误
            $marked = "注册成功，请登录";
        } else {
//            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        create_table($conn, $nickname);
    }
}
//$nickname = "fry1";
//create_table($conn,$nickname);
//创建表
function create_table($conn,$nickname){
    $mes_name = $nickname."_"."message";
    $classify_income_name = $nickname."_"."classify_income";
    $classify_pay_name = $nickname."_"."classify_pay";
    $remind_name = $nickname."_"."remind";
    $account_name = $nickname."_"."account";
    $bank_name = $nickname."_"."bank";

    $sql_mes = "CREATE TABLE $mes_name (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        date TIMESTAMP NOT NULL ,
        year INT(6) NOT NULL ,
        month INT(6) NOT NULL ,
        day INT(6) NOT NULL ,
        account VARCHAR(30) NOT NULL ,
        price VARCHAR (100) NOT NULL ,
        classify VARCHAR (100) NOT NULL ,
        remark VARCHAR(100),
        budget VARCHAR(30) NOT NULL
      ) DEFAULT charset=utf8";
    $sql_classify_income = "CREATE TABLE $classify_income_name
    (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        classify VARCHAR(255)
    ) DEFAULT charset=utf8";
    $sql_classify_pay = "CREATE TABLE $classify_pay_name (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        classify VARCHAR(255)
      ) DEFAULT charset=utf8";
    $sql_remind = "CREATE TABLE $remind_name (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        date TIMESTAMP NOT NULL ,
        year INT(6) NOT NULL ,
        month INT(6) NOT NULL ,
        day INT(6) NOT NULL ,
        mes VARCHAR(30) NOT NULL
      ) DEFAULT charset=utf8";
    $sql_account = "CREATE TABLE $account_name (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        account VARCHAR(255),
        remark int(10)
      ) DEFAULT charset=utf8";
    $sql_bank = "CREATE TABLE $bank_name (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        bank VARCHAR(255),
        priceLimit VARCHAR(255),
        accountDay VARCHAR(255),
        repaymentDay VARCHAR(255)
      ) DEFAULT charset=utf8";
    if ($conn->query($sql_mes) === TRUE) {
//        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
    if ($conn->query($sql_classify_income) === TRUE) {
//        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
    if ($conn->query($sql_classify_pay) === TRUE) {
//        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
    if ($conn->query($sql_remind) === TRUE) {
//        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
    if ($conn->query($sql_account) === TRUE) {
//        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
    if ($conn->query($sql_bank) === TRUE) {
//        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    //插入数据
    $sql_insert_classify = "INSERT INTO $classify_income_name (id,classify)
            VALUES (1,'固定工资');";
    $sql_insert_classify.= "INSERT INTO $classify_income_name (id,classify)
            VALUES (2,'奖金收入');";
    $sql_insert_classify .= "INSERT INTO $classify_income_name (id,classify)
            VALUES (3,'兼职等');";
    $sql_insert_classify .= "INSERT INTO $classify_income_name (id,classify)
            VALUES (4,'其他收入');";
    $sql_insert_classify .= "INSERT INTO $classify_pay_name (id,classify)
            VALUES (1,'衣服饰品');";
    $sql_insert_classify .= "INSERT INTO $classify_pay_name (id,classify)
            VALUES (2,'交通通讯');";
    $sql_insert_classify .= "INSERT INTO $classify_pay_name (id,classify)
            VALUES (3,'人情往来');";
    $sql_insert_classify .= "INSERT INTO $classify_pay_name (id,classify)
            VALUES (5,'医疗保健');";
    $sql_insert_classify .= "INSERT INTO $account_name (id,account,remark)
            VALUES (1,'现金','1');";
    $sql_insert_classify .= "INSERT INTO $account_name (id,account,remark)
            VALUES (2,'负债','1');";
    $sql_insert_classify .= "INSERT INTO $account_name (id,account,remark)
            VALUES (3,'投资','1');";
    $sql_insert_classify .= "INSERT INTO $account_name (id,account,remark)
            VALUES (4,'保险','1');";

    if ($conn->multi_query($sql_insert_classify) === TRUE) {
//        echo "New records created successfully";
    } else {
        echo "Error: " . $sql_insert_classify . "<br>" . $conn->error;
    }
}