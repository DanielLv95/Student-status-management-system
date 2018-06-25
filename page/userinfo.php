<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2017/11/6
 * Time: 11:36
 */
header('Content-type: application/json');
require_once '../config/conn.php';
//调用数据库函数库来获取数据
require_once LIB_ROOT . 'IPDO.class.php';
//调用链接函数
$code=0;
$msg='';
$count='';
$data='';
$nowpage=$_GET['page'];
$limit=$_GET['limit'];
$offset=($nowpage-1)*$limit;
$pdo = IPDO::getSingleton($option);
$sql="select * from loginfo limit $offset,$limit";
$res=$pdo->fetchAll($sql);
//var_dump($res);
$res1=array("code"=>$code,"msg"=>$msg,"count"=>count($res),"data"=>$res);
echo json_encode($res1);
//echo json_encode($res);
//return json_encode($res);
//var_dump($res);
//for($i=1;$i<=100;$i++){
//    $sql="insert into loginfo VALUES (null,\"zhqngsan.$i\",\"zhqngsan.$i\",3,\"烟台市蓬莱.$i\")";
//    $res=$pdo->exec($sql);
//    var_dump($res);
//}
?>