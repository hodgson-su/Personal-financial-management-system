<?php

//导入类库
require 'Slim/Slim.php';

//注册Slim框架自带的自动加载类
\Slim\Slim::registerAutoloader();

//创建实例
$app = new \Slim\Slim();
//显示
$app->get('/mes_show/:nickname','showHandle');
//添加
$app->post('/mes_add/:nickname','addHandle');
//删除
$app->delete('/mes_delete/:nickname/:id','deleteHandle');
//条件查询
$app->get("/:nickname/:search","searchHandle");

//显示
function showHandle($nickname){
	$individual_table = $nickname."_message";
	$sql="select * from $individual_table";
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

// 添加
function addHandle($nickname){
	$individual_table = $nickname."_message";
	$result = array();
	$data = array();
	$rawpostdata = file_get_contents("php://input");
	$post = json_decode($rawpostdata,true);
	$date = date('y-m-d h:i:s',time());
	$date_time_array = getdate(time());
	$year = $date_time_array["year"];
	$month = $date_time_array["mon"];
	$day = $date_time_array["mday"];
	$sql="insert into $individual_table (date,year,month,day,account,price,classify,remark,budget) values (:date,:year,:month,:day,:account,:price,:classify,:remark,:budget)";
	if(empty($post['remark'])){
		$remark = null;
	}else{
		$remark = $post['remark'];
	}
	try{
		$pdo=getConnect();
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam("date",$date);
		$stmt->bindParam("year",$year);
		$stmt->bindParam("month",$month);
		$stmt->bindParam("day",$day);
		$stmt->bindParam("account",$post['account']);
		$stmt->bindParam("price",$post['price']);
		$stmt->bindParam("classify",$post['classify']);
		$stmt->bindParam("remark",$remark);
		$stmt->bindParam("budget",$post['budget']);
		$stmt->execute();
		$id=$pdo->lastInsertId();
		$pdo=null;
		$result['success'] = 'success';
//		echo '{"id":'.$id.'}';
		$data['result'] = $result;
		echo '['.json_encode($result).']';
	}catch(PDOException $e){
		echo '{"err":'.$e->getMessage().'}';
	}
}

// 删除
function deleteHandle($nickname,$id){
	$individual_table = $nickname."_message";
	$sql="delete from $individual_table where id = :id";
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

//条件查询
function searchHandle($nickname,$search){
	$individual_table = $nickname."_message";
	$sql="select * from $individual_table where account like :search";
	try{
		$search="%".$search."%";
		$pdo=getConnect();
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam("search",$search);
		$stmt->execute();
		$data=$stmt->fetchAll(PDO::FETCH_ASSOC);
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

// 更新
$app->put(
	'/:nickname/:id',
	function($nickname,$id) use($app){
		$individual_table = $nickname."_message";
		$rawpostdata = file_get_contents("php://input");
		$post = json_decode($rawpostdata,true);
		//获取前台传过来的数据
		$data=$app->request->put();
		$sql="update $individual_table set account=:account,price=:price,classify=:classify,remark=:remark ,budget =:budget where id=:id";
		try{
			$pdo=getConnect();
			$stmt=$pdo->prepare($sql);
			$stmt->bindParam("account",$post['account']);
			$stmt->bindParam("price",$post['price']);
			$stmt->bindParam("classify",$post['classify']);
			$stmt->bindParam("remark",$post['remark']);
			$stmt->bindParam("budget",$post['budget']);
			$stmt->bindParam("id",$id);
			$stmt->execute();
			$pdo=null;
			echo json_encode($data);
		}catch(PDOException $e){
			echo '{"err":'.$e->getMessage().'}';
		}
	}
);

//连接数据库
function getConnect($h='localhost',$u="root",$p="",$db="finicial_management"){
	$pdo = new PDO("mysql:host=$h;dbname=$db",$u,$p,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"));
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $pdo;
}

//运行应用
$app->run();