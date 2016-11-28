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


//进入个人界面以后获取数据库的数据
$individual = array();
$income = array();
$pay = array();
$data = array();
$echo = array();
$date_time_array = getdate(time());

$year = $date_time_array["year"];
$month = $date_time_array["mon"];
$day = $date_time_array["mday"];
$week = $date_time_array["wday"];

$individual = $post['nickname'];

$individual_table = $individual."_message";
$data['echo'] = $echo;
//收入的本周，本月，本年的计算
$all_month_price = 0;
$income_sum_month_sql = "select sum(price) as all_month_price from $individual_table where month = $month and budget = '收'";
$income_sum_month_result = $conn->query($income_sum_month_sql);
while($row = mysqli_fetch_array($income_sum_month_result)){
    $all_month_price = $row['all_month_price'];
}
$income['month'] = $all_month_price;

$sum_week_income = 0;
select_week_income($conn,$week,$day,$month,$sum_week_income,$individual_table);
$income['week'] = $sum_week_income;
function select_week_income($conn,$week,$day,$month,&$sum_week_income,$individual_table){
    $sum_week_income = 0;
    $before_week = $week - 1;
    $before_day = $day -1;
    while($before_week>=1){
        $sql ="select price from $individual_table where month = $month and day = $before_day and budget = '收'";
        $result = $conn->query($sql);
        while($row = mysqli_fetch_array($result)){
            $sum_week_income = $row['price']+ $sum_week_income;
        }
        $before_day = $day-1;
        $before_week = $before_week -1;
    }
    while($week<=7){
        $sql = "select price from $individual_table where month = $month and day = $day and budget = '收'";
        $result = $conn->query($sql);
        while($row = mysqli_fetch_array($result)){
            $sum_week_income = $row['price']+ $sum_week_income;
        }
        $day = $day+1;
        $week = $week +1;
    }
}

$all_year_price = 0;
$income_sum_year_sql = "select sum(price) as all_year_price from $individual_table where year = $year and budget = '收'";
$income_sum_year_result = $conn->query($income_sum_year_sql);
while($row = mysqli_fetch_array($income_sum_year_result)){
    $all_year_price = $row['all_year_price'];
}
$income['year'] = $all_year_price;
$data['income'] = $income;
$data['month'] = $month;

//支出
$pay_sum_month_price = 0;
$pay_sum_month_sql = "select sum(price) as pay_sum_month_price from $individual_table where month = $month and budget = '支'";
$pay_sum_month_result = $conn->query($pay_sum_month_sql);
while($row = mysqli_fetch_array($pay_sum_month_result)){
    $pay_sum_month_price = $row['pay_sum_month_price'];
}
$pay['month'] = $pay_sum_month_price;

$pay_sum_week = 0;
pay_sum_week($conn,$week,$day,$month,$pay_sum_week,$individual_table);
$pay['week'] = $pay_sum_week;

function pay_sum_week($conn,$week,$day,$month,&$pay_sum_week,$individual_table){
    $pay_sum_week = 0;
    $before_week = $week - 1;
    $before_day = $day -1;
    while($before_week>=1){
        $sql ="select price from $individual_table where month = $month and day = $before_day and budget = '支'";
        $result = $conn->query($sql);
        while($row = mysqli_fetch_array($result)){
            $pay_sum_week_income = $row['price']+ $pay_sum_week;
        }
        $before_day = $day-1;
        $before_week = $before_week -1;
    }
    while($week<=7){
        $sql = "select price from $individual_table where month = $month and day = $day and budget = '支'";
        $result = $conn->query($sql);
        while($row = mysqli_fetch_array($result)){
            $pay_sum_week = $row['price']+ $pay_sum_week;
        }
        $day = $day+1;
        $week = $week +1;
    }
}

$pay_sum_year_price = 0;
$pay_sum_year_sql = "select sum(price) as pay_sum_year_price from $individual_table where year = $year and budget = '支'";
$pay_sum_year_result = $conn->query($pay_sum_year_sql);
while($row = mysqli_fetch_array($pay_sum_year_result)){
    $pay_sum_year_price = $row['pay_sum_year_price'];
}

$pay['year'] = $pay_sum_year_price;
$data['pay'] = $pay;

//account
$account = array();
$account_table = $individual."_account";
$account_sql = "select account from $account_table";
$account_result = $conn->query($account_sql);
$i =0;
if($account_result->num_rows>0){
    while($row = $account_result->fetch_assoc()){
        $account[$i] = $row['account'];
        $i = $i+1;
    }
    $i = 0;
}
$data['account'] = $account;
//通过account得到总的price，income与pay
for($i=0;$i<sizeof($account);$i++) {
    $account_pay_sql = "select sum(price) as account_pay_price from $individual_table where month = $month and budget = '支' and account = '$account[$i]' ";
    $account_pay_result = $conn->query($account_pay_sql);
    while ($row = mysqli_fetch_array($account_pay_result)) {
        $account_pay_price[$i] = (int)$row['account_pay_price'];
        if($account_pay_price[$i] == null){
            $account_pay_price[$i] = 0;
        }
    }
}
$data['account_pay_price'] = $account_pay_price;

for($i=0;$i<sizeof($account);$i++) {
    $account_income_sql = "select sum(price) as account_income_price from $individual_table where month = $month and budget = '收' and account = '$account[$i]' ";
    $account_income_result = $conn->query($account_income_sql);
    while ($row = mysqli_fetch_array($account_income_result)) {
        $account_income_price[$i] = (int)$row['account_income_price'];
        if($account_income_price[$i] == null){
            $account_income_price[$i] = 0;
        }
    }
}
$data['account_income_price'] = $account_income_price;

$account_blance_price = array();
for($i=0;$i<sizeof($account);$i++){
    $account_blance_price[$i] = $account_income_price[$i] - $account_pay_price[$i];
}
$data['account_blance_price'] = $account_blance_price;


//本月支出去向，通过classify进行分类
$classify_pay = array();
$classify_pay_table = $individual."_classify_pay";
$classify_pay_sql = "select classify from $classify_pay_table";
$classify_pay_result = $conn->query($classify_pay_sql);
$i = 0;
if($classify_pay_result->num_rows>0){
    while($row = $classify_pay_result->fetch_assoc()){
        $classify_pay[$i] = $row['classify'];
        $i = $i+1;
    }
    $i = 0;
}

//通过classify得到现金数返回sum_classify_price
for($i=0;$i<sizeof($classify_pay);$i++) {
    $pay_sum_classify_pay_sql = "select sum(price) as pay_sum_classify_pay_price from $individual_table where month = $month and budget = '支' and classify = '$classify_pay[$i]' ";
    $pay_sum_classify_pay_result = $conn->query($pay_sum_classify_pay_sql);
    while ($row = mysqli_fetch_array($pay_sum_classify_pay_result)) {
        $pay_sum_classify_pay_price[$i] = (int)$row['pay_sum_classify_pay_price'];
        if($pay_sum_classify_pay_price[$i] == null){
            $pay_sum_classify_pay_price[$i] = 0;
        }
    }
}
$data['classify']=$classify_pay;
$data['classify_price'] = $pay_sum_classify_pay_price;

//本月收入去向，通过classify_income进行分类
$classify_income = array();
$classify_income_table = $individual."_classify_income";
$classify_income_sql = "select classify from $classify_income_table";
$classify_income_result = $conn->query($classify_income_sql);
$i = 0;
if($classify_income_result->num_rows>0){
    while($row = $classify_income_result->fetch_assoc()){
        $classify_income[$i] = $row['classify'];
        $i = $i+1;
    }
    $i = 0;
}
for($i=0;$i<sizeof($classify_income);$i++) {
    $pay_sum_classify_income_sql = "select sum(price) as pay_sum_classify_income_price from $individual_table where month = $month and budget = '收' and classify = '$classify_income[$i]' ";
    $pay_sum_classify_income_result = $conn->query($pay_sum_classify_income_sql);
    while ($row = mysqli_fetch_array($pay_sum_classify_income_result)) {
        $pay_sum_classify_income_price[$i] = (int)$row['pay_sum_classify_income_price'];
        if($pay_sum_classify_income_price[$i] == null){
            $pay_sum_classify_income_price[$i] = 0;
        }
    }
}
$data['classify_income']=$classify_income;
$data['classify_income_price'] = $pay_sum_classify_income_price;



//本周支出数据
$pay_sum_week_price = [0,0,0,0,0,0,0];
$before_week =(int) $week;
$before_day = (int)$day;
for($before_week = $week ;$before_week>0;$before_week = $before_week-1,$before_day=$before_day-1){
    $pay_sum_week_price_sql = "select sum(price) as pay_sum_week_price from $individual_table where month = $month and budget = '支' and day = $before_day ";
    $pay_sum_week_result = $conn->query($pay_sum_week_price_sql);
    while ($row = mysqli_fetch_array($pay_sum_week_result)) {
        $pay_sum_week_price[(int)$before_week-1] = (int)$row['pay_sum_week_price'];
    }
}
$after_day = $day+1;
for($after_week = $week+1;$after_week<=7;$after_week = $after_week+1,$after_day = $after_day+1){
    if($after_day>31){
        $month = $month+1;
        $after_day = 1;
    }
    $pay_sum_week_price_sql = "select sum(price) as pay_sum_week_price from $individual_table where month = $month and budget = '支' and day = $after_day ";
    $pay_sum_week_result = $conn->query($pay_sum_week_price_sql);
    while ($row = mysqli_fetch_array($pay_sum_week_result)) {
        $pay_sum_week_price[(int)$after_week-1] = (int)$row['pay_sum_week_price'];
    }
}
$data['pay_sum_week_price'] = $pay_sum_week_price;

//收支趋势，显示4月数据，
$pay_month_trend_price = [0,0,0,0];
$trend_times = [0,0,0,0];
$pay_month = $month;
for($i=3;$i>=0;$i--){
    $trend_times[$i] =$year.'.'.$pay_month;
    $pay_month_trend_sql = "select sum(price) as pay_month_trend from $individual_table where month = $pay_month and budget = '支' ";
    $pay_month_trend_result = $conn->query($pay_month_trend_sql);
    while ($row = mysqli_fetch_array($pay_month_trend_result)) {
        $pay_month_trend_price[$i] = (int)$row['pay_month_trend'];
    }
    $pay_month= $pay_month-1;
}
$data['pay_month_trend_price'] = $pay_month_trend_price;

$income_month_trend_price = [0,0,0,0];
$income_month = $month;
for($i=3;$i>=0;$i--){
    $income_month_trend_sql = "select sum(price) as income_month_trend from $individual_table where month = $income_month and budget = '收' ";
    $income_month_trend_result = $conn->query($income_month_trend_sql);
    while ($row = mysqli_fetch_array($income_month_trend_result)) {
        $income_month_trend_price[$i] = (int)$row['income_month_trend'];
    }
    $income_month = $income_month-1;
}
$data['income_month_trend_price'] = $income_month_trend_price;

$balance_month_trend_price = [0,0,0,0];
for($i=3;$i>=0;$i--){
    $blance_month_trend_price[$i] =(int)( $income_month_trend_price[$i] - $pay_month_trend_price[$i]);
}
//$items = array();
$data['blance_month_trend_price'] = $blance_month_trend_price;
$data['trend_times'] =$trend_times;
//for($i=0;$i<4;$i++){
//    if($items == null){
//        $items = "[{"."time".":".$trend_times[$i].",income:".$income_month_trend_price[$i].",pay:".$income_month_trend_price[$i].",blance:".$blance_month_trend_price[$i]."},";
//    }else{
//        $items = $items."{time".":".$trend_times[$i].",income:".$income_month_trend_price[$i].",pay:".$income_month_trend_price[$i].",blance:".$blance_month_trend_price[$i]."},";
//    }
//
//}
//$data['items'] = $items."]";

//bills
//总收入与支出与结余

//form03
$pay_sum_budget_income_price = [0,0,0,0];
//$budget_month = $post['month'];
if(empty($post['month'])){
    $budget_month = $month;
}else{
    $budget_month = $post['month'];
}
$individual_budget = $individual."_budget_".$year."_".$budget_month;
$budget_sql = "select * from $individual_budget";
if(mysqli_query($conn,$budget_sql)){
//    echo '存在'
    select_budget_exit($conn,$classify_income,$individual_budget,$individual_table,$budget_month,$year,$classify_pay,$data);
}else{
//    echo '不存在';
    $sql_budgets = "CREATE TABLE $individual_budget (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        classify VARCHAR (100) NOT NULL ,
        price VARCHAR (100) NOT NULL ,
        budget VARCHAR(30) NOT NULL
      ) DEFAULT charset=utf8";
    if ($conn->query($sql_budgets) === TRUE) {
//        echo "Table MyGuests created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
    $sql_insert_classify = "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (1,'固定工资',0,'收');";
    $sql_insert_classify.= "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (2,'奖金收入',0,'收');";
    $sql_insert_classify .= "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (3,'兼职等',0,'收');";
    $sql_insert_classify .= "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (4,'其他收入',0,'收');";
    $sql_insert_classify .= "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (5,'衣服饰品',0,'支');";
    $sql_insert_classify .= "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (6,'交通通讯',0,'支');";
    $sql_insert_classify .= "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (7,'人情往来',0,'支');";
    $sql_insert_classify .= "INSERT INTO $individual_budget (id,classify,price,budget)
            VALUES (8,'医疗保健',0,'支');";

    if ($conn->multi_query($sql_insert_classify) === TRUE) {
//        echo "New records created successfully";
    } else {
        echo "Error: " . $sql_insert_classify . "<br>" . $conn->error;
    }
}
if(mysqli_query($conn,$budget_sql)){
    select_budget_exit($conn,$classify_income,$individual_budget,$individual_table,$budget_month,$year,$classify_pay,$data);
}

//$data['budget_income_price'] = $pay_sum_budget_income_price;
//$data['fact_income_price'] = $pay_sum_fact_income_price;
//$data['budget_pay_price'] = $pay_sum_budget_pay_price;
//$data['fact_pay_price'] = $pay_sum_fact_pay_price;

function select_budget_exit($conn,$classify_income,$individual_budget,$individual_table,$budget_month,$year,$classify_pay,&$data){
    //    income收入，
//    预算收入budget_income
    for($i=0;$i<sizeof($classify_income);$i++) {
        $pay_sum_budget_income_sql = "select price from $individual_budget where budget = '收' and classify = '$classify_income[$i]' ";
        $pay_sum_budget_income_result = $conn->query($pay_sum_budget_income_sql);
        while ($row = mysqli_fetch_array($pay_sum_budget_income_result)) {
            $pay_sum_budget_income_price[$i] = (int)$row['price'];
            if($pay_sum_budget_income_price[$i] == null){
                $pay_sum_budget_income_price[$i] = 0;
            }
        }
    }
    $data['budget_income_price'] = $pay_sum_budget_income_price;
//    实际收入
    for($i=0;$i<sizeof($classify_income);$i++) {
        $pay_sum_fact_income_sql = "select sum(price) as sum_price_fact_income from $individual_table where budget = '收' and classify = '$classify_income[$i]' and month = $budget_month and year = $year";
        $pay_sum_fact_income_result = $conn->query($pay_sum_fact_income_sql);
        while ($row = mysqli_fetch_array($pay_sum_fact_income_result)) {
            $pay_sum_fact_income_price[$i] = (int)$row['sum_price_fact_income'];
            if($pay_sum_fact_income_price[$i] == null){
                $pay_sum_fact_income_price[$i] = 0;
            }
        }
    }
    $data['fact_income_price'] = $pay_sum_fact_income_price;
//pay 支出
//    预算
    for($i=0;$i<sizeof($classify_pay);$i++) {
        $pay_sum_budget_pay_sql = "select price from $individual_budget where budget = '支' and classify = '$classify_pay[$i]' ";
        $pay_sum_budget_pay_result = $conn->query($pay_sum_budget_pay_sql);
        while ($row = mysqli_fetch_array($pay_sum_budget_pay_result)) {
            $pay_sum_budget_pay_price[$i] = (int)$row['price'];
            if($pay_sum_budget_pay_price[$i] == null){
                $pay_sum_budget_pay_price[$i] = 0;
            }
        }
    }
    $data['budget_pay_price'] = $pay_sum_budget_pay_price;
//    实际支出
    for($i=0;$i<sizeof($classify_pay);$i++) {
        $pay_sum_fact_pay_sql = "select sum(price) as sum_price_fact_pay from $individual_table where budget = '支' and classify = '$classify_pay[$i]' and month = $budget_month ";
        $pay_sum_fact_pay_result = $conn->query($pay_sum_fact_pay_sql);
        while ($row = mysqli_fetch_array($pay_sum_fact_pay_result)) {
            $pay_sum_fact_pay_price[$i] = (int)$row['sum_price_fact_pay'];
            if($pay_sum_fact_pay_price[$i] == null){
                $pay_sum_fact_pay_price[$i] = 0;
            }
        }
    }
    $data['fact_pay_price'] = $pay_sum_fact_pay_price;
}

//setting01
$change = array();
$change_sql = "select * from signin WHERE nickName = '$individual'";
$change_result = $conn->query($change_sql);
$i =0;
if($change_result->num_rows>0){
    while($row = $change_result->fetch_assoc()){
        $change['password'] = $row['passWord'];
        $change['email'] = $row['EMail'];
        $change['image'] = $row['Image'];
        $change['myphone'] = $row['myPhone'];
        $change['takeover'] = $row['takeOver'];
    }

}
$data['change'] = $change;

$mes = array();
if(!empty($post['email'])){
    $mes = $post['email'];
    mysqli_query($conn,"UPDATE signin SET EMail = '$mes' WHERE nickName = '$individual'");
}else{
    $mes = null;
}
$data['mes'] = $mes;

$image = array();
if(!empty($post['image'])){
    $image = $post['image'];
    mysqli_query($conn,"UPDATE signin SET Image = '$image' WHERE nickName = '$individual'");
}else{
    $image = null;
}
$data['image'] = $image;

//setting03 mm
$password = array();
$password_remark = array();
if(!empty($post['newpassword'])){
    $oldpassword = $post['oldpassword'];
    $newpassword = $post['newpassword'];

    $sql_password = "SELECT * FROM signin";
    $result_password = $conn->query($sql_password);
    while($row = $result_password->fetch_assoc()){
        if($row['nickName'] == $individual && $row['passWord']== $oldpassword){
            $password_remark = true;
        }
    }
    if($password_remark == true){
        mysqli_query($conn,"UPDATE signin SET passWord = '$newpassword' WHERE nickName = '$individual' AND passWord = '$oldpassword'");
        $password_remark = true;
    }else{
        $password_remark = false;
    }
}else{
    $password =null;
}
$data['password'] = $password;
$data['passwrod_remark'] = $password_remark;

//setting03 email
if(!empty($post['email'])){
    $email = $post['email'];
    mysqli_query($conn,"UPDATE signin SET EMail = $email WHERE nickName = '$individual'");
}

//setting03 phone
if(!empty($post['phone'])){
    $phone = $post['phone'];
    mysqli_query($conn,"UPDATE signin SET myPhone = $phone WHERE nickName = '$individual'");
}

//takeover
if(!empty($post['takeover'])){
    $takeover = $post['takeover'];
    mysqli_query($conn,"UPDATE signin SET takeOver = $takeover WHERE nickName = '$individual'");
}

//setting05 add_classify
if(!empty($post['pay_classify'])){
    $pay_classify = $post['pay_classify'];
    $pay_classify_table = $individual."_classify_pay";
    mysqli_query($conn,"INSERT INTO $pay_classify_table (classify) VALUES ('$pay_classify')");
}

//setting05 delete
if(!empty($post['pay_id'])){
    $pay_id = $post['pay_id'];
    $pay_classify = $post['pay_classify'];
    $pay_classify_table = $individual."_classify_pay";
    mysqli_query($conn,"DElETE FROM $pay_classify_table WHERE id = $pay_id");
}

//setting05 add_classify
if(!empty($post['income_classify'])){
    $income_classify = $post['income_classify'];
    $income_classify_table = $individual."_classify_income";
    mysqli_query($conn,"INSERT INTO $income_classify_table (classify) VALUES ('$income_classify')");
}

//setting05 delete
if(!empty($post['income_id'])){
    $income_id = $post['income_id'];
    $income_classify = $post['income_classify'];
    $income_classify_table = $individual."_classify_income";
    mysqli_query($conn,"DElETE FROM $income_classify_table WHERE id = $income_id");
}

//setting06 change
if(!empty($post['price'])){
    $price_id = $post['id'];
    $price = $post['price'];
    $month = $post['month'];
    $price_budget = $individual."_budget_2016_".$month;
    mysqli_query($conn,"UPDATE $price_budget SET price = '$price' WHERE id = '$price_id'");
}

//account02 intsert
if(!empty($post['bank'])){
    $bank = $post['bank'];
    $limit = $post['limit'];
    $account_day = $post['account_day'];
    $repayment_day = $post['repayment_day'];
    $remark = "2";
    $bank_table = $individual."_bank";
    $account_table = $individual."_account";

    $classify_pay_sql = "select id from $bank_table";
    $classify_pay_result = $conn->query($classify_pay_sql);

    if($classify_pay_result->num_rows>0){
        while($row = $classify_pay_result->fetch_assoc()){
            $id = $row['id'];
        }
        $id = $id +1;
    }else{
        $id = 1;
    }
    $marked = "1";
    mysqli_query($conn,"INSERT INTO $bank_table (id,bank,priceLimit,accountDay,repaymentDay,marked) VALUES ('$id','$bank','$limit','$account_day','$repayment_day','$marked')");
    mysqli_query($conn,"INSERT INTO $account_table (account,remark) VALUES ('$bank','$remark')");
}
if(!empty($post['limit'])){
    $test = $post['limit'];
}else{
    $test = "fail";
}
$data['test'] = $test;

echo json_encode($data);


