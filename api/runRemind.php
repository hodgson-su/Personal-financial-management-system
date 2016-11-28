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

//json数据解析开始
$rawpostdata = file_get_contents("php://input");
$post = json_decode($rawpostdata,true);
//json数据解析结束

$data = array();

$date_time_array = getdate(time());

$time = time();
$date = date("y-m-d",$time) ;

$year = $date_time_array["year"];
$month = $date_time_array["mon"];
$day = $date_time_array["mday"];

$individual = "frying";

//remind
$get_mes = array();
$remind_table = $individual."_remind";
$remind_sql = "select * from $remind_table WHERE month = '$month' AND day = '$day'";
$remind_result = $conn->query($remind_sql);
$i = 0;
if($remind_result->num_rows>0){
    while($row = $remind_result->fetch_assoc()){
        $get_mes[$i] = $row['mes'];
        $i = $i+1;
    }
}
$data['get_mes'] = $get_mes;
$data['i']=$i;

//bank
//accountDay
$bank_table = $individual."_bank";
$bank_sql = "select * from $bank_table WHERE accountDay = '$day' AND marked = '1'";
$bank_result = $conn->query($bank_sql);
if($bank_result->num_rows>0){
    $mark = "0";
    while($row = $bank_result->fetch_assoc()){
        $mes = "请注意查看".$row['bank']."的上月账单";
        $insert_sql="INSERT INTO $remind_table (date,year,month,day,mes,mark) VALUES ('$date','$year','$month','$day','$mes','$mark')";
        mysqli_query($conn,$insert_sql);
        $id = $row['id'];
        mysqli_query($conn,"UPDATE $bank_table SET marked = '2' WHERE id = '$id'");
    }
}
//repaymentDay
$repay_bank_sql = "select * from $bank_table WHERE repaymentDay = '$day' AND marked = '2'";
$repay_bank_result = $conn->query($repay_bank_sql);
if($repay_bank_result->num_rows>0){
    while($row = $repay_bank_result->fetch_assoc()){
        $mes = "请注意今天是".$row['bank']."的信用卡付款日，请结清账单";
        $insert_sql="INSERT INTO $remind_table (date,year,month,day,mes,mark) VALUES ('$date','$year','$month','$day','$mes','$mark')";
        mysqli_query($conn,$insert_sql);
        $id = $row['id'];
        mysqli_query($conn,"UPDATE $bank_table SET marked = '3' WHERE id = '$id'");
    }
}

$reset_sql = "select * from $bank_table WHERE repaymentDay < '$day' AND marked = '3'";
$reset_result = $conn->query($reset_sql);
if($reset_result->num_rows>0){
    while($row = $reset_result->fetch_assoc()){
        mysqli_query($conn,"UPDATE $bank_table SET marked = '1' WHERE id = '$id'");
    }
}

echo json_encode($data);