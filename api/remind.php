<?php
//导入类库
require 'Slim/Slim.php';

//注册Slim框架自带的自动加载类
\Slim\Slim::registerAutoloader();

//创建实例
$app = new \Slim\Slim();

//显示消息
$app->get('/remind/:nickname','showRemind');
$app->post('/remind_add/:nickname','addRemind');
$app->delete('/remind_delete/:nickname/:id','deleteRemind');

function showRemind($nickname){
    $individual_remind = $nickname."_remind";
    $sql="select * from $individual_remind";
    try{
        $pdo=getConnect();
        $stmt=$pdo->query($sql);
        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo=null;
        //json不支持中文,使用前先转码
        foreach($data as $key=>$value){
            foreach ($value as $k=>$v){
                $data[$key][$k]=urlencode($v);
            }
        }
        echo urldecode(json_encode($data));
    }catch(PDOException $e){
        echo '{"err":'.$e->getMessage().'}';
    }
}

function addRemind($nickname){
    $individual_table = $nickname."_remind";

    $rawpostdata = file_get_contents("php://input");
    $post = json_decode($rawpostdata,true);

    $sql="insert into $individual_table (date,year,month,day,mes) values (:date,:year,:month,:day,:mes)";
    try{
        $pdo=getConnect();
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam("date",$post['date']);
        $stmt->bindParam("year",$post['year']);
        $stmt->bindParam("month",$post['month']);
        $stmt->bindParam("day",$post['day']);
        $stmt->bindParam("mes",$post['remind']);
        $stmt->execute();
        $id=$pdo->lastInsertId();
        $pdo=null;
        $result = "success";
        echo '['.json_encode($result).']';
    }catch(PDOException $e){
        echo '{"err":'.$e->getMessage().'}';
    }
}

function deleteRemind($nickname,$id){
    $remind_table = $nickname."_remind";
    $sql="delete from $remind_table where id = :id";
    try{
        $pdo=getConnect();
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam("id",$id);
        $stmt->execute();
        $pdo=null;
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