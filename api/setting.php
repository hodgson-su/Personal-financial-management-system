<?php

//导入类库
require 'Slim/Slim.php';

//注册Slim框架自带的自动加载类
\Slim\Slim::registerAutoloader();

//创建实例
$app = new \Slim\Slim();
//显示
$app->get('/classify_show/:nickname/:month','showClassify');

function showClassify($nickname,$month){
    $pay_classify = $nickname."_classify_pay";
    $income_classify = $nickname."_classify_income";
    $budget = $nickname."_budget_2016_".$month;
    $bank = $nickname."_bank";
    $message = $nickname."_message";
    $sql_pay="select * from $pay_classify";
    $sql_income = "select * from $income_classify";
    $sql_budget = "select * from $budget";
    $sql_bank = "select * from $bank";
    $sql_message = "select * from $message";
    try{
        $pdo=getConnect();
        $stmt=$pdo->query($sql_pay);
        $data_pay=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo=null;
        //json不支持中文,使用前先转码
        foreach($data_pay as $key=>$value){
            foreach ($value as $k=>$v){
                $data_pay[$key][$k]=urlencode($v);
            }
        }
        $data['pay_classify'] = $data_pay;

        $pdo=getConnect();
        $stmt=$pdo->query($sql_income);
        $data_income=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo=null;
        //json不支持中文,使用前先转码
        foreach($data_income as $key=>$value){
            foreach ($value as $k=>$v){
                $data_income[$key][$k]=urlencode($v);
            }
        }
        $data['income_classify'] = $data_income;

        $pdo=getConnect();
        $stmt=$pdo->query($sql_budget);
        $data_budget=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo=null;
        //json不支持中文,使用前先转码
        foreach($data_budget as $key=>$value){
            foreach ($value as $k=>$v){
                $data_budget[$key][$k]=urlencode($v);
            }
        }
        $data['data_budget'] = $data_budget;
//account02 bank
        $pdo=getConnect();
        $stmt=$pdo->query($sql_bank);
        $data_bank=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo=null;
        //json不支持中文,使用前先转码
        foreach($data_bank as $key=>$value){
            foreach ($value as $k=>$v){
                $data_bank[$key][$k]=urlencode($v);
            }
        }
        $data['data_bank'] = $data_bank;

        $pdo=getConnect();
        $stmt=$pdo->query($sql_message);
        $data_message=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo=null;
        //json不支持中文,使用前先转码
        foreach($data_message as $key=>$value){
            foreach ($value as $k=>$v){
                $data_message[$key][$k]=urlencode($v);
            }
        }
        $data['data_message'] = $data_message;

        echo urldecode(json_encode($data));
    }catch(PDOException $e){
        echo '{"err":'.$e->getMessage().'}';
    }
}

//连接数据库
function getConnect($h='localhost',$u="root",$p="",$db="finicial_management"){
    $pdo = new PDO("mysql:host=$h;dbname=$db",$u,$p,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

//运行应用
$app->run();

