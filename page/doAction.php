
<?php
/**
 * Created by PhpStorm.
 * User: 56252
 * Date: 2017/11/7
 * Time: 12:19
 */
//调用数据库函数库来获取数据
require_once '../lib/IPDO.class.php';
//配置数据链接信息

$option  = array(
    'host'      =>  '127.0.0.1',
    'user'      =>  'root',
    'pass'      =>  '1234',
    'dbname'    =>  'xueji1',
    'port'      =>  3306,
    'charset'   => 'utf8'
);
$pdo = IPDO::getSingleton($option);

session_start();
switch ($_GET['act']){
    case "login":
        loginChk();break;
    case "ulist":
        userList();break;
    case "msglist":
        msgList();break;
    case "del":
        delUser();break;
    case "tj":
        tjUser();break;
    case "uselect":
        uSelect();break;
    case "sendmsg":
        sendMsg();break;
    case "readmsg":
        readMsg();break;
    case "deldmsg":
        delMsg();break;
    case "mkread":
        mkRead();break;
    case "sendtg":
        sendTonggao();break;
    case "tglist":
        tgList();break;
    case "tjmajor":
        tjMajor();break;
    case "mjlist":
        mjList();break;
    case "schmjlist":
        schmjList();break;
    case "cselect":
        cSelect();break;
    case "sselect":
        sSelect();break;
    case "mjselect":
        mjSelect();break;
    case "xgmm":
        xgMima();break;
    case "tjsmj":
        tjsMajor();break;
    case "tjsch":
        tjSchool();break;
    case "tjstu":
        tjStu();break;
    case "upload":
        upLoad();break;
    case "stulist":
        stuList();break;
    case "xzselect":
        xzSelect();break;
    case "cxstu":
        cxStu();break;
    case "wh":
        whStu();break;
    case "reg":
        regStu();break;
    case "gra":
        graStu();break;
    case "newtg":
        newTg();break;
    case "logout":
        logOut();break;
    default:break;

}
function loginChk(){
    global $pdo;
    $data=isset($_POST['data'])?$_POST['data']:'';
    $array=object_array(json_decode($data));
    $username = $array['username'];
    $password = md5($array['password']);
    $sql = "select * from userinfo where username='{$username}' and password='{$password}'";
    if($pdo ->fetchRow($sql)){
        $_SESSION['username']= $username;
        $sql1="select role from userinfo where username='$username'";
        $res=$pdo->fetchRow($sql1);
        $role=$res['role'];
        $_SESSION['role']=$role;
        $result=array(
            'errcode'=>0,
            'role'=>$role
        );
    }else{
        $result=array(
            'errcode'=>1,
            'role'=>0
        );
    }
    echo json_encode($result);
}
function userList(){
    global $pdo;
    $page=$_GET['page'];
    $limit=$_GET['limit'];
    $offset=($page-1)*$limit;
    $username=isset($_GET['username'])?$_GET['username']:'';
    $code=0;
    $msg='';
    if($username==''){
        $sql="SELECT * FROM userinfo u LEFT JOIN city c ON c.cityid=u.belcity LEFT JOIN school s on s.schid=u.belsch where username <> 'admin' limit $offset,$limit";
        $sql1="select * from userinfo";
        $res=$pdo->fetchAll($sql);
        foreach ($res as $k=>$v){
            if($v['role']==1){
                $res[$k]['role']="总管理员";
            }
            if($v['role']==3){
                $res[$k]['role']="市级管理员";
            }
            if($v['role']==4){
                $res[$k]['role']="校级管理员";
            }
        }
        $totalres=$pdo->fetchAll($sql1);
        $count=count($totalres);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    }else{
        $sql="select * from userinfo where username='$username'";
        $res=$pdo->fetchAll($sql);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>count($res),"data"=>$res);
    }
    echo json_encode($res1);
//    return json_encode($res1);
}
function delUser(){
    global $pdo;
    $err=array();
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
//    var_dump($array);
    foreach ($array as $k=>$v){
        if(is_array($v)){
                $sql="delete from userinfo where uid=".$v['uid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
        }else{
            $sql="delete from userinfo where uid=".$array['uid'];
            if($pdo->exec($sql)==1){
                $err[]=1;
            }else{
                $err[]=0;
            }
            break;
        }
    }
    echo json_encode($err);
}
function tjUser(){
    global $pdo;
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
//    var_dump($array);
    $username=$array['username'];
    $password=md5($array['password']);
    $city=$array['cselect'];
    $school=$array['sselect'];
    $role=$array['role'];
    $phone=$array['phone'];
    $address=$array['address'];
    $sql1="select * from userinfo WHERE username = '$username'";
    $res=$pdo->fetchAll($sql1);
    if($res){
        $result=array(
            "errcode"=>0
        );
    }else{
        if($role==3){
            $sql2="insert into userinfo VALUES ('','$username','$password',$role,'$phone','$address',null,$city)";
            $res=$pdo->exec($sql2);
            if($res){
                $result=array(
                    "errcode"=>1
                );
            }else {
                $result = array(
                    "errcode" => 2
                );
            }
        }
        if($role==4){
            $sql2="insert into userinfo VALUES ('','$username','$password',$role,'$phone','$address',$school,$city)";
            $res=$pdo->exec($sql2);
            if($res){
                $result=array(
                    "errcode"=>1
                );
            }else {
                $result = array(
                    "errcode" => 2
                );
            }
        }
    }

    echo json_encode($result);
}
function uSelect(){
    global $pdo;
    $result=array();
    $sql="select * from userinfo";
    foreach ($pdo->fetchAll($sql) as $k =>$v){
        $arr=array(
        "id"=>$v["uid"],
        "username"=> $v["username"]
        );
        array_push($result,$arr);
    }
    echo json_encode($result);
//    var_dump(json_encode($pdo->fetchAll($sql) ));
}
function sendMsg(){
    global $pdo;
    $date=date("Y-m-d H:i:s");
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $title=$array["title"];
    $recid=$array["receiver"];
    $sentnm=$_GET['user'];
    $content=$array["content"];
    $sql="select username from userinfo WHERE id='$recid'";
    $recnm=$pdo->fetchRow($sql)["username"];
    $sql="insert into message VALUES ('','$title','$sentnm','$recnm','$content',1,'$date')";
    if ($pdo->exec($sql)==1){
        $result=array(
            "errcode" => 0
        );
    }else{
        $result=array(
            "errcode" => 1
        );
    }
    echo json_encode($result);
}
function msgList(){
    global $pdo;
    $state=isset($_GET['state'])?$_GET['state']:'';
    $page=$_GET['page'];
    $limit=$_GET['limit'];
    $offset=($page-1)*$limit;
    $rece=$_GET['user'];
    $code=0;
    $msg='';
    if($state==''){
        $sql="select * from message WHERE receiver ='$rece' limit $offset,$limit";
        $sql1="select * from message";
        $res=$pdo->fetchAll($sql);
        foreach ($res as $k=>$v){
            if($v['state']==1){
                $res[$k]['state']="未读";
            }
            if($v['state']==2){
                $res[$k]['state']="已读";
            }
        }
        $totalres=$pdo->fetchAll($sql1);
        $count=count($totalres);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    }elseif ($state==1){
        $sql="select * from message WHERE receiver ='$rece' AND state=1 limit $offset,$limit";
        $sql1="select * from message WHERE receiver ='$rece' AND state=1";
        $res=$pdo->fetchAll($sql);
        foreach ($res as $k=>$v){
            if($v['state']==1){
                $res[$k]['state']="未读";
            }
        }
        $totalres=$pdo->fetchAll($sql1);
        $count=count($totalres);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    }else{
        $sql="select * from message WHERE receiver ='$rece' AND state=2 limit $offset,$limit";
        $sql1="select * from message WHERE receiver ='$rece' AND state=2";
        $res=$pdo->fetchAll($sql);
        foreach ($res as $k=>$v){

            if($v['state']==2){
                $res[$k]['state']="已读";
            }
        }
        $totalres=$pdo->fetchAll($sql1);
        $count=count($totalres);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    }

    echo json_encode($res1);

}
function readMsg(){
    global $pdo;
    $err=array();
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $id=$array['id'];
    $sql="update message set state = 2 where id=$id";
    $pdo->exec($sql);
}
function delMsg(){
    global $pdo;
    $err=array();
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $id=$array['id'];
    $sql="delete from message where id=$id";
    if($pdo->exec($sql)==1){
        $err=array(
            "errcode"=>1
        );
    }else{
        $err=array(
            "errcode"=>0
        );
    }
    echo json_encode($err);
}
function mkRead(){
    global $pdo;
    $err=array();
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $state=isset($_POST["state"])?$_POST["state"]:'';
    $array=object_array(json_decode($data));
    if($state==1){
        foreach ($array as $k=>$v){
            if($v['state']=='未读'){
                unset($array[$k]);
            }
        }
        foreach ($array as $k=>$v){
            if(is_array($v)){
                $sql="update message set state = $state where id=".$v['id'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
            }else{
                $sql="update message set state = $state where id=".$array['id'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
                break;
            }
        }
    }
    if($state==2){
        foreach ($array as $k=>$v){
            if($v['state']=="已读"){
                unset($array[$k]);
            }
        }
        foreach ($array as $k=>$v){
            if(is_array($v)){
                $sql="update message set state = $state where id=".$v['id'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
            }else{
                $sql="update message set state = $state where id=".$array['id'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
            }
        }
    }
    echo json_encode($err);
}
function sendTonggao(){
    global $pdo;
    $date=date("Y-m-d H:i:s");
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $title=$array['title'];
    $content=$array['content'];
    $sql="insert into tonggao VALUES ('','$title','$date','$content')";
    if($pdo->exec($sql)==1){
        $err=array(
            "errcode"=>1
        );
    }else{
        $err=array(
            "errcode"=>0
        );
    }
    echo json_encode($err);
}
function tgList(){
    global $pdo;
    $page=$_GET['page'];
    $limit=$_GET['limit'];
    $offset=($page-1)*$limit;
    $code=0;
    $msg='';
    $sql="select * from tonggao limit $offset,$limit";
    $sql1="select * from tonggao";
    $res=$pdo->fetchAll($sql);
    $totalres=$pdo->fetchAll($sql1);
    $count=count($totalres);
    $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    echo json_encode($res1);
}
function tjMajor(){
    global $pdo;
    $date=date("Y-m-d H:i:s");
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $mid=$array['majorid'];
    $mname=$array['majornm'];
    $sql="select * from major WHERE mid= $mid";
    $sql1="select * from major WHERE mname= '$mname'";
    $sql2="insert into major VALUES ('$mid','$mname','$date')";
    if(count($pdo->fetchAll($sql))>0){
        $err=array(
            "errcode"=>1
        );
    }
    if(count($pdo->fetchAll($sql1))>0){
        $err=array(
            "errcode"=>2
        );
    }
    if(count($pdo->fetchAll($sql))<=0&&count($pdo->fetchAll($sql1))<=0){
        if($pdo->exec($sql2)==1){
            $err=array(
                "errcode"=>0
            );
        }else{
            $err=array(
                "errcode"=>3
            );
        }
    }
    echo json_encode($err);

}
function mjList(){
    global $pdo;
    $page=$_GET['page'];
    $limit=$_GET['limit'];
    $offset=($page-1)*$limit;
    $mjname=isset($_GET['mname'])?$_GET['mname']:'';
    $code=0;
    $msg='';
    if($mjname==''){
        $sql="select * from major limit $offset,$limit";
        $sql1="select * from major";
        $res=$pdo->fetchAll($sql);
        $totalres=$pdo->fetchAll($sql1);
        $count=count($totalres);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    }else{
        $sql="select * from major where mname='$mjname'";
        $res=$pdo->fetchAll($sql);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>count($res),"data"=>$res);
    }
    echo json_encode($res1);
}
function schmjList(){
    global $pdo;
    $page=$_GET['page'];
    $limit=$_GET['limit'];
    $offset=($page-1)*$limit;
    $mjname=isset($_GET['mname'])?$_GET['mname']:'';
    $uname=isset($_GET['user'])?$_GET['user']:'';
    $sql="select belsch from userinfo WHERE username='$uname'";
    $sch=$pdo->fetchRow($sql)['belsch'];
    $code=0;
    $msg='';
    if($mjname==''){
        $sql="select * from schmajor WHERE belsch=$sch limit $offset,$limit";
        $sql1="select * from schmajor WHERE belsch=$sch";
        $res=$pdo->fetchAll($sql);
        $totalres=$pdo->fetchAll($sql1);
        $count=count($totalres);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    }else{
        $sql="select * from schmajor where mname='$mjname' AND belsch=$sch";
        $res=$pdo->fetchAll($sql);
        $res1=array("code"=>$code,"msg"=>$msg,"count"=>count($res),"data"=>$res);
    }
    echo json_encode($res1);
}
function cSelect(){
    global $pdo;
    $result=array();
    $sql="select * from city";
    foreach ($pdo->fetchAll($sql) as $k =>$v){
        $arr=array(
            "id"=>$v["cityid"],
            "cityname"=> $v["cityname"]
        );
        array_push($result,$arr);
    }
    echo json_encode($result);
//    var_dump(json_encode($pdo->fetchAll($sql) ));
}
function sSelect(){
    global $pdo;
    $result=array();
    $cityid=isset($_POST['cityid'])?$_POST['cityid']:'';
    if($cityid!=''){
        $sql="select * from school WHERE cityid='$cityid'";
        foreach ($pdo->fetchAll($sql) as $k =>$v){
            $arr=array(
                "id"=>$v["schid"],
                "schname"=> $v["schname"]
            );
            array_push($result,$arr);
        }
    }else{
        $sql="select * from school";
        foreach ($pdo->fetchAll($sql) as $k =>$v){
            $arr=array(
                "id"=>$v["schid"],
                "schname"=> $v["schname"]
            );
            array_push($result,$arr);
        }
    }
    echo json_encode($result);
}
function xgMima(){
    global $pdo;
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $username=$array['username'];
    $oldpass=md5($array['opassword']);
    $pass=md5($array['password']);
    $sql="select uid from userinfo WHERE username='$username'";
    $uid=$pdo->fetchRow($sql)['uid'];
    $sql="select password from userinfo WHERE username='$username'";
    $oldtrue=$pdo->fetchRow($sql)['password'];
    if($oldtrue==$oldpass){
        $sql="update userinfo set password = '$pass' where uid=$uid";
        if($pdo->exec($sql)==1){
            $err=array(
                "errcode"=>1
            );
        }else{
            $err=array(
                "errcode"=>0
            );
        }
    }else{
        $err=array(
            "errcode"=>2
        );
    }
    echo json_encode($err);
}
function tjsMajor(){
    global $pdo;
    $err=array();
    $date=date("Y-m-d H:i:s");
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $user=$_GET['user'];
    $sql="select belsch from userinfo WHERE username='$user'";
    $sch=$pdo->fetchRow($sql)['belsch'];
    foreach ($array as $k=>$v){
        if(is_array($v)){
            $two=true;
        }else{
            $two=false;
        }
    }
    if($two){
        foreach ($array as $k=>$v){
            $mid=$v['mid'];
            $sql="select * from schmajor WHERE mid='$mid' AND belsch=$sch";
            if(count($pdo->fetchAll($sql))>=1){
                unset($array[$k]);
            }
        }
        foreach ($array as $k=>$v) {
            if (is_array($v)) {
                $mid=$v['mid'];
                $mname=$v['mname'];
                $sql="insert into schmajor VALUES ('$mid','$mname','$date',$sch)";
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=2;
                }
            } else {
                $mid=$array['mid'];
                $mname=$array['mname'];
                $sql="insert into schmajor VALUES ('$mid','$mname','$date',$sch)";
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=2;
                }
                break;
            }
        }
        if(count($array)==0){
            $err[]=0;
        }
    }else{
        $mid=$array['mid'];
        $sql="select * from schmajor WHERE mid='$mid' AND belsch=$sch";
        if(count($pdo->fetchAll($sql))>=1){
            $err[]=0;
        }else{
            $mid=$array['mid'];
            $mname=$array['mname'];
            $sql="insert into schmajor VALUES ('$mid','$mname','$date',$sch)";
            if($pdo->exec($sql)==1){
                $err[]=1;
            }else{
                $err[]=2;
            }
        }
    }
    echo json_encode($err);
}
function tjSchool(){
    global $pdo;
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $schid=$array['schid'];
    $schname=$array['schnm'];
    $cityid=$array['cselect'];
    if($array['xuezhi']==1){
        $xuezhi='3';
    }elseif ($array['xuezhi']==2){
        $xuezhi='5';
    }elseif ($array['xuezhi']==3){
        $xuezhi='3,5';
    }else{
        $xuezhi='';
    }
    $sql="select * from school WHERE schid= $schid";
    $sql1="select * from school WHERE schname= '$schname'";
    $sql2="insert into school VALUES ($schid,'$schname',$cityid,'$xuezhi')";
    if(count($pdo->fetchAll($sql))>0){
        $err=array(
            "errcode"=>1
        );
    }
    if(count($pdo->fetchAll($sql1))>0){
        $err=array(
            "errcode"=>2
        );
    }
    if(count($pdo->fetchAll($sql))<=0&&count($pdo->fetchAll($sql1))<=0){
        if($pdo->exec($sql2)==1){
            $err=array(
                "errcode"=>0
            );
        }else{
            $err=array(
                "errcode"=>3
            );
        }
    }
    echo json_encode($err);

}
function mjSelect(){
    global $pdo;
    $result=array();
    $username=isset($_POST['user'])?$_POST['user']:'';
    if($username!=''){
        $sql="select belsch from userinfo WHERE username='$username'";
        $schid=$pdo->fetchRow($sql)['belsch'];
        $sql="select * from schmajor WHERE belsch=$schid";
        foreach ($pdo->fetchAll($sql) as $k =>$v){
            $arr=array(
                "mid"=>$v["mid"],
                "mname"=> $v["mname"]
            );
            array_push($result,$arr);
        }
    }else{
        $sql="select * from major";
        foreach ($pdo->fetchAll($sql) as $k =>$v){
            $arr=array(
                "mid"=>$v["mid"],
                "mname"=> $v["mname"]
            );
            array_push($result,$arr);
        }
    }


    echo json_encode($result);
}
function upLoad(){
    $msg='';
    if (file_exists("../photo/" . $_FILES["file"]["name"]))
    {
        $code=1;
        $res=array(
            "src"=>""
        );
        $res1=array("code"=>$code,"msg"=>$msg,"data"=>$res);

    }
    else
    {
        move_uploaded_file($_FILES["file"]["tmp_name"],
            "../photo/" . "FDFDDF");
        $code=0;
        $res=array(
            "src"=>"../photo/".  "FDFDDF"
        );
        $res1=array("code"=>$code,"msg"=>$msg,"data"=>$res);
    }
    echo  json_encode($res1);
}
function tjStu(){
    global $pdo;
    $err=array(
        "siderr"=>0,
        "phoneerr"=>0,
        "idnumerr"=>0,
        "err"=>2
    );
    $date=date("Y-m");
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $sid=$array['sid'];
    $name=$array['name'];
    $sex=$array['sex'];
    $identity=$array['identity'];
    $str1="";
    $str2="";
    $str3="";
    for($i=6;$i<10;$i++){
        $str1.=$identity[$i];
    }
    for($i=10;$i<12;$i++){
        $str2.=$identity[$i];
    }
    for($i=12;$i<14;$i++){
        $str3.=$identity[$i];
    }
    $date= $str1.'-'.$str2.'-'.$str3;
    $address=$array['address'];
    $phone=$array['phone'];
    $major=$array['major'];
    $username=$_POST['user'];
    $sql4="select belsch from userinfo WHERE username='$username'";
    $schid=$pdo->fetchRow($sql4)['belsch'];
    $sql="select * from student WHERE sid='$sid'";
    $sql1="select * from student WHERE idnum='$identity'";
    $sql2="select * from student WHERE phone='$phone'";
    if(count($pdo->fetchAll($sql))>=1){
        $err['siderr']=1;
    }
    if(count($pdo->fetchAll($sql1))>=1){
        $err['idnumerr']=1;
    }
    if(count($pdo->fetchAll($sql2))>=1){
        $err['phoneerr']=1;
    }
    if(count($pdo->fetchAll($sql))==0&&count($pdo->fetchAll($sql1))==0&&count($pdo->fetchAll($sql2))==0){
        $photosrc="../photo/".$identity.".jpg";
        $sql3="insert into student VALUES ('$sid','$name','$sex','$date','$identity','$address','$phone',$schid,$major,'$photosrc','$date','',1,1)";
        if($pdo->exec($sql3)==1){
            rename("../photo/FDFDDF",$photosrc);
            $err['err']=0;
        }else{
            unlink("../photo/FDFDDF");
            $err['err']=1;
        }
    }
    echo json_encode($err);
}
function stuList(){
    global $pdo;
    $page=$_GET['page'];
    $limit=$_GET['limit'];
    $offset=($page-1)*$limit;
    $name=isset($_GET['name'])?$_GET['name']:'';
    $sch=isset($_GET['school'])?$_GET['school']:'';
    $major=isset($_GET['major'])?$_GET['major']:'';
    $sex=isset($_GET['sex'])?$_GET['sex']:'';
    $sid=isset($_GET['sid'])?$_GET['sid']:'';
    $idnum=isset($_GET['idnum'])?$_GET['idnum']:'';
    $user=isset($_GET['user'])?$_GET['user']:'';
    $reg=isset($_GET['reg'])?$_GET['reg']:'';
    $gra=isset($_GET['gra'])?$_GET['gra']:'';
    $code=0;
    $msg='';
    $sql1="select belsch from userinfo WHERE username='$user'";
    $schid=$pdo->fetchRow($sql1)['belsch'];
    if ($user==''){
        $sql="SELECT * FROM student s LEFT JOIN school sc ON s.school=sc.schid LEFT JOIN major m ON s.major=m.mid  WHERE 1=1 ";

    }else{
        $sql="SELECT * FROM student s LEFT JOIN school sc ON s.school=sc.schid LEFT JOIN major m ON s.major=m.mid  WHERE school=$schid AND 1=1 ";
    }
    if($user!=''&&$reg==1){
        $sql="SELECT * FROM student s LEFT JOIN school sc ON s.school=sc.schid LEFT JOIN major m ON s.major=m.mid  WHERE school=$schid AND regstate=1 AND 1=1 ";
    }
    if($user!=''&&$reg==2&&$gra==1){
        $sql="SELECT * FROM student s LEFT JOIN school sc ON s.school=sc.schid LEFT JOIN major m ON s.major=m.mid  WHERE school=$schid AND regstate=2 AND grastate=1 AND 1=1 ";
    }
    if($user!=''&&$reg==2&&$gra==3){
        $sql="SELECT * FROM student s LEFT JOIN school sc ON s.school=sc.schid LEFT JOIN major m ON s.major=m.mid  WHERE regstate=2 AND grastate=3 AND 1=1 ";
    }
    if($user!=''&&$reg==3){
        $sql="SELECT * FROM student s LEFT JOIN school sc ON s.school=sc.schid LEFT JOIN major m ON s.major=m.mid  WHERE  regstate=3 AND 1=1 ";
    }
    if($name!=''){
        $sql.=" and name='$name'";
    }
    if($sid!=''){
        $sql.=" and sid='$sid'";
    }
    if($idnum!=''){
        $sql.=" and idnum='$idnum'";
    }
    if($sex!=''){
        if($sex==1){
            $sex='男';
        }else{
            $sex='女';
        }
        $sql.=" and sex='$sex'";
    }
    if($sch!=''){
        $sql.=" and school=$sch";
    }
    if($major!=''){
        $sql.=" and major='$major'";
    }
    $totalres=$pdo->fetchAll($sql);
    $count=count($totalres);
    $sql.=" limit $offset,$limit";
    $res=$pdo->fetchAll($sql);
//    var_dump($res);
    foreach ($res as $k=>$v){
        if(is_array($v)){
            $isarry=true;
        }else{
            $isarry=false;
        }
        if($isarry){
            if($v['regstate']==1){
                $res[$k]['regstate']="未注册";
            }
            if($v['regstate']==2){
                $res[$k]['regstate']="已注册";
            }
            if($v['regstate']==3){
                $res[$k]['regstate']="注册待审批";
            }
            if($v['grastate']==1){
                $res[$k]['grastate']="未毕业";
            }
            if($v['grastate']==2){
                $res[$k]['grastate']="已毕业";
            }
            if($v['grastate']==3){
                $res[$k]['grastate']="毕业待审批";
            }
        }elseif(count($res)==1){
            if($res['regstate']==1){
                $res['regstate']="未注册";
            }
            if($res['regstate']==2){
                $res['regstate']="已注册";
            }
            if($v['regstate']==3){
                $res[$k]['regstate']="注册待审批";
            }
            if($res['grastate']==1){
                $res['grastate']="未毕业";
            }
            if($res['grastate']==2){
                $res['grastate']="已毕业";
            }
            if($v['grastate']==3){
                $res[$k]['grastate']="毕业待审批";
            }
        }

    }
    $res1=array("code"=>$code,"msg"=>$msg,"count"=>$count,"data"=>$res);
    echo json_encode($res1);
//    return json_encode($res1);
}
function xzSelect(){
    global $pdo;
    $res=array();
    $user=$_POST['user'];
    $sql="select belsch from userinfo WHERE username='$user'";
    $schid=$pdo->fetchRow($sql)['belsch'];
    $sql="select xuezhi from school WHERE schid=$schid";
    $result=$pdo->fetchRow($sql);
    if($result['xuezhi']=='3'){
        $arr=array(
            "value"=>'3',
            "xuezhi"=>"3"
        );
        array_push($res,$arr);
    }
    if($result['xuezhi']=='5'){
        $arr=array(
            "value"=>'5',
            "xuezhi"=>"5"
        );
        array_push($res,$arr);
    }
    if($result['xuezhi']=='3,5'){
        $arr=array(
            "value"=>'3,5',
            "xuezhi"=>"3,5"
        );
        array_push($res,$arr);
    }
    echo json_encode($res);

}
function cxStu(){
    global $pdo;
    $idnum=$_POST['idnum'];
    $sql="select * from student WHERE idnum='$idnum'";

    echo json_encode($pdo->fetchAll($sql));
}
function whStu(){
    global $pdo;
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $sid=$array['sid'];
    $name=$array['name'];
    $sex=$array['sex'];
    $address=$array['address'];
    $phone=$array['phone'];
    $sql1="select idnum from student WHERE sid='$sid'";
    $idnum=$pdo->fetchRow($sql1)['idnum'];
    if($array['photosrc']!=''){
        $photosrc="../photo/".$idnum.".jpg";
    }
    $sql="update student set name='$name' , sex='$sex' , address='$address' , phone='$phone' , photo='$photosrc' WHERE sid='$sid'";
    if($pdo->exec($sql)==1){
        rename("../photo/FDFDDF",$photosrc);
        $err['err']=0;
    }else{
        unlink("../photo/FDFDDF");
        $err['err']=1;
    }
    echo json_encode($err);
}
function regStu(){
    global $pdo;
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $user=$_POST['user'];
    $sql="select role from userinfo WHERE username='$user'";
    $role=$pdo->fetchRow($sql)['role'];
    if($role==4){
        foreach ($array as $k=>$v){
            if(is_array($v)){
                $sql="update student set regstate=3 WHERE sid=".$v['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
            }else{
                $sql="update student set regstate=3 WHERE sid=".$array['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
                break;
            }
        }
    }
    if($role==3){
        foreach ($array as $k=>$v){
            if(is_array($v)){
                $sql="update student set regstate=2 WHERE sid=".$v['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
            }else{
                $sql="update student set regstate=2 WHERE sid=".$array['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
                break;
            }
        }
    }
    echo json_encode($err);
}
function graStu(){
    global $pdo;
    $date=date("Y-m-d");
    $data=isset($_POST["data"])?$_POST["data"]:'';
    $array=object_array(json_decode($data));
    $user=$_POST['user'];
    $sql="select role from userinfo WHERE username='$user'";
    $role=$pdo->fetchRow($sql)['role'];
    if($role==4){
        foreach ($array as $k=>$v){
            if(is_array($v)){
                $sql="update student set grastate=3 WHERE sid=".$v['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
            }else{
                $sql="update student set grastate=3 WHERE sid=".$array['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
                break;
            }
        }
    }
    if($role==3){
        foreach ($array as $k=>$v){
            if(is_array($v)){
                $sql="update student set grastate=2 ,gradate='$date' WHERE sid=".$v['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
            }else{
                $sql="update student set grastate=2 ,gradate='$date' WHERE sid=".$array['sid'];
                if($pdo->exec($sql)==1){
                    $err[]=1;
                }else{
                    $err[]=0;
                }
                break;
            }
        }
    }
    echo json_encode($err);
}
function newTg(){
    global $pdo;
    $sql="select * from tonggao ORDER BY time DESC";
    echo json_encode($pdo->fetchAll($sql));
}
function logOut(){
    unset($_SESSION['username']);
    unset($_SESSION['role']);
    if(!isset($_SESSION['username'])&&!isset($_SESSION['role'])){
        $err=array(
            "err"=>0
        );
    }else{
        $err=array(
            "err"=>1
        );
    }
    echo json_encode($err);
}
//用于处理js传递的数据
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}
?>