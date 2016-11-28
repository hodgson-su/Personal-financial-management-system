<?php
/**
 * Created by PhpStorm.
 * User: yhg97p
 * Date: 2016/3/21
 * Time: 15:51
 */
//注册界面
function register_individual($conn){
    $register_nickname = "Lily1";
    $register_username = "李丽";
    $register_password = "Lily123";
    $register_repassword = "Lily123";
    $register_email = "Lily1123@126.com";
    if($register_nickname =="" || $register_username =="" || $register_password =="" ){
        echo"用户名或者密码不得为空";
    }
    else{
        if($register_password != $register_repassword){
            echo"两次输入密码不一致";
        }else{
            insert_data($conn,$register_nickname,$register_username,$register_password,$register_email);
            create_table($conn,$register_nickname);
        }
    }
}

//插入数据
function insert_data($conn,$register_nickname,$register_username,$register_password,$register_email)
{
    //验证用户名没有重复
    $register_check = true;
    $sql_select_check = "SELECT nickName,EMail FROM signin";
    $check_result = $conn->query($sql_select_check);
    while ($check_row = $check_result->fetch_assoc()) {
        if ($check_row['nickName'] == $register_nickname) {
            echo "此用户名已存在";
            $register_check = false;
            exit;
        } elseif ($check_row['EMail'] == $register_email) {
            echo "此邮箱已经注册";
            $register_check = false;
            exit;
        }
    }
    if ($register_check) {
        $sql = "INSERT INTO signin (nickName, userName, passWord,EMail)
            VALUES ('$register_nickname', '$register_username', '$register_password','$register_email')";
        $conn->query("SET NAMES UTF-8");
        if (mysqli_query($conn,$sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

//创建表
function create_table($conn,$register_nickname){
    $register_nickname = "individual"."_".$register_nickname;
    echo $register_nickname;
    $sql = "CREATE TABLE $register_nickname (
        num INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        date TIMESTAMP ,
        account VARCHAR(30) NOT NULL,
        momey VARCHAR (100) NOT NULL ,
        classify VARCHAR (100) NOT NULL ,
        remark VARCHAR(100)
    )";
    if (mysqli_query($conn, $sql)) {
        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}